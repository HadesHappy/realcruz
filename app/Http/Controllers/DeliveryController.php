<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Library\Log as MailLog;
use Acelle\Library\StringHelper;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use GuzzleHttp\Client;
use Acelle\Model\TrackingLog;
use Acelle\Model\BounceLog;
use Acelle\Model\SendingServer;
use Acelle\Model\SendingServerMailgun;
use Acelle\Model\SendingServerSendGrid;
use Acelle\Model\SendingServerElasticEmail;
use Acelle\Model\SendingServerSparkPost;
use Acelle\Model\SendingServerPostal;
use Acelle\Model\FeedbackLog;
use Acelle\Model\Blacklist;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Exception;

class DeliveryController extends Controller
{
    /**
     * Campaign notification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function notify(Request $request)
    {
        // Make sure the request is POST
        // ElasticEmail send notification via GET
        // if (!$request->isMethod('post')) {
        //     return;
        // }

        $type = $request->stype;

        try {
            if ($type == 'amazon') { // @TODO hard-coded here, seeking for a solution
                $this->handleAws();
            } elseif ($type == SendingServerMailgun::WEBHOOK) {
                SendingServerMailgun::handleNofification();
            } elseif ($type == SendingServerSendGrid::WEBHOOK) {
                $this->handleSendGrid();
            } elseif ($type == SendingServerElasticEmail::WEBHOOK) {
                MailLog::configure(storage_path().'/logs/handler-elasticemail.log');
                SendingServerElasticEmail::handleNotification($_GET);
            } elseif ($type == SendingServerSparkPost::WEBHOOK) {
                MailLog::configure(storage_path().'/logs/handler-sparkpost.log');
                SendingServerSparkPost::handleNotification($_GET);
            } else {
                throw new Exception('Unknown notification type');
            }

            return response()->json(['success' => 'OK']);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'error' => $ex->getMessage(),
                'debug' => $ex->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Handle SendGrid Event Notification
     *
     * @param SendGrid POST
     */
    private function handleSendGrid()
    {
        // Reference: https://sendgrid.com/docs/for-developers/tracking-events/event/

        MailLog::configure(storage_path().'/logs/handler-sendgrid.log');
        $messages = json_decode(file_get_contents('php://input'), true);
        MailLog::info(file_get_contents('php://input'));

        foreach ($messages as $message) {
            switch ($message['event']) {
                case 'dropped':
                case 'bounce':
                    $bounceLog = new BounceLog();

                    // runtime-message_id is set by SendGrid SMTP API
                    // in case of SendGrid Web API, use sg_message_id instead
                    if (array_key_exists("runtime_message_id", $message)) {
                        $bounceLog->runtime_message_id = $message["runtime_message_id"];
                    }

                    // retrieve the associated tracking log in Acelle
                    $trackingLog = TrackingLog::where('runtime_message_id', $bounceLog->runtime_message_id)->first();
                    if ($trackingLog) {
                        $bounceLog->message_id = $trackingLog->message_id;
                    }

                    // SendGrid only notifies in case of HARD bounce
                    $bounceLog->bounce_type = BounceLog::HARD;
                    $bounceLog->raw = json_encode($message);
                    $bounceLog->save();
                    MailLog::info('Bounce recorded for message '.$bounceLog->runtime_message_id);

                    // add subscriber's email to blacklist
                    $subscriber = $bounceLog->findSubscriberByRuntimeMessageId();
                    if ($subscriber) {
                        $subscriber->sendToBlacklist($bounceLog->raw);
                        MailLog::info('Email added to blacklist');
                    } else {
                        MailLog::warning('Cannot find associated tracking log for SendGrid message '.$bounceLog->runtime_message_id);
                    }
                    break;
                case 'spamreport':
                    $feedbackLog = new FeedbackLog();

                    // runtime-message_id is set by SendGrid SMTP API
                    // in case of SendGrid Web API, use sg_message_id instead
                    if (array_key_exists("runtime_message_id", $message)) {
                        $feedbackLog->runtime_message_id = $message["runtime_message_id"];
                    }

                    // retrieve the associated tracking log in Acelle
                    $trackingLog = TrackingLog::where('runtime_message_id', $feedbackLog->runtime_message_id)->first();
                    if ($trackingLog) {
                        $feedbackLog->message_id = $trackingLog->message_id;
                    }

                    // SendGrid only notifies in case of SPAM reported
                    $feedbackLog->feedback_type = 'spam';
                    $feedbackLog->raw_feedback_content = json_encode($message);
                    $feedbackLog->save();
                    MailLog::info('Feedback recorded for message '.$feedbackLog->runtime_message_id);

                    // update the mail list, subscriber to be marked as 'spam-reported'
                    $subscriber = $feedbackLog->findSubscriberByRuntimeMessageId();
                    if ($subscriber) {
                        $subscriber->markAsSpamReported();
                        MailLog::info('Subscriber marked as spam-reported');
                    } else {
                        MailLog::warning('Cannot find associated tracking log for SendGrid message '.$feedbackLog->runtime_message_id);
                    }
                    break;
                default:
                    // nothing
            }
        }

        header('X-PHP-Response-Code: 200', true, 200);
    }

    private function handleAws()
    {
        MailLog::configure(storage_path().'/logs/handler-aws.log');

        try {
            MailLog::info('Validating message...');
            // Create a message from the post data and validate its signature
            $message = Message::fromRawPostData();
            $validator = new MessageValidator();
            $validator->validate($message);
            MailLog::info('Message validated!');
        } catch (\Exception $e) {
            // Pretend we're not here if the message is invalid
            MailLog::warning($e->getMessage());
            return;
        }

        if ($message['Type'] === 'SubscriptionConfirmation') {
            MailLog::info('subscription received');
            // Send a request to the SubscribeURL to complete subscription
            (new Client(['verify' => false]))->get($message['SubscribeURL']);

            MailLog::info('subscription confirmed');

            return;
        }

        if ($message['Type'] != 'Notification') {
            MailLog::info('not notification');

            return;
        }

        $responseMessage = json_decode($message['Message'], true);

        if ($responseMessage['notificationType'] == 'AmazonSnsSubscriptionSucceeded') {
            MailLog::info('subscription confirmed by AWS');

            return;
        }

        /*
        sleep(5);
        $trackingLog = TrackingLog::where("message_id", $responseMessage['mail']['messageId'])->first() ;
        if (empty($trackingLog)) {
            MailLog::error('message_id not found');
            return;
        }
        */

        if ($responseMessage['notificationType'] == 'Bounce') {
            MailLog::info('Bounce reported');
            $bounce = $responseMessage['bounce'];

            $bounceLog = new BounceLog();
            $bounceLog->runtime_message_id = $responseMessage['mail']['messageId'];
            $bounceLog->bounce_type = $bounce['bounceType']; // !== 'Permanent' ? BounceLog::SOFT : BounceLog::HARD;
            $bounceLog->raw = $message['Message'];
            $trackingLog = TrackingLog::where('runtime_message_id', $bounceLog->runtime_message_id)->first();
            if (!is_null($trackingLog)) {
                $bounceLog->message_id = $trackingLog->message_id;
                $bounceLog->save();
                MailLog::info('Bounce recorded for message '.$bounceLog->runtime_message_id);

                if ($bounce['bounceType'] === 'Permanent') {
                    MailLog::info('Adding email to blacklist');
                    $bounceLog->findSubscriberByRuntimeMessageId()->sendToBlacklist($bounceLog->raw);
                }
            } else {
                MailLog::info('No tracking log found');
                $bounceLog->save();
                if ($bounce['bounceType'] === 'Permanent') {
                    $r = Blacklist::firstOrNew(['email' => extract_email($responseMessage['mail']['destination'][0])]);
                    $r->reason = $bounceLog->raw;
                    $r->save();
                    MailLog::info($responseMessage['mail']['destination'][0].' blacklisted');
                }
            }
        }

        if ($responseMessage['notificationType'] == 'Complaint') {
            MailLog::info('Complaint reported');
            $feedback = $responseMessage['complaint'];

            $feedbackLog = new FeedbackLog();
            $feedbackLog->runtime_message_id = $responseMessage['mail']['messageId'];
            try {
                $feedbackLog->feedback_type = $feedback['complaintFeedbackType'];
            } catch (\Exception $ex) {
                $feedbackLog->feedback_type = 'unknown';
            }

            $feedbackLog->raw_feedback_content = $message['Message'];
            $feedbackLog->save();
            MailLog::info('Feedback recorded for message '.$feedbackLog->runtime_message_id);
            try {
                MailLog::info('Adding email to abuse list');
                $feedbackLog->findSubscriberByRuntimeMessageId()->markAsSpamReported();
            } catch (\Exception $e) {
                MailLog::warning('Cannot mark subscriber as Abuse-Reported. ' . $e->getMessage());
            }
        }
    }

    /**
     * Receive bounce/feedback report via API.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        // Retrieve input
        $params = $request->all();

        // Validation rules
        $rules = [
            'event' => 'required|in:bounce,feedback',
            'message_id' => 'required',
        ];

        // Custom error messages
        $msg = [
            'event.in' => 'Invalid event. Possible values are: bounce, feedback',
            'bounce_type.in' => 'Invalid bounce type. Possible values are: hard, soft',
            'feedback_type.in' => 'Invalid feedback type. Possible values are: spam, abuse',
        ];

        // Execute validation
        $validation = Validator::make($params, $rules, $msg);

        // Rules for bounce
        $validation->sometimes('bounce_type', 'required|in:hard,soft', function ($input) {
            return $input->event == 'bounce';
        });

        // Rules for feedback
        $validation->sometimes('feedback_type', 'required|in:spam,abuse', function ($input) {
            return $input->event == 'feedback';
        });

        // Return 400 if validation fails
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()->toArray()], 400);
        }

        // Proceed with blacklisting
        if ($params['event'] == 'feedback') {
            $feedbackLog = new FeedbackLog();
            $feedbackLog->runtime_message_id = StringHelper::cleanupMessageId($params['message_id']);
            // For Mailgun, runtime_message_id EQUIV. message_id
            $feedbackLog->message_id = $feedbackLog->runtime_message_id;
            $feedbackLog->feedback_type = $params['feedback_type'];
            $feedbackLog->raw_feedback_content = (array_key_exists('response', $params)) ? $params['response'] : '';
            $feedbackLog->save();
            MailLog::info('Feedback recorded (from API) for message '.$feedbackLog->runtime_message_id);

            $subscriber = $feedbackLog->findSubscriberByRuntimeMessageId();
            if (!is_null($subscriber)) {
                MailLog::info('Adding email to abuse list');
                $feedbackLog->findSubscriberByRuntimeMessageId()->markAsSpamReported();
            } else {
                MailLog::warning('Subscriber not found');
            }
        } elseif ($params['event'] == 'bounce') {
            $bounceLog = new BounceLog();
            $bounceLog->runtime_message_id = StringHelper::cleanupMessageId($params['message_id']);
            // For Mailgun, runtime_message_id EQUIV. message_id
            $bounceLog->message_id = $bounceLog->runtime_message_id;
            $bounceLog->bounce_type = $params['bounce_type'];
            $bounceLog->raw = (array_key_exists('response', $params)) ? $params['response'] : '';
            $bounceLog->save();
            MailLog::info('Bounce recorded for message '.$bounceLog->runtime_message_id);
            $subscriber = $bounceLog->findSubscriberByRuntimeMessageId();
            if (!is_null($subscriber)) {
                MailLog::info('Adding email to blacklist');
                $bounceLog->findSubscriberByRuntimeMessageId()->sendToBlacklist($bounceLog->raw);
            } else {
                MailLog::warning('Subscriber not found');
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
