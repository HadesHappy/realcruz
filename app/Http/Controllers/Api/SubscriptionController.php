<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/subscriptions - API controller for managing subscriptions.
 */
class SubscriptionController extends Controller
{
    /**
     * Display all subscriptions.
     *
     * GET /api/v1/campaigns
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::guard('api')->user();

        $subscriptions = \Acelle\Model\Subscription::select('*')
            ->paginate($request->per_page ? $request->per_page : 25);

        return \Response::json($subscriptions, 200);
    }

    /**
     * Subscribe customer to a plan (For admin only).
     *
     * POST /api/v1/subscriptions
     *
     * @param \Illuminate\Http\Request $request         All supscription information
     * @param string                   $customer_uid    Customer's uid
     * @param string                   $plan_uid        Plan's uid
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::guard('api')->user();
        $customer = \Acelle\Model\Customer::findByUid($request->customer_uid);
        $plan = \Acelle\Model\Plan::findByUid($request->plan_uid);

        // check if customer exists
        if (!is_object($customer)) {
            return \Response::json(array('status' => 0, 'message' => 'Customer not found'), 404);
        }

        // check if plan exists
        if (!is_object($plan)) {
            return \Response::json(array('status' => 0, 'message' => 'Plan not found'), 404);
        }

        // authorize
        if (!$user->can('assignPlan', $customer)) {
            return \Response::json(array('status' => 0, 'message' => 'Unauthorized'), 401);
        }

        // check if item active
        if (!$plan->isActive()) {
            return \Response::json(array('status' => 0, 'message' => 'Plan is not active'), 404);
        }

        $subscription = $customer->assignPlan($plan);

        // * Disable billing information: customer does not need to pay the invoice. Pass by the billing and checkout step
        // * assignPlan always create a subscription with an unpaid invoice.
        // * So just fulfill the invoice
        // * By design: when the subscription invoice is fulfilled, the callback function will be triggered
        //              to set subscription as active automatically.
        if ($request->disable_billing && $request->disable_billing !== 'false') {
            $subscription->getUnpaidInvoice()->fulfill();
        }

        return \Response::json(array(
            'status' => 1,
            'message' => 'Assigned '.$customer->user->displayName().' plan to '.$plan->name.' successfully.',
            'customer_uid' => $customer->uid,
            'plan_uid' => $plan->uid
        ), 200);
    }
}
