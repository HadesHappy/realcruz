<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/plans - API controller for managing plans.
 */
class PlanController extends Controller
{
    /**
     * Display all plans.
     *
     * GET /api/v1/plans
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::guard('api')->user();

        // authorize
        if (!$user->can('read', new \Acelle\Model\Plan())) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }

        $rows = \Acelle\Model\Plan::limit(100)->get();

        $plans = $rows->map(function ($plan) {
            return [
                'uid' => $plan->uid,
                'name' => $plan->name,
                'price' => $plan->price,
                'currency_code' => $plan->currency->code,
                'frequency_amount' => $plan->frequency_amount,
                'frequency_unit' => $plan->frequency_unit,
                'options' => $plan->getOptions(),
                'status' => $plan->status,
                'quota' => $plan->quota,
                'created_at' => $plan->created_at,
                'updated_at' => $plan->updated_at,
            ];
        });

        return \Response::json($plans, 200);
    }

    /**
     * Create a new plan.
     *
     * POST /api/v1/plans
     *
     * @param \Illuminate\Http\Request $request All plan information
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::guard('api')->user();

        $plan = new \Acelle\Model\Plan();

        // authorize
        if (!$user->can('create', $plan)) {
            return \Response::json(array('status' => 0, 'message' => 'Unauthorized'), 401);
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan->fill($request->all());
            // $plan->options = json_encode($request->options);
            $plan->fillOptions($request->options);

            $validator = \Validator::make($request->all(), $plan->apiRules());
            if ($validator->fails()) {
                return response()->json($validator->messages(), 403);
            }

            $rules = [];
            if (isset($request->sending_servers)) {
                foreach ($request->sending_servers as $key => $param) {
                    if ($param['check']) {
                        $rules['sending_servers.'.$key.'.fitness'] = 'required';
                    }
                }
            }

            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->messages(), 403);
            }

            $plan->admin_id = $user->admin->id;
            $plan->status = \Acelle\Model\Plan::STATUS_INACTIVE;
            $plan->save();

            // check status
            $plan->checkStatus();

            // For sending servers
            if (isset($request->sending_servers)) {
                $plan->updateSendingServers($request->sending_servers);
            }

            // For email verification servers
            if (isset($request->email_verification_servers)) {
                $plan->updateEmailVerificationServers($request->email_verification_servers);
            }

            return \Response::json(array(
                'status' => 1,
                'message' => trans('messages.plan.created'),
                'plan_uid' => $plan->uid
            ), 200);
        }
    }
}
