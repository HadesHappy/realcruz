<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class LayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->admin->getPermission('layout_read') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\Layout::getAll();

        return view('admin.layouts.index', [
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
        if ($request->user()->admin->getPermission('layout_read') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\Layout::search($request)->paginate($request->per_page);

        return view('admin.layouts._list', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $layout = \Acelle\Model\Layout::findByUid($id);

        // authorize
        if (\Gate::denies('update', $layout)) {
            return $this->notAuthorized();
        }

        // Get old post values
        if (null !== $request->old()) {
            $layout->fill($request->old());
        }

        return view('admin.layouts.edit', [
            'layout' => $layout,
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
        $layout = \Acelle\Model\Layout::findByUid($id);

        // Prenvent save from demo mod
        if ($this->isDemoMode()) {
            return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
        }

        // authorize
        if (\Gate::denies('update', $layout)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('patch')) {
            $rules = array(
                'content' => 'required',
                'subject' => 'required',
            );

            // $this->validate($request, $rules);

            // make validator
            $validator = \Validator::make($request->all(), $rules);

            // redirect if fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()[0],
                ], 400);
            }

            // check ses email tags
            if ($layout->alias == 'sender_verification_email_for_amazon_ses' && preg_match("/\<((meta)|(title)|(style))/i", $request->content)) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('messages.layout.amazon_ses.tag_not_permit'),
                ], 400);
            }

            // Save template
            $layout->fill($request->all());
            $layout->save();

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.layout.updated'));
            return response()->json([
                'status' => 'success',
                'url' => action('Admin\LayoutController@edit', $layout->uid),
            ]);
        }
    }
}
