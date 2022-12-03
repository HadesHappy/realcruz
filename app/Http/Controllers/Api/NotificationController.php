<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\BounceLog;
use Response;
use Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications.
     *
     * GET /api/v1/plans
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        return Response::json(['message' => 'Comming...'], 200);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $params = $request->all();

        list($log, $validator) = BounceLog::createFromRequest($request->all());

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        } else {
            return Response::json($log, 200);
        }
    }
}
