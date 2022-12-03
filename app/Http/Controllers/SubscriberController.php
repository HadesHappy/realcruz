<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Library\Log as MailLog;
use Acelle\Model\EmailVerificationServer;
use Acelle\Model\MailList;
use Acelle\Model\Segment;
use Acelle\Model\Subscriber;
use Acelle\Model\JobMonitor;
use Acelle\Library\Facades\Hook;

class SubscriberController extends Controller
{
    /**
     * Search items.
     */
    public function search($list, $request)
    {
        $customer = $request->user()->customer;
        $subscribers = $customer->subscribers()
            ->search($request->keyword)
            ->filter($request)
            ->orderBy($request->sort_order ? $request->sort_order : 'created_at', $request->sort_direction ? $request->sort_direction : 'asc')
            ->where('mail_list_id', '=', $list->id);

        return $subscribers;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);

        return view('subscribers.index', [
            'list' => $list
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('read', $list)) {
            return;
        }

        $subscribers = $this->search($list, $request);
        // $total = distinctCount($subscribers);
        $total = $subscribers->count();
        $subscribers->with(['mailList', 'subscriberFields']);
        $subscribers = $subscribers->paginate($request->per_page);

        $fields = $list->getFields->whereIn('uid', $request->columns);

        return view('subscribers._list', [
            'subscribers' => $subscribers,
            'total' => $total,
            'list' => $list,
            'fields' => $fields,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);
        $subscriber = new \Acelle\Model\Subscriber();
        $subscriber->mail_list_id = $list->id;

        // authorize
        if (\Gate::denies('create', $subscriber)) {
            return $this->noMoreItem();
        }

        // Get old post values
        $values = [];
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        return view('subscribers.create', [
            'list' => $list,
            'subscriber' => $subscriber,
            'values' => $values,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);
        $customer = $request->user()->customer;

        if (!$customer->user->can('addMoreSubscribers', [ $list, $more = 1 ])) {
            return $this->noMoreItem();
        }

        // Validate & and create subscriber
        // Throw ValidationError exception in case of failure
        list($validator, $subscriber) = $list->subscribe($request, Subscriber::SUBSCRIPTION_TYPE_ADDED);

        // @IMPORTANT: do not use $validator->fails() again,
        // if validation runs again, it is now TRUE! subscriber's email inserted => no longer unique
        if (is_null($subscriber)) {
            return back()->withInput()->withErrors($validator);
        }

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.subscriber.created'));
        return redirect()->action('SubscriberController@index', $list->uid);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // Get old post values
        $values = [];
        foreach ($list->getFields as $key => $field) {
            $values[$field->tag] = $subscriber->getValueByField($field);
        }
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        return view('subscribers.edit', [
            'list' => $list,
            'subscriber' => $subscriber,
            'values' => $values,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = $request->user()->customer;
        $list = MailList::findByUid($request->list_uid);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, $subscriber->getRules());

            // Upload
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $subscriber->uploadImage($request->file('image'));
                }
            }
            // Remove image
            if ($request->_remove_image == 'true') {
                $subscriber->removeImage();
            }

            // Update field
            $subscriber->updateFields($request->all());

            event(new \Acelle\Events\MailListUpdated($subscriber->mailList));

            // Log
            $subscriber->log('updated', $customer);

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.subscriber.updated'));

            return redirect()->action('SubscriberController@index', $list->uid);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $customer = $request->user()->customer;
        $uids = $request->uids;

        if (!is_array($request->uids)) {
            $uids = explode(',', $request->uids);
        }
        $subscribers = \Acelle\Model\Subscriber::whereIn('uid', $uids);
        $list = MailList::findByUid($request->list_uid);

        // Select all items
        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        }

        // get related mail lists to update the cached information
        $lists = $subscribers->get()->map(function ($e) {
            return MailList::find($e->mail_list_id);
        })->unique();

        // actually delete the subscriber
        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('delete', $subscriber)) {
                $subscriber->delete();

                // Log
                $subscriber->log('deleted', $customer);
            }
        }

        foreach ($lists as $list) {
            event(new \Acelle\Events\MailListUpdated($list));
        }

        // Redirect to my lists page
        return response()->json([
            "status" => 'success',
            "message" => trans('messages.subscribers.deleted'),
        ]);
    }

    /**
     * Subscribe subscriber.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);
        $customer = $request->user()->customer;

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        } else {
            $subscribers = \Acelle\Model\Subscriber::whereIn(
                'uid',
                is_array($request->uids) ? $request->uids : explode(',', $request->uids)
            );
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('subscribe', $subscriber)) {
                $subscriber->subscribe([
                    'message_id' => null,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                ]);

                // update MailList cache
                event(new \Acelle\Events\MailListUpdated($subscriber->mailList));

                // Log
                $subscriber->log('subscribed', $customer);
            }
        }

        // Redirect to my lists page
        echo trans('messages.subscribers.subscribed');
    }

    /**
     * Unsubscribe subscriber.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);
        $customer = $request->user()->customer;

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        } else {
            $subscribers = \Acelle\Model\Subscriber::whereIn(
                'uid',
                is_array($request->uids) ? $request->uids : explode(',', $request->uids)
            );
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('unsubscribe', $subscriber)) {
                $subscriber->unsubscribe([
                    'message_id' => null,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                ]);

                // Log
                $subscriber->log('unsubscribed', $customer);

                // update MailList cache
                event(new \Acelle\Events\MailListUpdated($subscriber->mailList));
            }
        }

        // Redirect to my lists page
        echo trans('messages.subscribers.unsubscribed');
    }

    /**
     * Import from file.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $customer = $request->user()->customer;
        $list = MailList::findByUid($request->list_uid);
        $currentJob = $list->importJobs()->first();

        // authorize
        if (\Gate::denies('import', $list)) {
            return $this->notAuthorized();
        }

        $importNotifications = Hook::execute('list_import_notifications');

        // GET, has a current job
        if ($currentJob) {
            return view('subscribers.import', [
                'list' => $list,
                'currentJobUid' => $currentJob->uid,
                'progressCheckUrl' => action('SubscriberController@importProgress', ['job_uid' => $currentJob->uid, 'list_uid' => $list->uid]),
                'cancelUrl' => action('SubscriberController@cancelImport', ['job_uid' => $currentJob->uid]),
                'logDownloadUrl' => action('SubscriberController@downloadImportLog', ['job_uid' => $currentJob->uid]),
                'importNotifications' => $importNotifications,
            ]);
        // GET, do not have any job
        } else {
            return view('subscribers.import', [
                'list' => $list,
                'importNotifications' => $importNotifications,
            ]);
        }
    }

    public function dispatchImportJob(Request $request)
    {
        // Get the list
        $list = MailList::findByUid($request->list_uid);

        // Upload to server
        // Example of outcome: /home/acelle/storage/app/tmp/import-000000.csv
        $filepath = $list->uploadCsv($request->file('file'));

        // Use the default way if there is no other plugin
        Hook::registerIfEmpty('dispatch_list_import_job', function ($list, $filepath) {
            return $list->dispatchImportJob($filepath);
        });

        // Dispatch the import job
        $currentJob = Hook::perform('dispatch_list_import_job', [$list, $filepath]);

        // Return the job information
        return response()->json([
            'currentJobUid' => $currentJob->uid,
            'progressCheckUrl' => action('SubscriberController@importProgress', ['job_uid' => $currentJob->uid, 'list_uid' => $list->uid]),
            'cancelUrl' => action('SubscriberController@cancelImport', ['job_uid' => $currentJob->uid]),
            'logDownloadUrl' => action('SubscriberController@downloadImportLog', ['job_uid' => $currentJob->uid]),
        ]);
    }

    /**
     * Import from file.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelImport(Request $request)
    {
        $job = JobMonitor::findByUid($request->job_uid);

        try {
            $job->cancel();
            return response()->json(['status' => 'done']);
        } catch (\Exception $ex) {
            $job->delete(); // delete anyway if already done or failed, to make it simple to user
            return response()->json(['status' => '']);
        }
    }

    /**
     * Import from file.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelExport(Request $request)
    {
        $job = JobMonitor::findByUid($request->job_uid);

        try {
            $job->cancel();
            return response()->json(['status' => 'done']);
        } catch (\Exception $ex) {
            $job->delete(); // delete anyway if already done or failed, to make it simple to user
            return response()->json(['status' => '']);
        }
    }

    public function downloadImportLog(Request $request)
    {
        $job = JobMonitor::findByUid($request->job_uid);

        // Only available if job has moved out of queued status
        return response()->download($job->getJsonData()['logfile']);
    }

    public function downloadExportedFile(Request $request)
    {
        $job = JobMonitor::findByUid($request->job_uid);

        // Only available if job has moved out of queued status
        return response()->download($job->getJsonData()['filepath']);
    }

    /**
     * Check import proccessing.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function importProgress(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);
        $job = $list->importJobs()->first();

        $progress = $list->getProgress($job);

        // Get progress updated by the import process and status of the final job monitor
        return response()->json($progress);
    }

    /**
     * Export to csv.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $list = MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('export', $list)) {
            return $this->notAuthorized();
        }

        $currentJob = $list->exportJobs()->first();

        // GET, has a current job
        if ($currentJob) {
            return view('subscribers.export', [
                'list' => $list,
                'currentJobUid' => $currentJob->uid,
                'progressCheckUrl' => action('SubscriberController@exportProgress', ['job_uid' => $currentJob->uid]),
                'cancelUrl' => action('SubscriberController@cancelExport', ['job_uid' => $currentJob->uid]),
                'downloadUrl' => action('SubscriberController@downloadExportedFile', ['job_uid' => $currentJob->uid]),
            ]);
        // GET, do not have any job
        } else {
            return view('subscribers.export', [
                'list' => $list
            ]);
        }
    }

    public function dispatchExportJob(Request $request)
    {
        // Get the list
        $list = MailList::findByUid($request->list_uid);

        // Get segment if any
        $segmentUid = $request->input('segment_uid');
        $segment = (is_null($segmentUid)) ? null : Segment::findByUid($segmentUid);

        // Dispatch import job
        $currentJob = $list->dispatchExportJob($segment);

        // Return the job information
        return response()->json([
            'currentJobUid' => $currentJob->uid,
            'progressCheckUrl' => action('SubscriberController@exportProgress', ['job_uid' => $currentJob->uid]),
            'cancelUrl' => action('SubscriberController@cancelExport', ['job_uid' => $currentJob->uid]),
            'downloadUrl' => action('SubscriberController@downloadExportedFile', ['job_uid' => $currentJob->uid]),
        ]);
    }

    /**
     * Check export proccessing.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function exportProgress(Request $request)
    {
        $job = JobMonitor::findByUid($request->job_uid);

        // Get progress updated by the import process and status of the final job monitor
        $progress = $job->getJsonData();
        $progress['status'] = $job->status;
        $progress['error'] = $job->error;

        return response()->json($progress);
    }

    /**
     * Copy subscribers to lists.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        $from_list = MailList::findByUid($request->from_uid);
        $to_list = MailList::findByUid($request->to_uid);

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($from_list, $request)->select('subscribers.*');
        } else {
            $subscribers = \Acelle\Model\Subscriber::whereIn(
                'uid',
                is_array($request->uids) ? $request->uids : explode(',', $request->uids)
            );
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('update', $to_list)) {
                $subscriber->copy($to_list);
            }
        }

        // Trigger updating related campaigns cache
        event(new \Acelle\Events\MailListUpdated($to_list));

        // Log
        $to_list->log('copied', $request->user()->customer, [
            'count' => $subscribers->count(),
            'from_uid' => $from_list->uid,
            'to_uid' => $to_list->uid,
            'from_name' => $from_list->name,
            'to_name' => $to_list->name,
        ]);

        // Redirect to my lists page
        echo trans('messages.subscribers.copied');
    }

    /**
     * Move subscribers to lists.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request)
    {
        $from_list = MailList::findByUid($request->from_uid);
        $to_list = MailList::findByUid($request->to_uid);

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($from_list, $request)->select('subscribers.*');
        } else {
            $subscribers = \Acelle\Model\Subscriber::whereIn(
                'uid',
                is_array($request->uids) ? $request->uids : explode(',', $request->uids)
            );
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('update', $to_list)) {
                $subscriber->move($to_list);
            }
        }

        // Trigger updating related campaigns cache
        event(new \Acelle\Events\MailListUpdated($from_list));
        event(new \Acelle\Events\MailListUpdated($to_list));

        // Log
        $to_list->log('moved', $request->user()->customer, [
            'count' => $subscribers->count(),
            'from_uid' => $from_list->uid,
            'to_uid' => $to_list->uid,
            'from_name' => $from_list->name,
            'to_name' => $to_list->name,
        ]);

        // Redirect to my lists page
        echo trans('messages.subscribers.moved');
    }

    /**
     * Copy Move subscribers form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copyMoveForm(Request $request)
    {
        $from_list = MailList::findByUid($request->from_uid);

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($from_list, $request);
        } else {
            $subscribers = \Acelle\Model\Subscriber::whereIn(
                'uid',
                is_array($request->uids) ? $request->uids : explode(',', $request->uids)
            );
        }

        return view('subscribers.copy_move_form', [
            'subscribers' => $subscribers,
            'from_list' => $from_list
        ]);
    }

    /**
     * Start the verification process
     *
     */
    public function startVerification(Request $request)
    {
        $subscriber = Subscriber::findByUid($request->uid);
        $server = EmailVerificationServer::findByUid($request->email_verification_server_id);
        try {
            $subscriber->verify($server);

            // success message
            $request->session()->flash('alert-success', trans('messages.verification.finish'));

            // update MailList cache
            event(new \Acelle\Events\MailListUpdated($subscriber->mailList));

            return redirect()->action('SubscriberController@edit', ['list_uid' => $request->list_uid, 'uid' => $subscriber->uid]);
        } catch (\Exception $e) {
            MailLog::error(sprintf("Something went wrong while verifying %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage()));
            return view('somethingWentWrong', ['message' => sprintf("Something went wrong while verifying %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage())]);
        }
    }

    /**
     * Reset the verification data
     *
     */
    public function resetVerification(Request $request)
    {
        $subscriber = Subscriber::findByUid($request->uid);

        try {
            MailLog::info(sprintf("Cleaning up verification data for %s (%s)", $subscriber->email, $subscriber->id));
            $subscriber->resetVerification();
            // success message
            $request->session()->flash('alert-success', trans('messages.verification.reset'));

            MailLog::info(sprintf("Finish cleaning up verification data for %s (%s)", $subscriber->email, $subscriber->id));
            return redirect()->action('SubscriberController@edit', ['list_uid' => $request->list_uid, 'uid' => $subscriber->uid]);
        } catch (\Exception $e) {
            MailLog::error(sprintf("Something went wrong while cleaning up verification data for %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage()));
            return view('somethingWentWrong', ['message' => sprintf("Something went wrong while cleaning up verification data for %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage())]);
        }
    }

    /**
     * Render customer image.
     */
    public function avatar(Request $request)
    {
        // Get current customer
        if ($request->uid != '0') {
            $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);
        } else {
            $subscriber = new \Acelle\Model\Subscriber();
        }
        if (!empty($subscriber->imagePath())) {
            $img = \Image::make($subscriber->imagePath());
        } else {
            $img = \Image::make(public_path('images/subscriber-icon.jpg'));
        }

        return $img->response();
    }

    /**
     * Resend confirmation email.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resendConfirmationEmail(Request $request)
    {
        $subscribers = \Acelle\Model\Subscriber::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );
        $list = MailList::findByUid($request->list_uid);

        // Select all items
        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        }

        // Launch re-sending job
        dispatch_now(new \Acelle\Jobs\SendConfirmationEmailJob($subscribers->get(), $list));

        // Redirect to my lists page
        echo trans('messages.subscribers.resend_confirmation_email.being_sent');
    }

    /**
     * Update tags.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTags(Request $request, $list_uid, $uid)
    {
        $list = MailList::findByUid($list_uid);
        $subscriber = Subscriber::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // saving
        if ($request->isMethod('post')) {
            $subscriber->updateTags($request->tags);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.subscriber.tagged', [
                    'subscriber' => $subscriber->getFullName(),
                ]),
            ], 201);
        }

        return view('subscribers.updateTags', [
            'list' => $list,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Automation remove contact tag.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function removeTag(Request $request, $list_uid, $uid)
    {
        $list = MailList::findByUid($list_uid);
        $subscriber = Subscriber::findByUid($uid);

        // authorize
        if (\Gate::denies('delete', $subscriber)) {
            return $this->notAuthorized();
        }

        $subscriber->removeTag($request->tag);

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.automation.contact.tag.removed', [
                'tag' => $request->tag,
            ]),
        ], 201);
    }

    /**
     * Bulk remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        // init
        $list = MailList::findByUid($request->list_uid);

        // validate and save posted data
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), ['emails' => 'required']);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('subscribers.bulkDelete', [
                    'list' => $list,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // get all emails
            $emails = array_unique(preg_split("/[\s,\r\n]+/", $request->emails));
            $subscribers = $list->subscribers()->whereIn('email', $emails)->get();

            //
            return view('subscribers.bulkDeleteConfirm', [
                'list' => $list,
                'emails' => $emails,
                'subscribers' => $subscribers,
            ]);
        }

        return view('subscribers.bulkDelete', [
            'list' => $list,
        ]);
    }

    /**
     * Bulk remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDeleteConfirm(Request $request)
    {
        // init
        $list = MailList::findByUid($request->list_uid);

        // validate and save posted data
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), ['emails' => 'required']);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('subscribers.bulkDelete', [
                    'list' => $list,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // get all emails
            $emails = preg_split("/[\s,\r\n]+/", $request->emails);

            //
            return view('subscribers.bulkDeleteConfirm', [
                'list' => $list,
                'emails' => $emails,
            ]);
        }

        return view('subscribers.bulkDelete', [
            'list' => $list,
        ]);
    }

    /**
     * Bulk assign values to subscribers.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function assignValues(Request $request, $list_uid)
    {
        // init
        $list = MailList::findByUid($request->list_uid);
        $subscribers = \Acelle\Model\Subscriber::whereIn('uid', $request->uids);

        // Select all items
        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $validator = \Acelle\Model\Subscriber::assginValues($subscribers, $request);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('subscribers.assignValues', [
                    'list' => $list,
                    'subscribers' => $subscribers,
                    'errors' => $validator->errors(),
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.subscribers.values_assigned'),
            ]);
        }

        return view('subscribers.assignValues', [
            'list' => $list,
            'subscribers' => $subscribers,
        ]);
    }
}
