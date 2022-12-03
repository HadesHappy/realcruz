<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Plan;
use Acelle\Model\Setting;
use Acelle\Model\SendingServer;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new Plan())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new Plan())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $plans = \Acelle\Model\Plan::search($request->keyword)
            ->filter($request);

        return view('admin.plans.index', [
            'plans' => $plans,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $plans = \Acelle\Model\Plan::search($request->keyword)
            ->filter($request)
            ->orderBy($request->sort_order, $request->sort_direction ? $request->sort_direction : 'asc')
            ->paginate($request->per_page);

        return view('admin.plans._list', [
            'plans' => $plans,
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
        // Get current user
        $user = $request->user();
        $plan = new Plan();

        // authorize
        if (\Gate::denies('create', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan->fill($request->all());
            // $plan->options = json_encode($request->options);
            $plan->fillOptions($request->options);

            $this->validate($request, $plan->rules());

            $rules = [];
            if (isset($request->sending_servers)) {
                foreach ($request->sending_servers as $key => $param) {
                    if ($param['check']) {
                        $rules['sending_servers.'.$key.'.fitness'] = 'required';
                    }
                }
            }
            $this->validate($request, $rules);

            $plan->admin_id = $user->admin->id;
            $plan->save();

            // check status
            $plan->checkStatus();

            // For sending servers
            if (isset($request->sending_servers)) {
                $plan->updateSendingServers($request->sending_servers);
            }

            // For email verification servers
            if (isset($request->email_verification_servers)) {
                $plan->updateEmailVerificationServers($request->email_verification_servers);
            }

            $request->session()->flash('alert-success', trans('messages.plan.created'));
            return redirect()->action('Admin\PlanController@index');
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
    public function edit(Request $request, $id)
    {
        $plan = Plan::findByUid($id);
        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $plan->fill($request->old());
        }

        // For options
        if (isset($request->old()['options'])) {
            $plan->options = json_encode($request->old()['options']);
        }
        $options = $plan->getOptions();

        // Sending servers
        if (isset($request->old()['sending_servers'])) {
            $plan->plansSendingServers = collect([]);
            foreach ($request->old()['sending_servers'] as $key => $param) {
                if ($param['check']) {
                    $server = \Acelle\Model\SendingServer::findByUid($key);
                    $row = new \Acelle\Model\PlansSendingServer();
                    $row->plan_id = $plan->id;
                    $row->sending_server_id = $server->id;
                    $row->fitness = $param['fitness'];
                    $plan->plansSendingServers->push($row);
                }
            }
        }

        // Email verification servers
        if (isset($request->old()['email_verification_servers'])) {
            $plan->fillPlansEmailVerificationServers($request->old()['email_verification_servers']);
        }
        return view('admin.plans.edit', [
            'plan' => $plan,
            'options' => $options
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
    public function save(Request $request, $uid)
    {
        // Get current user
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        $validator = $plan->saveAll($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        // check plan status
        $plan->checkStatus();

        # redirect to sending servers page when needed
        if ($request->use_system_sending_server) {
            return redirect()->action('Admin\PlanController@sendingServers', $plan->uid);
        }

        return redirect()->back()->with('alert-success', trans('messages.plan.updated'));
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
        $items = Plan::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::denies('delete', $item)) {
                return;
            }
        }

        foreach ($items->get() as $item) {
            $item->delete();
        }

        // Redirect to my lists page
        echo trans('messages.plans.deleted');
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
        echo trans('messages._deleted_');
    }

    /**
     * Select2 plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        echo Plan::select2($request);
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
        $plans = Plan::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        return view('admin.plans.delete_confirm', [
            'plans' => $plans,
        ]);
    }

    /**
     * Chart pie chart.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function pieChart(Request $request)
    {
        $admin = $request->user()->admin;

        // authorize
        if (\Gate::denies('read', new Plan())) {
            return $this->notAuthorized();
        }

        $result = [
            'columns' => [],
            'data' => [],
        ];

        $datas = [];
        foreach (Plan::active()->get() as $plan) {
            $count = $admin->getAllSubscriptionsByPlan($plan)->count();
            // create data
            if ($count) {
                $result['bar_names'][] = $plan->name;
                $result['data'][] = ['value' => $count, 'name' => $plan->name];
            }
        }

        usort($result['data'], function ($a, $b) {
            return strcmp($a['value'], $b['value']);
        });
        $result['data'] = array_reverse($result['data']);

        return response()->json($result);
    }

    public function copy(Request $request)
    {
        $plan = Plan::findByUid($request->copy_plan_uid);

        // authorize
        if (\Gate::denies('copy', $plan)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.plans.copy', [
                    'plan' => $plan,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $plan->copy($request->name);
            return trans('messages.plan.copied');
        }

        return view('admin.plans.copy', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan sending server setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServer(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        if ($plan->getOption('sending_server_option') == Plan::SENDING_SERVER_OPTION_SYSTEM) {
            return redirect()->action('Admin\PlanController@sendingServers', $plan->uid);
        } elseif ($plan->getOption('sending_server_option') == Plan::SENDING_SERVER_OPTION_OWN) {
            return redirect()->action('Admin\PlanController@sendingServerOwn', $plan->uid);
        } elseif ($plan->getOption('sending_server_option') == Plan::SENDING_SERVER_OPTION_SUBACCOUNT) {
            return redirect()->action('Admin\PlanController@sendingServerSubaccount', $plan->uid);
        }
    }

    /**
     * Plan sending server subaccount setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServerSubaccount(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan->fillAll($request);
            $plan->save();

            $request->session()->flash('alert-success', trans('messages.plan.sending_server.saved'));
            return redirect()->action('Admin\PlanController@sendingServerSubaccount', $plan->uid);
        }

        return view('admin.plans.sending_server.subaccount', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan sending server own setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServerOwn(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan->fillAll($request);
            $plan->save();

            $request->session()->flash('alert-success', trans('messages.plan.sending_server.saved'));
            return redirect()->action('Admin\PlanController@sendingServerOwn', $plan->uid);
        }

        return view('admin.plans.sending_server.own', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan sending server setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function emailVerification(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // fill all
        $plan->fillAll($request);

        return view('admin.plans.email_verification', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan sending servers.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServers(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        return view('admin.plans.sending_server.system', [
            'plan' => $plan,
        ]);
    }

    /**
     * Add plan sending server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function addSendingServer(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $sendingServer = SendingServer::findByUid($request->sending_server_uid);
            $existIds = $plan->plansSendingServers()->pluck('sending_server_id')->toArray();

            // check if sending server is already added
            if (in_array($sendingServer->id, $existIds)) {
                $request->session()->flash('alert-error', trans('messages.plan.sending_server.already_added'));
                return redirect()->action('Admin\PlanController@sendingServers', $plan->uid);
            }

            $plan->addSendingServerByUid($sendingServer->uid);

            // check plan status
            $plan->checkStatus();

            $request->session()->flash('alert-success', trans('messages.plan.sending_server.added'));
            return redirect()->action('Admin\PlanController@sendingServers', $plan->uid);
        }

        return view('admin.plans.sending_servers_add', [
            'plan' => $plan,
            'noSendingServer' => true,
        ]);
    }

    /**
     * Remove plan sending server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function removeSendingServer(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        $plan->removeSendingServerByUid($request->sending_server_uid);

        // check plan status
        $plan->checkStatus();

        $request->session()->flash('alert-success', trans('messages.plan.sending_server.removed'));
        return redirect()->action('Admin\PlanController@sendingServers', $plan->uid);
    }

    /**
     * Set primary plan sending server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function setPrimarySendingServer(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        $plan->setPrimarySendingServer($request->sending_server_uid);

        // check plan status
        $plan->checkStatus();

        $request->session()->flash('alert-success', trans('messages.plan.sending_server.primary.updated'));
        return redirect()->action('Admin\PlanController@sendingServers', $plan->uid);
    }

    /**
     * Plan sending servers fitnesses.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function fitness(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // update fitness value
        if ($request->isMethod('post')) {
            $plan->updateFitnesses($request->sending_servers);

            // check plan status
            $plan->checkStatus();

            $request->session()->flash('alert-success', trans('messages.plan.sending_servers.fitness.updated'));
            return redirect()->action('Admin\PlanController@sendingServers', $plan->uid);
        }

        return view('admin.plans.fitness', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan general information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function general(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // fill all params
        $plan->fillAll($request);

        return view('admin.plans.general', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan quota information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function quota(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // fill all
        $plan->fillAll($request);

        return view('admin.plans.quota', [
            'plan' => $plan,
            'options' => $plan->getOptions(),
        ]);
    }

    /**
     * Plan security information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function security(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // fill all
        $plan->fillAll($request);

        return view('admin.plans.security', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan payment information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // fill all
        $plan->fillAll($request);

        return view('admin.plans.payment', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan email footer information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function emailFooter(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // fill all
        $plan->fillAll($request);

        return view('admin.plans.email_footer', [
            'plan' => $plan,
        ]);
    }

    public function tos(Request $request)
    {
        $plan = Plan::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $validator = $plan->updateTos($request->all());

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.plans.tos', [
                    'plan' => $plan,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $request->session()->flash('alert-success', trans('messages.plan.tos.updated'));
        }

        return view('admin.plans.tos', [
            'plan' => $plan,
        ]);
    }

    /**
     * Sending Limit Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingLimit(Request $request)
    {
        if (!$request->uid) {
            $plan = new Plan();
        } else {
            $plan = Plan::findByUid($request->uid);
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan->fillAll($request);
            return view('admin.plans._sending_limit', [
                'plan' => $plan,
            ]);
        }

        return view('admin.plans.sending_limit', [
            'plan' => $plan,
        ]);
    }

    /**
     * Billing cycle form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function billingCycle(Request $request)
    {
        if (!$request->uid || $request->uid == '00') {
            $plan = new Plan();
            $plan->uid = '00';
        } else {
            $plan = Plan::findByUid($request->uid);
        }

        // save posted data
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'plan.general.frequency_amount' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.plans.billing_cycle', [
                    'plan' => $plan,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $plan->fillAll($request);

            return view('admin.plans._billing_cycle', [
                'plan' => $plan,
            ]);
        }

        return view('admin.plans.billing_cycle', [
            'plan' => $plan,
        ]);
    }

    /**
     * Sending Limit Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function wizard(Request $request)
    {
        if (!$request->uid) {
            $plan = Plan::newDefaultPlan();
        } else {
            $plan = Plan::findByUid($request->uid);
        }

        // fill all
        $plan->fillAll($request);

        // save posted data
        if ($request->isMethod('post')) {
            $validator = $plan->validate($request);

            if ($validator->fails()) {
                return redirect()
                    ->action('Admin\PlanController@wizard')
                    ->withErrors($validator)
                    ->withInput();
            }

            $plan->saveAll($request);

            // check plan status
            $plan->checkStatus();

            return redirect()->action('Admin\PlanController@wizardSendingServer', $plan->uid);
        }

        return view('admin.plans.wizard', [
            'plan' => $plan,
        ]);
    }

    /**
     * Sending Limit Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function wizardSendingServer(Request $request)
    {
        $plan = Plan::findByUid($request->uid);
        // fill all
        $plan->fillAll($request);

        // save posted data
        if ($request->isMethod('post')) {
            $validator = $plan->validate($request);

            if ($validator->fails()) {
                return redirect()
                    ->action('Admin\PlanController@wizardSendingServer', $plan->uid)
                    ->withErrors($validator)
                    ->withInput();
            }

            $plan->saveAll($request);

            // check plan status
            $plan->checkStatus();

            return 'success';
        }

        return view('admin.plans.wizard_sending_server', [
            'plan' => $plan,
        ]);
    }

    /**
     * Plan sending server change option.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServerOption(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan->fillAll($request);
            $plan->save();

            // check plan status
            $plan->checkStatus();

            return redirect()->action('Admin\PlanController@sendingServer', $plan->uid);
        }

        return view('admin.plans.sending_server_option', [
            'plan' => $plan,
        ]);
    }

    /**
     * Show item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function visibleOn(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('visibleOn', $plan)) {
            //
            return response()->json([
                'status' => 'error',
                'message' => trans('messages.plan.show.denied'),
            ], 201);
        }

        //
        $plan->visibleOn();

        // check plan status
        $plan->checkStatus();

        // Redirect to my lists page
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.plan.showed'),
        ], 201);
    }

    /**
     * Show item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function visibleOff(Request $request, $uid)
    {
        $plan = Plan::findByUid($uid);

        // authorize
        if (\Gate::denies('visibleOff', $plan)) {
            //
            return response()->json([
                'status' => 'error',
                'message' => trans('messages.plan.hide.denied'),
            ], 201);
        }

        //
        $plan->visibleOff();

        // check plan status
        $plan->checkStatus();

        // Redirect to my lists page
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.plan.hidden'),
        ], 201);
    }
}
