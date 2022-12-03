<?php

namespace Acelle\Http\Controllers\Api;

use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/campaigns - API controller for managing campaigns.
 */
class CampaignController extends Controller
{
    /**
     * Display all user's campaigns.
     *
     * GET /api/v1/campaigns
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::guard('api')->user();

        $lists = \Acelle\Model\Campaign::getAll()
            ->select('uid', 'name', 'type', 'subject', 'plain', 'from_email', 'from_name', 'reply_to', 'status', 'delivery_at', 'created_at', 'updated_at')
            ->where('customer_id', '=', $user->customer->id)
            ->get();

        return \Response::json($lists, 200);
    }

    /**
     * Display the specified campaign information.
     *
     * GET /api/v1/campaigns/{id}
     *
     * @param int $id Campaign's id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \Auth::guard('api')->user();

        $item = \Acelle\Model\Campaign::
            where('uid', '=', $id)
            ->first();

        // check if item exists
        if (!is_object($item)) {
            return \Response::json(array('message' => 'Campaign not found'), 404);
        }

        // authorize
        if (!$user->can('read', $item)) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }

        // statistics
        $campaign = [
            'uid' => $item->uid,
            'name' => $item->name,
            'list' => (is_object($item->mailList) ? $item->mailList->name : ''),
            'segment' => (is_object($item->segment) ? $item->segment->name : ''),
            'default_subject' => $item->default_subject,
            'from_email' => $item->from_email,
            'from_name' => $item->from_name,
            'remind_message' => $item->remind_message,
            'status' => $item->status,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];

        // statistics
        $statistics = [
            'subscriber_count' => $item->subscribersCount(),
            'uniq_open_rate' => $item->openRate(),
            'delivered_rate' => $item->deliveredRate(),
            'unique_open_count' => $item->uniqueOpenCount(),
            'open_rate' => $item->openRate(),
            'uniq_open_count' => $item->openUniqCount(),
            'last_open' => (is_object($item->lastOpen()) ? $item->lastOpen()->created_at : ''),
            'click_rate' => $item->clickRate(),
            'click_count' => $item->clickCount(),
            'abuse_feedback_count' => $item->abuseFeedbackCount(),
            'last_click' => (is_object($item->lastClick()) ? $item->lastClick()->created_at : ''),
            'click_count' => $item->clickCount(),
            'bounce_count' => $item->bounceCount(),
            'unsubscribe_count' => $item->unsubscribeCount(),
            'links' => $item->getTopLinks()->get()->pluck(['url']),
            'top_locations' => $item->topLocations()->get()->pluck('ip_address'),
            'top_open_subscribers' => $item->getTopOpenSubscribers()->get()->map(function ($i, $key) {
                return [
                    'uid' => $i->uid,
                    'email' => $i->email,
                ];
            }),
        ];

        return \Response::json(['campaign' => $campaign, 'statistics' => $statistics], 200);
    }

    /**
     * Pause campaign.
     *
     * GET /api/v1/campaigns/{id}
     *
     * @param int $id Campaign's id
     *
     * @return \Illuminate\Http\Response
     */
    public function pause($id)
    {
        $user = \Auth::guard('api')->user();

        $campaign = \Acelle\Model\Campaign::
            where('uid', '=', $id)
            ->first();

        // check if item exists
        if (!is_object($campaign)) {
            return \Response::json(array('message' => 'Campaign not found'), 404);
        }

        // authorize
        if (!$user->can('pause', $campaign)) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }

        $campaign->pause();

        // statistics
        $campaign = [
            'uid' => $campaign->uid,
            'name' => $campaign->name,
            'list' => (is_object($campaign->mailList) ? $campaign->mailList->name : ''),
            'segment' => (is_object($campaign->segment) ? $campaign->segment->name : ''),
            'default_subject' => $campaign->default_subject,
            'from_email' => $campaign->from_email,
            'from_name' => $campaign->from_name,
            'remind_message' => $campaign->remind_message,
            'status' => $campaign->status,
            'created_at' => $campaign->created_at,
            'updated_at' => $campaign->updated_at,
        ];

        return \Response::json([
            'status' => 'success',
            'message' => 'The campaign was paused',
            'campaign' => $campaign], 200);
    }
}
