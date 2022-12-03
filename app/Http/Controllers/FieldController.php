<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $fields = $list->getFields;

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        // Get old post values
        if (isset($request->old()['fields'])) {
            $fields = $list->getFieldsFromParams($request->old());
        }

        return view('fields.index', [
            'list' => $list,
            'fields' => $fields,
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
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $validator = $list->updateOrCreateFieldsFromRequest($request);

            if ($validator->fails()) {
                return redirect()->action('FieldController@index', $list->uid)
                ->withErrors($validator)
                ->withInput();
            }

            // Redirect to my lists page
            return redirect()->action('FieldController@index', $list->uid)
                ->with('alert-success', trans('messages.fields.updated'));
        }
    }

    /**
     * Get field sample.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sample(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        return view('fields._form_samples', [
            'list' => $list,
            'type' => $request->type,
        ]);
    }

    /**
     * Delete an item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $field = \Acelle\Model\Field::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $field->mailList)) {
            return $this->notAuthorized();
        }

        if ($field->tag != 'EMAIL') {
            $field->delete();

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.fields.deleted'));
            return redirect()->action('FieldController@index', $request->list_uid);
        } else {
            // Redirect to my lists page
            $request->session()->flash('alert-error', trans('messages.fields.can_not_delete_email_field'));
            return redirect()->action('FieldController@index', $request->list_uid);
        }
    }
}
