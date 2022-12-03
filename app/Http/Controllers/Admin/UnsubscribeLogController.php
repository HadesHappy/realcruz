<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class UnsubscribeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->admin->getPermission('report_unsubscribe_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\UnsubscribeLog::getAll();

        return view('admin.unsubscribe_logs.index', [
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
        if ($request->user()->admin->getPermission('report_unsubscribe_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\UnsubscribeLog::search($request)->paginate($request->per_page);

        return view('admin.unsubscribe_logs._list', [
            'items' => $items,
        ]);
    }
}
