<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Currency())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Currency())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $currencies = \Acelle\Model\Currency::search($request);

        return view('admin.currencies.index', [
            'currencies' => $currencies,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Currency())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Currency())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $currencies = \Acelle\Model\Currency::search($request)->paginate($request->per_page);

        return view('admin.currencies._list', [
            'currencies' => $currencies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $currency = new \Acelle\Model\Currency();
        $currency->status = 'active';

        // authorize
        if (\Gate::denies('create', $currency)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $currency->fill($request->old());
        }

        return view('admin.currencies.create', [
            'currency' => $currency,
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
        $currency = new \Acelle\Model\Currency();

        // authorize
        if (\Gate::denies('create', $currency)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $rules = $currency->rules();

            $this->validate($request, $rules);

            // Save current currency info
            $currency->admin_id = $user->admin->id;
            $currency->fill($request->all());
            $currency->status = 'active';

            if ($currency->save()) {
                $request->session()->flash('alert-success', trans('messages.currency.created'));
                return redirect()->action('Admin\CurrencyController@index');
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
        $currency = \Acelle\Model\Currency::findByUid($id);

        // authorize
        if (\Gate::denies('update', $currency)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $currency->fill($request->old());
        }

        return view('admin.currencies.edit', [
            'currency' => $currency,
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
        $currency = \Acelle\Model\Currency::findByUid($id);

        // Prenvent save from demo mod
        if ($this->isDemoMode()) {
            return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
        }

        // authorize
        if (\Gate::denies('update', $currency)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $rules = $currency->rules();

            $this->validate($request, $rules);

            // Save currency
            $currency->fill($request->all());

            if ($currency->save()) {
                $request->session()->flash('alert-success', trans('messages.currency.updated'));
                return redirect()->action('Admin\CurrencyController@index');
            }
        }
    }

    /**
     * Enable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \Acelle\Model\Currency::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->enable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.currencies.enabled');
    }

    /**
     * Disable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \Acelle\Model\Currency::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.currencies.disabled');
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
        $items = \Acelle\Model\Currency::whereIn(
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
        echo trans('messages.currencies.deleted');
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
        echo \Acelle\Model\Currency::select2($request);
    }
}
