<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Acelle\Model\MailList;
use Acelle\Model\EmailVerificationServer;
use Acelle\Events\MailListSubscription;
use Acelle\Model\Setting;
use Acelle\Model\Customer;
use Exception;

class MailListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        return view('lists.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $customer = $request->user()->customer;

        $lists = $customer->mailLists()
            ->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        $settings = [
            'list.clone_for_others' => Setting::isYes('list.clone_for_others')
        ];
        return view('lists._list', [
            'lists' => $lists,
            'settings' => $settings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Generate info
        $customer = $request->user()->customer;
        $list = new \Acelle\Model\MailList(['all_sending_servers' => true]);
        $list->contact = new \Acelle\Model\Contact();

        if (is_object($customer->contact)) {
            $list->contact->fill($customer->contact->toArray());
            $list->send_to = $customer->contact->email;
        } else {
            $list->send_to = $customer->user->email;
        }

        // default values
        $list->subscribe_confirmation = false;
        $list->send_welcome_email = false;
        $list->unsubscribe_notification = false;

        // authorize
        if (\Gate::denies('create', $list)) {
            return $this->noMoreItem();
        }

        // Get old post values
        if (null !== $request->old()) {
            $list->fill($request->old());
        }
        if (isset($request->old()['contact'])) {
            $list->contact->fill($request->old()['contact']);
        }

        // Sending servers
        if (isset($request->old()['sending_servers'])) {
            $list->mailListsSendingServers = collect([]);
            foreach ($request->old()['sending_servers'] as $key => $param) {
                if ($param['check']) {
                    $server = \Acelle\Model\SendingServer::findByUid($key);
                    $row = new \Acelle\Model\MailListsSendingServer();
                    $row->mail_list_id = $list->id;
                    $row->sending_server_id = $server->id;
                    $row->fitness = $param['fitness'];
                    $list->mailListsSendingServers->push($row);
                }
            }
        }

        $allowedSingleOptin = Setting::isYes('list.allow_single_optin');

        return view('lists.create', [
            'list' => $list,
            'allowedSingleOptin' => $allowedSingleOptin
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
        // Generate info
        $customer = $request->user()->customer;
        $list = new \Acelle\Model\MailList();

        // authorize
        if (\Gate::denies('create', $list)) {
            return $this->noMoreItem();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\MailList::$rules);

            $rules = [];
            if (isset($request->sending_servers)) {
                foreach ($request->sending_servers as $key => $param) {
                    if ($param['check']) {
                        $rules['sending_servers.'.$key.'.fitness'] = 'required';
                    }
                }
            }
            $this->validate($request, $rules);

            // Save contact
            $contact = \Acelle\Model\Contact::create($request->all()['contact']);
            $list->fill($request->all());
            $list->customer_id = $customer->id;
            $list->contact_id = $contact->id;
            $list->save();

            // For sending servers
            if (isset($request->sending_servers)) {
                $list->updateSendingServers($request->sending_servers);
            }

            // Trigger updating related campaigns cache
            event(new \Acelle\Events\MailListUpdated($list));

            // Log
            $list->log('created', $request->user()->customer);

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.list.created'));

            return redirect()->action('MailListController@index');
        }
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
    public function edit(Request $request, $uid)
    {
        // Generate info
        $customer = $request->user()->customer;
        $list = \Acelle\Model\MailList::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        // Get old post values
        if (null !== $request->old()) {
            $list->fill($request->old());
        }
        if (isset($request->old()['contact'])) {
            $list->contact->fill($request->old()['contact']);
        }

        // Sending servers
        if (isset($request->old()['sending_servers'])) {
            $list->mailListsSendingServers = collect([]);
            foreach ($request->old()['sending_servers'] as $key => $param) {
                if ($param['check']) {
                    $server = \Acelle\Model\SendingServer::findByUid($key);
                    $row = new \Acelle\Model\MailListsSendingServer();
                    $row->mail_list_id = $list->id;
                    $row->sending_server_id = $server->id;
                    $row->fitness = $param['fitness'];
                    $list->mailListsSendingServers->push($row);
                }
            }
        }

        $allowedSingleOptin = Setting::isYes('list.allow_single_optin');

        return view('lists.edit', [
            'list' => $list,
            'allowedSingleOptin' => $allowedSingleOptin
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
        // Generate info
        $customer = $request->user()->customer;
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, \Acelle\Model\MailList::$rules);

            $rules = [];
            if (isset($request->sending_servers)) {
                foreach ($request->sending_servers as $key => $param) {
                    if ($param['check']) {
                        $rules['sending_servers.'.$key.'.fitness'] = 'required';
                    }
                }
            }
            $this->validate($request, $rules);

            // Save contact
            $list->contact->fill($request->all()['contact']);
            $list->contact->save();
            $list->fill($request->all());
            $list->save();

            // For sending servers
            if (isset($request->sending_servers)) {
                $list->updateSendingServers($request->sending_servers);
            }

            // Log
            $list->log('updated', $request->user()->customer);

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.list.updated'));

            return redirect()->action('MailListController@edit', $list->uid);
        }
    }

    /**
     * Custom sort items.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort = json_decode($request->sort);
        foreach ($sort as $row) {
            $list = \Acelle\Model\MailList::findByUid($row[0]);

            // authorize
            if (\Gate::denies('update', $list)) {
                return $this->notAuthorized();
            }

            $list->save();
        }

        echo trans('messages.lists._deleted_');
    }

    /**
     * Delete confirm message.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteConfirm(Request $request)
    {
        $lists = \Acelle\Model\MailList::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        return view('lists.delete_confirm', [
            'lists' => $lists,
        ]);
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
        if (isSiteDemo()) {
            return response()->json(["message" => trans('messages.operation_not_allowed_in_demo')], 404);
        }

        $lists = \Acelle\Model\MailList::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($lists->get() as $item) {
            // authorize
            if (\Gate::allows('delete', $item)) {
                $item->delete();

                // not needed as the related campaigns will be deleted as well
                // $item->updateCachedInfo();

                // Log
                $item->log('deleted', $request->user()->customer);

                // update MailList cache
                event(new \Acelle\Events\MailListUpdated($item));
            }
        }

        // Redirect to my lists page
        echo trans('messages.lists.deleted');
    }

    /**
     * List overview.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        event(new \Acelle\Events\MailListUpdated($list));

        // authorize
        if (\Gate::denies('read', $list)) {
            return $this->notAuthorized();
        }

        return view('lists.overview', [
            'list' => $list,
        ]);
    }

    /**
     * List growth chart content.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function listGrowthChart(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        if (is_object($list)) {
            $list_id = $list->id;
        } else {
            $list_id = null;
            $list = new \Acelle\Model\MailList();
            $list->customer_id = $request->user()->customer->id;
        }

        // authorize
        if (\Gate::denies('read', $list)) {
            return $this->notAuthorized();
        }

        $result = [
            'columns' => [],
            'data' => [],
            'bar_names' => [trans('messages.subscriber_growth')],
        ];

        // columns
        for ($i = 4; $i >= 0; --$i) {
            $result['columns'][] = \Carbon\Carbon::now()->subMonthsNoOverflow($i)->format('m/Y');
        }

        // datas
        foreach ($result['bar_names'] as $bar) {
            for ($i = 4; $i >= 0; --$i) {
                $result['data'][] = \Acelle\Model\Customer::subscribersCountByTime(
                    \Carbon\Carbon::now()->subMonthsNoOverflow($i)->startOfMonth(),
                    \Carbon\Carbon::now()->subMonthsNoOverflow($i)->endOfMonth(),
                    $request->user()->customer->id,
                    $list_id
                );
            }
        }

        return response()->json($result);
    }

    /**
     * Chart statistics chart.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function statisticsChart(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);
        $customer = $request->user()->customer;

        if (is_object($list)) {
            $list_id = $list->id;
        } else {
            $list_id = null;
            $list = new \Acelle\Model\MailList();
            $list->customer_id = $request->user()->customer->id;
        }

        // authorize
        if (\Gate::denies('read', $list)) {
            return $this->notAuthorized();
        }

        $result = [
            'data' => [],
        ];

        if (isset($list->id)) {
            if ($list->readCache('SubscribeCount', 0)) {
                $result['data'][] = ['value' => $list->readCache('SubscribeCount', 0), 'name' => trans('messages.subscribed')];
            }

            if ($list->readCache('UnsubscribeCount', 0)) {
                $result['data'][] = ['value' => $list->readCache('UnsubscribeCount', 0), 'name' => trans('messages.unsubscribed')];
            }

            if ($list->readCache('UnconfirmedCount', 0)) {
                $result['data'][] = ['value' => $list->readCache('UnconfirmedCount', 0), 'name' => trans('messages.unconfirmed')];
            }

            if ($list->readCache('BlacklistedCount', 0)) {
                $result['data'][] = ['value' => $list->readCache('BlacklistedCount', 0), 'name' => trans('messages.blacklisted')];
            }

            if ($list->readCache('SpamReportedCount', 0)) {
                $result['data'][] = ['value' => $list->readCache('SpamReportedCount', 0), 'name' => trans('messages.spam_reported')];
            }
        } else {
            if ($customer->readCache('SubscribedCount', 0)) {
                $result['data'][] = ['value' => $customer->readCache('SubscribeCount', 0), 'name' => trans('messages.subscribed')];
            }

            if ($customer->readCache('UnsubscribedCount', 0)) {
                $result['data'][] = ['value' => $customer->readCache('UnsubscribeCount', 0), 'name' => trans('messages.unsubscribed')];
            }

            if ($customer->readCache('UnconfirmedCount', 0)) {
                $result['data'][] = ['value' => $customer->readCache('UnconfirmedCount', 0), 'name' => trans('messages.unconfirmed')];
            }

            if ($customer->readCache('BlacklistedCount', 0)) {
                $result['data'][] = ['value' => $customer->readCache('BlacklistedCount', 0), 'name' => trans('messages.blacklisted')];
            }

            if ($customer->readCache('SpamReportedCount', 0)) {
                $result['data'][] = ['value' => $customer->readCache('SpamReportedCount', 0), 'name' => trans('messages.spam_reported')];
            }
        }

        // // datas
        // $result['data'][] = [
        //     'name' => trans('messages.statistics'),
        //     'type' => 'pie',
        //     'radius' => '70%',
        //     'center' => ['50%', '57.5%'],
        //     'data' => $datas
        // ];

        // $result['pie'] = 1;
        return response()->json($result);
    }

    /**
     * Quick view.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function quickView(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        if (!is_object($list)) {
            $list = new \Acelle\Model\MailList();
            $list->uid = '000';
            $list->customer_id = $request->user()->customer->id;
        }

        // authorize
        if (\Gate::denies('read', $list)) {
            return $this->notAuthorized();
        }

        return view('lists._quick_view', [
            'list' => $list,
        ]);
    }

    /**
     * Copy list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->copy_list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('lists.copy', [
                    'list' => $list,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $list->copy($request->name);
            return trans('messages.list.copied');
        }

        return view('lists.copy', [
            'list' => $list,
        ]);
    }

    /**
     * Embedded Forms.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function embeddedForm(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $list)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $list->setEmbeddedFormOptions($request->options);
        }

        return view('lists.embedded_form', [
            'list' => $list,
        ]);
    }

    /**
     * Embedded Forms.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function embeddedFormFrame(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $list)) {
            return $this->notAuthorized();
        }

        return view('lists.embedded_form_frame', [
            'list' => $list,
        ]);
    }

    /**
     * reCaptcha check.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function embeddedFormCaptcha(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        // Check if list does exist
        if (!is_object($list)) {
            return view('somethingWentWrong', ['message' => trans('messages.embedded_form.list_not_exsit')]);
        }

        $request->session()->put('form_url', \URL::previous());

        return view('lists.embedded_form_captcha', [
            'list' => $list,
        ]);
    }

    /**
     * Subscribe user from embedded Forms.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function embeddedFormSubscribe(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        if (\Acelle\Model\Setting::get('embedded_form_recaptcha') == 'yes') {
            if ($request->hasCaptcha) {
                // @hCaptcha
                if (\Acelle\Model\Setting::getCaptchaProvider() == 'hcaptcha') {
                    $hcaptcha = \Acelle\Hcaptcha\Client::initialize();
                    $success = $hcaptcha->check($request);
                } else {
                    $success = \Acelle\Library\Tool::checkReCaptcha($request);
                }
            } else {
                return view('lists.embedded_form_captcha', [
                    'list' => $list,
                ]);
            }
        } else {
            $success = true;
        }

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        // Check if list does exist
        if (!is_object($list)) {
            return view('somethingWentWrong', ['message' => trans('messages.embedded_form.list_not_exsit')]);
        }

        if (!$success) {
            $url = $request->session()->pull('form_url');
            $errs = [trans("messages.invalid_captcha")];
            return view('lists.embedded_form_captcha_invalid', [
                'errors' => $errs,
                'list' => $list,
                'back_link' => $url,
            ]);
        }

        try {
            // Create subscriber
            list($validator, $subscriber) = $list->subscribe($request, MailList::SOURCE_EMBEDDED_FORM);
        } catch (\Exception $ex) {
            return view('somethingWentWrong', ['message' => $ex->getMessage()]);
        }


        if ($validator->fails()) {
            $url = $request->session()->pull('form_url');
            // $validator->errors()
            $errs = [];
            foreach ($validator->errors()->toArray() as $key => $error) {
                $field = $list->getFieldByTag($key);
                $errs[] = trans('messages.list.field_not_valid', [
                    'field' => $field->label,
                ]);
            }

            if (strpos($url, '?') !== false) {
                $url = $url . "&" . implode('&', $errs);
            } else {
                $url = $url . "?" . implode('&', $errs);
            }

            // return redirect()->away($url);
            return view('lists.embedded_form_errors', [
                'errors' => $errs,
                'list' => $list,
                'back_link' => $url,
            ]);
        }

        // custom redirect
        if ($request->redirect_url) {
            return redirect()->away($request->redirect_url);
        } elseif ($list->subscribe_confirmation && !$subscriber->isSubscribed()) {
            // tell subscriber to check email for confirmation
            return redirect()->action('PageController@signUpThankyouPage', ['list_uid' => $list->uid, 'subscriber_uid' => $subscriber->uid]);
        } else {
            // All done, confirmed
            return redirect()->action(
                'PageController@signUpConfirmationThankyou',
                [
                    'list_uid' => $list->uid,
                    'uid' => $subscriber->uid,
                    'code' => 'empty',
                ]
            );
        }
    }

    /**
     * Mail list emails verification main page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function verification(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);
        $currentJob = $list->verificationJobs()->first();

        if ($currentJob) {
            return view('lists.email_verification', [
                'list' => $list,
                'currentJobUid' => $currentJob->uid,
                'cancelUrl' => action('MailListController@stopVerification', [ 'uid' => $list->uid, 'job_uid' => $currentJob->uid ]),
                'progressCheckUrl' => action('MailListController@verificationProgress', [ 'uid' => $list->uid, 'job_uid' => $currentJob->uid ]),
            ]);
        } else {
            return view('lists.email_verification', [
                'list' => $list,
            ]);
        }
    }

    /**
     * Start the verification process
     *
     */
    public function startVerification(Request $request)
    {
        // Get list & server
        $list = MailList::findByUid($request->uid);
        $server = EmailVerificationServer::findByUid($request->email_verification_server_id);

        // Dispatch
        $job = $list->dispatchVerificationJob($server);

        // Return
        return response()->json([
            'currentJobUid' => $job->uid,
            'cancelUrl' => action('MailListController@stopVerification', [ 'uid' => $list->uid, 'job_uid' => $job->uid ]),
            'progressCheckUrl' => action('MailListController@verificationProgress', [ 'uid' => $list->uid, 'job_uid' => $job->uid ]),
        ]);
    }

    /**
     * Stop the verification process
     *
     */
    public function stopVerification(Request $request)
    {
        $list = MailList::findByUid($request->uid);
        $job = $list->verificationJobs()->where('uid', $request->job_uid)->first();

        if (is_null($job)) {
            throw new Exception(sprintf('Verification job #%s does not exist', $request->job_uid));
        }

        $job->cancel();
        return response()->json();
    }

    /**
     * Reset the verification data
     *
     */
    public function resetVerification(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);
        $list->resetVerification();
        return redirect()->action('MailListController@verification', $list->uid);
    }

    /**
     * Check verification progress
     *
     */
    public function verificationProgress(Request $request)
    {
        $list = MailList::findByUid($request->uid);
        $job = $list->verificationJobs()->where('uid', $request->job_uid)->first();

        if (is_null($job)) {
            throw new Exception(sprintf('Verification job #%s does not exist', $request->job_uid));
        }

        $progress = $list->getProgress($job);

        // Get progress updated by the import process and status of the final job monitor
        return response()->json($progress);
    }

    /**
     * Check email
     *
     */
    public function checkEmail(Request $request)
    {
        header("Access-Control-Allow-Origin: *");

        $list = \Acelle\Model\MailList::findByUid($request->uid);
        $subscriber = $list->subscribers()->where('email', '=', strtolower(trim($request->EMAIL)))->first();

        if (is_object($subscriber) && $subscriber->status == \Acelle\Model\Subscriber::STATUS_SUBSCRIBED) {
            $result = trans('messages.email_already_subscribed');
        } else {
            $result = true;
        }

        return response()->json($result);
    }

    /**
     * Clone list for other users
     *
     */
    public function cloneForCustomersChoose(Request $request, $uid)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);
        $customers = Customer::where('id', '!=', $list->customer_id);

        $options = [];
        foreach ($customers->get() as $customer) {
            $options[] = ['text' => $customer->user->displayName(), 'value' => $customer->uid];
        }

        return view('lists.cloneForCustomersChoose', [
            'list' => $list,
            'options' => $options,
        ]);
    }

    /**
     * Clone list for other users
     *
     */
    public function cloneForCustomers(Request $request, $uid)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);
        $customers = Customer::WhereIn('uid', $request->customers);

        $list->cloneForCustomers($customers->get());

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.list.clone_for.success'),
        ]);
    }

    public function selectList(Request $request)
    {
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'list_uid' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('lists.selectList', [
                    'errors' => $validator->errors(),
                ], 400);
            }

            $url = str_replace('list_uid', $request->list_uid, $request->redirect);
            return response()->json([
                'url' => $url,
            ]);
        }

        return view('lists.selectList');
    }

    public function emailVerificationChart(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $list)) {
            return $this->notAuthorized();
        }

        $result = [
            'data' => [
                [
                    'name' => trans('messages.email_verification_result_deliverable'),
                    'value' => $list->subscribers()->deliverable()->count(),
                ],
                [
                    'name' => trans('messages.email_verification_result_undeliverable'),
                    'value' => $list->subscribers()->undeliverable()->count(),
                ],
                [
                    'name' => trans('messages.email_verification_result_risky'),
                    'value' =>  $list->subscribers()->risky()->count(),
                ],
                [
                    'name' => trans('messages.email_verification_result_unverified'),
                    'value' => $list->subscribers()->unverified()->count(),
                ],
                [
                    'name' => trans('messages.email_verification_result_unknown'),
                    'value' => $list->subscribers()->unknown()->count(),
                ],
            ],
        ];

        // usort($result['data'], function ($a, $b) {
        //     return strcmp($a['value'], $b['value']);
        // });
        // $result['data'] = array_reverse($result['data']);

        return response()->json($result);
    }
}
