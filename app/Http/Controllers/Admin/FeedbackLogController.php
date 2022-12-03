<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class FeedbackLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->admin->getPermission('report_feedback_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\FeedbackLog::getAll();

        return view('admin.feedback_logs.index', [
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
        if ($request->user()->admin->getPermission('report_feedback_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\FeedbackLog::search($request)->paginate($request->per_page);

        return view('admin.feedback_logs._list', [
            'items' => $items,
        ]);
    }
}
