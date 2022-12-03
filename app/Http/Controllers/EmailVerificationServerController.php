<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class EmailVerificationServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->customer->can('read', new \Acelle\Model\EmailVerificationServer())) {
            return $this->notAuthorized();
        }

        $request->merge(array("customer_id" => $request->user()->customer->id));
        $servers = \Acelle\Model\EmailVerificationServer::search($request);

        return view('email_verification_servers.index', [
            'servers' => $servers,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (!$request->user()->customer->can('read', new \Acelle\Model\EmailVerificationServer())) {
            return $this->notAuthorized();
        }

        $request->merge(array("customer_id" => $request->user()->customer->id));
        $servers = \Acelle\Model\EmailVerificationServer::search($request)->paginate($request->per_page);

        return view('email_verification_servers._list', [
            'servers' => $servers,
        ]);
    }

    /**
     * Select sending server type.
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        return view('email_verification_servers.select');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $server = new \Acelle\Model\EmailVerificationServer();
        $server->status = \Acelle\Model\EmailVerificationServer::STATUS_ACTIVE;
        $server->uid = '0';
        $server->fill($request->old());

        // authorize
        if (!$request->user()->customer->can('create', $server)) {
            return $this->notAuthorized();
        }

        $server->fill($request->old());

        $options = [];
        if (!empty($request->old()['options'])) {
            $options = $request->old()['options'];
        }

        return view('email_verification_servers.create', [
            'server' => $server,
            'options' => $options,
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
        $current_user = $request->user();
        $server = new \Acelle\Model\EmailVerificationServer();

        // authorize
        if (!$request->user()->customer->can('create', $server)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $server->fill($request->all());

            $this->validate($request, $server->rules());

            // Save current user info
            $server->customer_id = $request->user()->customer->id;
            $server->status = \Acelle\Model\EmailVerificationServer::STATUS_ACTIVE;
            $server->options = json_encode($request->options);

            if ($server->save()) {
                // Log
                $server->log('created', $request->user()->customer);

                $request->session()->flash('alert-success', trans('messages.email_verification_server.created'));
                return redirect()->action('EmailVerificationServerController@index');
            }
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
        $server = \Acelle\Model\EmailVerificationServer::findByUid($id);

        // authorize
        if (!$request->user()->customer->can('update', $server)) {
            return $this->notAuthorized();
        }

        $server->fill($request->old());

        $options = $server->getOptions();
        if (!empty($request->old()['options'])) {
            $options = $request->old()['options'];
        }

        return view('email_verification_servers.edit', [
            'server' => $server,
            'options' => $options,
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
        $server = \Acelle\Model\EmailVerificationServer::findByUid($id);

        // authorize
        if (!$request->user()->customer->can('update', $server)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $server->fill($request->all());

            $this->validate($request, $server->rules());

            // Save current user info
            $server->options = json_encode($request->options);

            if ($server->save()) {
                // Log
                $server->log('updated', $request->user()->customer);

                $request->session()->flash('alert-success', trans('messages.email_verification_server.updated'));
                return redirect()->action('EmailVerificationServerController@index');
            }
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
        $items = \Acelle\Model\EmailVerificationServer::whereIn(
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
        echo trans('messages.email_verification_servers.deleted');
    }

    /**
     * Disable sending server.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \Acelle\Model\EmailVerificationServer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->customer->can('disable', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.email_verification_servers.disabled');
    }

    /**
     * Disable sending server.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \Acelle\Model\EmailVerificationServer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->customer->can('enable', $item)) {
                $item->enable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.email_verification_servers.enabled');
    }

    /**
     * Email verification server display options form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request, $uid=null)
    {
        if ($uid) {
            $server = \Acelle\Model\EmailVerificationServer::findByUid($uid);
        } else {
            $server = new \Acelle\Model\EmailVerificationServer($request->all());
            $options = $server->getOptions();
        }

        return view('email_verification_servers._options', [
            'server' => $server,
            'options' => $options,
        ]);
    }
}
