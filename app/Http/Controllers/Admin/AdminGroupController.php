<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class AdminGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\AdminGroup())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\AdminGroup())) {
            $request->merge(array("creator_id" => $request->user()->id));
        }

        $groups = \Acelle\Model\AdminGroup::search($request);

        return view('admin.admin_groups.index', [
            'groups' => $groups,
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
        if (\Gate::denies('read', new \Acelle\Model\AdminGroup())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\AdminGroup())) {
            $request->merge(array("creator_id" => $request->user()->id));
        }

        $groups = \Acelle\Model\AdminGroup::search($request)->paginate($request->per_page);

        return view('admin.admin_groups._list', [
            'groups' => $groups,
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
        $user = $request->user();

        $group = new \Acelle\Model\AdminGroup([
                                'backend_access' => false,
                                'frontend_access' => true,
                            ]);
        $group->fill($request->old());

        // authorize
        if (\Gate::denies('create', $group)) {
            return $this->notAuthorized();
        }

        // For permissions
        if (isset($request->old()['permissions'])) {
            $group->permissions = json_encode($request->old()['permissions']);
        }
        $permissions = $group->getPermissions();

        return view('admin.admin_groups.create', [
            'group' => $group,
            'permissions' => $permissions,
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
        $user = $request->user();
        $group = new \Acelle\Model\AdminGroup(array());

        // authorize
        if (\Gate::denies('create', $group)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\AdminGroup::rules());

            $group->fill($request->all());
            $group->permissions = json_encode($request->permissions);
            $group->creator_id = $user->id;
            $group->save();

            $request->session()->flash('alert-success', trans('messages.admin_group.created'));

            return redirect()->action('Admin\AdminGroupController@index');
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
        // Generate info
        $user = $request->user();
        $group = \Acelle\Model\AdminGroup::find($id);
        $group->fill($request->old());

        // authorize
        if (\Gate::denies('update', $group)) {
            return $this->notAuthorized();
        }

        // For permissions
        if (isset($request->old()['permissions'])) {
            $group->permissions = json_encode($request->old()['permissions']);
        }
        $permissions = $group->getPermissions();

        return view('admin.admin_groups.edit', [
            'group' => $group,
            'permissions' => $permissions,
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
        $user = $request->user();

        $group = \Acelle\Model\AdminGroup::find($id);

        // authorize
        if (\Gate::denies('update', $group)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, \Acelle\Model\AdminGroup::rules());

            $group->fill($request->all());
            $group->permissions = json_encode($request->permissions);
            $group->save();

            $request->session()->flash('alert-success', trans('messages.admin_group.updated'));

            return redirect()->action('Admin\AdminGroupController@edit', $group->id);
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
        echo trans('messages._deleted_');
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
        $lists = \Acelle\Model\AdminGroup::whereIn('id', explode(',', $request->ids));

        foreach ($lists->get() as $item) {
            // authorize
            if (\Gate::denies('delete', $item)) {
                return;
            }
        }

        foreach ($lists->get() as $item) {
            $item->delete();
        }

        // Redirect to my lists page
        echo trans('messages.admin_groups.deleted');
    }
}
