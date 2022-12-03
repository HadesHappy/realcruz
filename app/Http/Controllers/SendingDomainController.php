<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\SendingDomain;
use Acelle\Model\SendingServer;
use Acelle\Model\Setting;
use Acelle\Library\Facades\Hook;
use Gate;

class SendingDomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->customer->can('read', new SendingDomain())) {
            return $this->notAuthorized();
        }

        $request->merge(array("customer_id" => $request->user()->customer->id));
        $plan = $request->user()->customer->activeSubscription()->plan;

        if ($plan->useSystemSendingServer()) {
            $server = $plan->primarySendingServer();
        } else {
            $server = null;
        }

        $items = SendingDomain::search($request, $server);

        return view('sending_domains.index', [
            'items' => $items,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $customer = $request->user()->customer;

        if (!$customer->can('read', new SendingDomain())) {
            return $this->notAuthorized();
        }

        $domains = $customer->sendingDomains();

        $plan = $customer->activeSubscription()->plan;
        if ($plan->useSystemSendingServer()) {
            $server = $plan->primarySendingServer();
            if (!$server->allowOtherSendingDomains()) {
                // Do not filter, just flag as incompatible
                // $domains->bySendingServer($server);
            }
        }

        $items = $domains->search($request->keyword)->orderBy($request->sort_order, $request->sort_direction)->paginate(25);

        return view('sending_domains._list', [
            'items' => $items,
            'server' => isset($server) ? $server : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $server = new SendingDomain([
            'signing_enabled' => true,
        ]);
        $server->status = 'active';
        $server->uid = '0';
        $server->fill($request->old());

        // authorize
        if (!$request->user()->customer->can('create', $server)) {
            return $this->notAuthorized();
        }

        return view('sending_domains.create', [
            'server' => $server,
            'readonly' => '0',
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
        // authorize
        if (Gate::denies('create', SendingDomain::class)) {
            return $this->notAuthorized();
        }

        // Get customer
        $customer = $request->user()->customer;

        // Initiate the domain instance
        $domain = $customer->sendingDomains()->make();

        // Determine if app-based domain or server-based domain
        $plan = $customer->activeSubscription()->plan;
        if ($plan->useSystemSendingServer()) {
            $server = $plan->primarySendingServer();
        } else {
            $server = null;
        }

        if ($server && $server->allowVerifyingOwnDomainsRemotely()) {
            list($validator, $domain) = $domain->createFromArray($request->all(), $server); // server based domain
        } else {
            list($validator, $domain) = $domain->createFromArray($request->all(), null);    // app based domain
        }

        if (is_null($domain)) { // fail to create
            return redirect()->action('SendingDomainController@create')->withErrors($validator);
        }

        // Log
        $domain->log('created', $customer);

        $request->session()->flash('alert-success', trans('messages.sending_domain.created'));
        return redirect()->action('SendingDomainController@show', $domain->uid);
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
        $domain = SendingDomain::findByUid($id);

        return view('sending_domains.show', [
            'server' => $domain,
            'readonly' => 'readonly',
        ]);
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
        $server = SendingDomain::findByUid($id);

        // authorize
        if (!$request->user()->customer->can('update', $server)) {
            return $this->notAuthorized();
        }

        $server->fill($request->old());

        return view('sending_domains.edit', [
            'server' => $server,
            'readonly' => 'readonly',
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
        // Get current user
        $current_user = $request->user();
        $server = SendingDomain::findByUid($id);

        // authorize
        if (!$request->user()->customer->can('update', $server)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, SendingDomain::rules());

            // Save current user info
            $server->fill($request->all());

            if ($server->save()) {
                // Log
                $server->log('updated', $request->user()->customer);

                $request->session()->flash('alert-success', trans('messages.sending_domain.updated'));
                return redirect()->action('SendingDomainController@show', $server->uid);
            }
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
        echo trans('messages.sending_domain._deleted_');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items = SendingDomain::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->customer->can('delete', $item)) {
                // Log
                $item->log('deleted', $request->user()->customer);

                $item->delete();
            }
        }

        // Redirect to my lists page
        echo trans('messages.sending_domains.deleted');
    }

    /**
     * Verify sending domain.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function verify($id)
    {
        $domain = SendingDomain::findByUid($id);
        $domain->verify();
    }

    /**
     * sending domain's records.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function records($id)
    {
        $domain = SendingDomain::findByUid($id);

        $tokens = json_decode($domain->verification_token, true);
        $identity = $tokens['identity'];
        $dkims = $tokens['dkim'];
        $spf = array_key_exists('spf', $tokens) ? $tokens['spf'] : null;
        $results = $tokens['results'];

        Hook::execute(
            'filter_aws_ses_dns_records',
            [&$identity, &$dkims, &$spf]
        );
        return view('sending_domains._records_aws', [
            'domain' => $domain,
            'identity' => $identity,
            'dkims' => $dkims,
            'spf' => $spf,
            'results' => $results,
        ]);
    }

    /**
     * update VerificationTxtName.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDkimSelector($id, Request $request)
    {
        $server = SendingDomain::findByUid($id);

        // authorize
        if (!$request->user()->customer->can('update', $server)) {
            return $this->notAuthorized();
        }

        if (!$server->setDkimSelector($request->value)) {
            return response(trans('messages.sending_domain.dkim_selector.not_valid'), 404)
                ->header('Content-Type', 'text/plain');
        }
    }
}
