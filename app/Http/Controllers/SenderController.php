<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Sender;
use Acelle\Model\SendingDomain;

class SenderController extends Controller
{
    /**
     * Search items.
     */
    public function search($request)
    {
        $request->merge(array("customer_id" => $request->user()->customer->id));
        $senders = Sender::search($request);

        return $senders;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (\Gate::denies('listing', new Sender())) {
            return $this->notAuthorized();
        }

        $subscription = $request->user()->customer->subscription;
        $plan = $subscription->plan;

        if ($subscription->plan->useOwnSendingServer()) {
            $email = true;
            $domain = true;
        } else {
            $server = $plan->primarySendingServer();
            $email = $server->allowVerifyingOwnEmails() || $server->allowVerifyingOwnEmailsRemotely();
            $domain = $server->allowVerifyingOwnDomains() || $server->allowVerifyingOwnDomainsRemotely();
        }

        if (!$email && !$domain) {
            return view('senders.available', [
                'identities' => $subscription->plan->getVerifiedIdentities(),
            ]);
        }

        if ($domain && !$email) {
            return redirect(url('sending_domains'));
        } else {
            return view('senders.index', [
                'senders' => $this->search($request),
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (\Gate::denies('listing', new Sender())) {
            return $this->notAuthorized();
        }

        return view('senders._list', [
            'senders' => $this->search($request)->paginate($request->per_page),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $sender = new Sender();

        $sender->fill($request->old());

        // authorize
        if (\Gate::denies('create', $sender)) {
            return $this->notAuthorized();
        }

        return view('senders.create', [
            'sender' => $sender,
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
        $sender = new Sender();

        // authorize
        if (\Gate::denies('create', $sender)) {
            return $this->notAuthorized();
        }

        $plan = $request->user()->customer->activeSubscription()->plan;

        $sender->fill($request->all());
        $sender->customer_id = $request->user()->customer->id;
        $sender->status = Sender::STATUS_PENDING;

        $this->validate($request, $sender->rules());

        $sender->save();

        if ($plan->useSystemSendingServer()) {
            $server = $plan->primarySendingServer();
        } else {
            $server = null;
        }

        $sender->verifyWith($server);
        return redirect()->action('SenderController@show', $sender->uid);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $sender = Sender::findByUid($id);
        $sender->updateVerificationStatus();

        // authorize
        if (\Gate::denies('read', $sender)) {
            return $this->notAuthorized();
        }

        return view('senders.show', [
            'sender' => $sender,
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
        $sender = Sender::findByUid($id);

        // authorize
        if (\Gate::denies('update', $sender)) {
            return $this->notAuthorized();
        }

        $sender->fill($request->old());

        return view('senders.edit', [
            'sender' => $sender,
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
        $sender = Sender::findByUid($id);

        // authorize
        if (\Gate::denies('update', $sender)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $sender->name = $request->name;

            $this->validate($request, $sender->editRules());

            if ($sender->save()) {
                $request->session()->flash('alert-success', trans('messages.sender.updated'));
                return redirect()->action('SenderController@show', $sender->uid);
            }
        }
    }

    /**
     * Verify sender.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        try {
            $sender = Sender::verifyToken($request->token);
            return view('senders.verified', [
                'sender' => $sender,
            ]);
        } catch (\Exception $ex) {
            return view('senders.failed', [
                'message' => $ex->getMessage(),
            ]);
        }
    }

    /**
     * Verify sender.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyResult(Request $request)
    {
        $sender = Sender::findByUid($request->uid);
        $sender->updateVerificationStatus();

        if ($sender->isVerified()) {
            return view('senders.verified', [
                'sender' => $sender,
            ]);
        } else {
            return view('senders.failed', [
                'message' => 'Failed to verify identity',
            ]);
        }
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
        if ($request->select_tool == 'all_items') {
            $senders = $this->search($request);
        } else {
            $senders = Sender::whereIn(
                'uid',
                is_array($request->uids) ? $request->uids : explode(',', $request->uids)
            );
        }

        foreach ($senders->get() as $sender) {
            // authorize
            if ($request->user()->customer->can('delete', $sender)) {
                $sender->delete();
            }
        }

        // Redirect to my lists page
        echo trans('messages.senders.deleted');
    }

    /**
     * Start import process.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $customer = $request->user()->customer;

        if ($request->isMethod('post')) {
            // authorize
            if (\Gate::denies('import', new Sender())) {
                return $this->notAuthorized();
            }

            if ($request->hasFile('file')) {
                // Start system job
                $job = new \Acelle\Jobs\ImportSenderJob($request->file('file')->path(), $request->user()->customer);
                $this->dispatch($job);
            } else {
                // @note: use try/catch instead
                echo "max_file_upload";
            }
        }


        return view('senders.import', [

        ]);
    }

    /**
     * Dropbox list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function dropbox(Request $request)
    {
        $droplist = $request->user()->customer->verifiedIdentitiesDroplist(strtolower(trim($request->keyword)));
        return response()->json($droplist);
    }
}
