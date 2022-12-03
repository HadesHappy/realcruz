<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Library\Log as MailLog;
use Illuminate\Support\Facades\Log as LaravelLog;
use Gate;
use Validator;
use Illuminate\Validation\ValidationException;
use Acelle\Library\StringHelper;
use Acelle\Jobs\ExportCampaignLog;
use Acelle\Model\Template;
use Acelle\Model\TrackingLog;
use Acelle\Model\Setting;
use Acelle\Model\Subscriber;
use Acelle\Model\Campaign;
use Acelle\Model\IpLocation;
use Acelle\Model\ClickLog;
use Acelle\Model\OpenLog;
use Acelle\Model\TemplateCategory;
use Acelle\Model\JobMonitor;
use DB;
use Exception;
use Carbon\Carbon;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = $request->user()->customer;
        $campaigns = $customer->campaigns();

        return view('campaigns.index', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $customer = $request->user()->customer;

        $campaigns = $customer->campaigns()
            ->search($request->keyword)
            ->filter($request)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        return view('campaigns._list', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = $request->user()->customer;
        $campaign = new Campaign([
            'track_open' => true,
            'track_click' => true,
            'sign_dkim' => true,
        ]);

        // authorize
        if (\Gate::denies('create', $campaign)) {
            return $this->noMoreItem();
        }

        $campaign->name = trans('messages.untitled');
        $campaign->customer_id = $customer->id;
        $campaign->status = Campaign::STATUS_NEW;
        $campaign->type = $request->type;
        $campaign->save();

        return redirect()->action('CampaignController@recipients', ['uid' => $campaign->uid]);
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
        $campaign = Campaign::findByUid($id);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        // Trigger the CampaignUpdate event to update the campaign cache information
        // The second parameter of the constructor function is false, meanining immediate update
        event(new \Acelle\Events\CampaignUpdated($campaign));

        if ($campaign->status == 'new') {
            return redirect()->action('CampaignController@edit', ['uid' => $campaign->uid]);
        } else {
            return redirect()->action('CampaignController@overview', ['uid' => $campaign->uid]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::findByUid($id);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // Check step and redirect
        if ($campaign->step() == 0) {
            return redirect()->action('CampaignController@recipients', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() == 1) {
            return redirect()->action('CampaignController@setup', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() == 2) {
            return redirect()->action('CampaignController@template', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() == 3) {
            return redirect()->action('CampaignController@schedule', ['uid' => $campaign->uid]);
        } elseif ($campaign->step() >= 4) {
            return redirect()->action('CampaignController@confirm', ['uid' => $campaign->uid]);
        }
    }

    /**
     * Recipients.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function recipients(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // Get rules and data
        $rules = $campaign->recipientsRules($request->all());
        $campaign->fillRecipients($request->all());

        if (!empty($request->old())) {
            $rules = $campaign->recipientsRules($request->old());
            $campaign->fillRecipients($request->old());
        }

        if ($request->isMethod('post')) {
            // Check validation
            $this->validate($request, $rules);

            $campaign->saveRecipients($request->all());

            // Trigger the CampaignUpdate event to update the campaign cache information
            // The second parameter of the constructor function is false, meanining immediate update
            event(new \Acelle\Events\CampaignUpdated($campaign));

            // redirect to the next step
            return redirect()->action('CampaignController@setup', ['uid' => $campaign->uid]);
        }

        return view('campaigns.recipients', [
            'campaign' => $campaign,
            'rules' => $rules,
        ]);
    }

    /**
     * Campaign setup.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function setup(Request $request)
    {
        $customer = $request->user()->customer;
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        $campaign->from_name = !empty($campaign->from_name) ? $campaign->from_name : $campaign->defaultMailList->from_name;
        $campaign->from_email = !empty($campaign->from_email) ? $campaign->from_email : $campaign->defaultMailList->from_email;
        $campaign->subject = !empty($campaign->subject) ? $campaign->subject : $campaign->defaultMailList->default_subject;

        // Get old post values
        if ($request->old()) {
            $campaign->fillAttributes($request->old());
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Fill values
            $campaign->fillAttributes($request->all());

            // Check validation
            $this->validate($request, $campaign->rules($request));
            $campaign->save();

            // Log
            $campaign->log('created', $customer);

            return redirect()->action('CampaignController@template', ['uid' => $campaign->uid]);
        }

        $rules = $campaign->rules();

        return view('campaigns.setup', [
            'campaign' => $campaign,
            'rules' => $campaign->rules(),
        ]);
    }

    /**
     * Template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function template(Request $request)
    {
        $customer = $request->user()->customer;
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        if ($campaign->type == 'plain-text') {
            return redirect()->action('CampaignController@plain', ['uid' => $campaign->uid]);
        }

        // check if campagin does not have template
        if (!$campaign->template) {
            return redirect()->action('CampaignController@templateCreate', ['uid' => $campaign->uid]);
        }

        return view('campaigns.template.index', [
            'campaign' => $campaign,
            'spamscore' => Setting::isYes('spamassassin.enabled'),
        ]);
    }

    /**
     * Create template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateCreate(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template.create', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Create template from layout.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateLayout(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $template = \Acelle\Model\Template::findByUid($request->template);
            $campaign->setTemplate($template);

            // return redirect()->action('CampaignController@templateEdit', $campaign->uid);
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.campaign.theme.selected'),
                'url' => action('CampaignController@templateBuilderSelect', $campaign->uid),
            ]);
        }

        // default tab
        if ($request->from != 'mine' && !$request->category_uid) {
            $request->category_uid = TemplateCategory::first()->uid;
        }

        return view('campaigns.template.layout', [
            'campaign' => $campaign
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function templateLayoutList(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // from
        if ($request->from == 'mine') {
            $templates = $request->user()->customer->templates()->email();
        } elseif ($request->from == 'gallery') {
            $templates = Template::shared()->email();
        } else {
            $templates = Template::shared()->email()
                ->orWhere('customer_id', '=', $request->user()->customer->id);
        }

        $templates = $templates->notPreserved()->search($request->keyword);

        // category id
        if ($request->category_uid) {
            $templates = $templates->categoryUid($request->category_uid);
        }

        $templates = $templates->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        return view('campaigns.template.layoutList', [
            'campaign' => $campaign,
            'templates' => $templates,
        ]);
    }

    /**
     * Select builder for editing template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateBuilderSelect(Request $request, $uid)
    {
        $campaign = Campaign::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template.templateBuilderSelect', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Edit campaign template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateEdit(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // save campaign html
        if ($request->isMethod('post')) {
            $rules = array(
                'content' => 'required',
            );

            $this->validate($request, $rules);

            // template extra validation by plan (unsubscribe URL for example)
            // UGLY code here, @todo: find a good place to handle this type of validation
            $plan = $request->user()->customer->activeSubscription()->plan;
            if ($plan->getOption('unsubscribe_url_required') == 'yes' && Setting::isYes('campaign.enforce_unsubscribe_url_check')) {
                if (strpos($request->content, '{UNSUBSCRIBE_URL}') === false) {
                    return response()->json(['message' => trans('messages.template.validation.unsubscribe_url_required')], 400);
                }
            }

            $campaign->setTemplateContent($request->content);
            $campaign->save();

            // update plain
            $campaign->updatePlainFromHtml();

            return response()->json([
                'status' => 'success',
            ]);
        }

        return view('campaigns.template.edit', [
            'campaign' => $campaign,
            'list' => $campaign->defaultMailList,
            'templates' => $request->user()->customer->getBuilderTemplates(),
        ]);
    }

    /**
     * Campaign html content.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateContent(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template.content', [
            'content' => $campaign->template->content,
        ]);
    }

    /**
     * Upload template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateUpload(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $campaign->uploadTemplate($request);

            // return redirect()->action('CampaignController@template', $campaign->uid);
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.campaign.template.uploaded'),
                'url' => action('CampaignController@templateBuilderSelect', $campaign->uid),
            ]);
        }

        return view('campaigns.template.upload', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Choose an existed template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function plain(Request $request)
    {
        $user = $request->user();
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Check validation
            $this->validate($request, ['plain' => 'required']);

            // save campaign plain text
            $campaign->plain = $request->plain;
            $campaign->save();

            return redirect()->action('CampaignController@schedule', ['uid' => $campaign->uid]);
        }

        return view('campaigns.plain', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Template preview iframe.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateIframe(Request $request)
    {
        $user = $request->user();
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.preview', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Schedule.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);
        $currentTimezone = $campaign->customer->getTimezone();

        // check step
        if ($campaign->step() < 3) {
            return redirect()->action('CampaignController@template', ['uid' => $campaign->uid]);
        }

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            if ($request->send_now == 'yes') {
                $campaign->run_at = null;
            } else {
                $runAtStr = $request->delivery_date.' '.$request->delivery_time;
                $runAt = Carbon::createFromFormat('Y-m-d H:i', $runAtStr, $currentTimezone);
                $campaign->run_at = $runAt->timezone('UTC'); // store in UTC
            }

            $campaign->save();
            return redirect()->action('CampaignController@confirm', ['uid' => $campaign->uid]);
        }

        // Get the run_at datetime in current customer timezone
        $runAt = is_null($campaign->run_at) ? Carbon::now() : $campaign->run_at;
        $runAt->timezone($currentTimezone);

        $delivery_date = $runAt->format('Y-m-d');
        $delivery_time = $runAt->format('H:i');

        $rules = array(
            'delivery_date' => 'required',
            'delivery_time' => 'required',
        );

        // Get old post values
        if (null !== $request->old()) {
            $campaign->fill($request->old());
        }

        return view('campaigns.schedule', [
            'campaign' => $campaign,
            'rules' => $rules,
            'delivery_date' => $delivery_date,
            'delivery_time' => $delivery_time,
        ]);
    }

    /**
     * Cofirm.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $customer = $request->user()->customer;
        $campaign = Campaign::findByUid($request->uid);

        // check step
        if ($campaign->step() < 4) {
            return redirect()->action('CampaignController@schedule', ['uid' => $campaign->uid]);
        }

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        try {
            $score = $campaign->score();
        } catch (\Exception $e) {
            $score = null;
        }

        // validate and save posted data
        if ($request->isMethod('post') && $campaign->step() >= 5) {
            // UGLY CODE
            $plan = $customer->activeSubscription()->plan;
            if ($plan->getOption('unsubscribe_url_required') == 'yes' && Setting::isYes('campaign.enforce_unsubscribe_url_check')) {
                if (strpos($campaign->getTemplateContent(), '{UNSUBSCRIBE_URL}') === false) {
                    $request->session()->flash('alert-error', trans('messages.template.validation.unsubscribe_url_required'));
                    return view('campaigns.confirm', [
                        'campaign' => $campaign,
                        'score' => $score,
                    ]);
                }
            }

            // Save campaign
            // @todo: check campaign status before requeuing. Otherwise, several jobs shall be created and campaign will get sent several times
            $campaign->schedule();

            // Log
            $campaign->log('started', $customer);

            return redirect()->action('CampaignController@index');
        }

        return view('campaigns.confirm', [
            'campaign' => $campaign,
            'score' => $score,
        ]);
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
        if (isSiteDemo()) {
            return response()->json(["message" => trans('messages.operation_not_allowed_in_demo')], 404);
        }

        $customer = $request->user()->customer;

        if (isSiteDemo()) {
            echo trans('messages.operation_not_allowed_in_demo');

            return;
        }

        if (!is_array($request->uids)) {
            $request->uids = explode(',', $request->uids);
        }

        $campaigns = Campaign::whereIn('uid', $request->uids);

        foreach ($campaigns->get() as $campaign) {
            // authorize
            if (\Gate::allows('delete', $campaign)) {
                $campaign->deleteAndCleanup();
            }
        }

        // Redirect to my lists page
        echo trans('messages.campaigns.deleted');
    }

    /**
     * Campaign overview.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // Trigger the CampaignUpdate event to update the campaign cache information
        // The second parameter of the constructor function is false, meanining immediate update
        event(new \Acelle\Events\CampaignUpdated($campaign));

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.overview', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Campaign links.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function links(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);
        $links = $campaign->clickLogs()
                          ->select(
                              'click_logs.url',
                              DB::raw('count(*) AS clickCount'),
                              DB::raw(sprintf('max(%s) AS lastClick', table('click_logs.created_at')))
                          )->groupBy('click_logs.url')->get();

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.links', [
            'campaign' => $campaign,
            'links' => $links,
        ]);
    }

    /**
     * 24-hour chart.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function chart24h(Request $request)
    {
        $currentTimezone = $request->user()->customer->getTimezone();
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $result = [
            'columns' => [],
            'opened' => [],
            'clicked' => [],
        ];

        $hours = [];

        // columns
        for ($i = 23; $i >= 0; --$i) {
            $time = Carbon::now()->timezone($currentTimezone)->subHours($i);
            $result['columns'][] = $time->format('h') . ':00 ' . $time->format('A');
            $hours[] = $time->format('H');
        }

        // 24h collection
        $openData24h = $campaign->openUniqHours(Carbon::now()->timezone($currentTimezone)->subHours(24), Carbon::now()->timezone($currentTimezone));
        $clickData24h = $campaign->clickHours(Carbon::now()->timezone($currentTimezone)->subHours(24), Carbon::now()->timezone($currentTimezone));

        // data
        foreach ($hours as $hour) {
            $num = isset($openData24h[$hour]) ? count($openData24h[$hour]) : 0;
            $result['opened'][] = $num;

            $num = isset($clickData24h[$hour]) ? count($clickData24h[$hour]) : 0;
            $result['clicked'][] = $num;
        }

        return response()->json($result);
    }

    /**
     * Chart.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function chart(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $result = [
            [
                'name' => trans('messages.recipients'),
                'value' => $campaign->readCache('SubscriberCount', 0),
            ],
            [
                'name' => trans('messages.delivered'),
                'value' => $campaign->deliveredCount(),
            ],
            [
                'name' => trans('messages.failed'),
                'value' => $campaign->failedCount(),
            ],
            [
                'name' => trans('messages.Open'),
                'value' => $campaign->openUniqCount(),
            ],
            [
                'name' => trans('messages.Click'),
                'value' => $campaign->uniqueClickCount(),
            ],
            [
                'name' => trans('messages.Bounce'),
                'value' => $campaign->bounceCount(),
            ],
            [
                'name' => trans('messages.report'),
                'value' => $campaign->feedbackCount(),
            ],
            [
                'name' => trans('messages.unsubscribe'),
                'value' => $campaign->unsubscribeCount(),
            ],
        ];

        return response()->json($result);
    }

    /**
     * Chart Country.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function chartCountry(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $result = [
            'data' => [],
        ];

        // create data
        $total = $campaign->uniqueOpenCount();
        $count = 0;
        foreach ($campaign->topOpenCountries()->get() as $location) {
            $country_name = (!empty($location->country_name) ? $location->country_name : trans('messages.unknown'));
            $result['data'][] = ['value' => $location->aggregate, 'name' => $country_name];
            $count += $location->aggregate;
        }

        // Others
        if ($total > $count) {
            $result['data'][] = ['value' => $total - $count, 'name' => trans('messages.others')];
        }

        usort($result['data'], function ($a, $b) {
            return strcmp($a['value'], $b['value']);
        });
        $result['data'] = array_reverse($result['data']);

        return response()->json($result);
    }

    /**
     * Chart Country by clicks.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function chartClickCountry(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $result = [
            'data' => [],
        ];

        // create data
        $datas = [];
        $total = $campaign->clickCount();
        $count = 0;
        foreach ($campaign->topClickCountries()->get() as $location) {
            $result['data'][] = ['value' => $location->aggregate, 'name' => $location->country_name];
            $count += $location->aggregate;
        }

        // others
        if ($total > $count) {
            $result['data'][] = ['value' => $total - $count, 'name' => trans('messages.others')];
        }

        usort($result['data'], function ($a, $b) {
            return strcmp($a['value'], $b['value']);
        });
        $result['data'] = array_reverse($result['data']);

        return response()->json($result);
    }

    /**
     * 24-hour quickView.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function quickView(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns._quick_view', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Select2 campaign.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        $data = ['items' => [], 'more' => true];

        $data['items'][] = ['id' => 0, 'text' => trans('messages.all')];
        foreach (Campaign::getAll()->get() as $campaign) {
            $data['items'][] = ['id' => $campaign->uid, 'text' => $campaign->name];
        }

        echo json_encode($data);
    }

    /**
     * Tracking when open.
     */
    public function open(Request $request)
    {
        try {
            // Record open log
            $openLog = OpenLog::createFromRequest($request);

            // Execute open callbacks registered for the campaign
            if ($openLog->trackingLog && $openLog->trackingLog->campaign) {
                $openLog->trackingLog->campaign->queueOpenCallbacks($openLog);
            }
        } catch (\Exception $ex) {
            // do nothing
        }

        return response()->file(public_path('images/transparent.gif'));
    }

    /**
     * Tracking when click link.
     */
    public function click(Request $request)
    {
        list($url, $log) = ClickLog::createFromRequest($request);

        if ($log && $log->trackingLog && $log->trackingLog->campaign) {
            $log->trackingLog->campaign->queueClickCallbacks($log);
        }

        return redirect()->away($url);
    }

    /**
     * Unsubscribe url.
     */
    public function unsubscribe(Request $request)
    {
        $subscriber = Subscriber::findByUid($request->subscriber);
        $message_id = StringHelper::base64UrlDecode($request->message_id);

        if (is_null($subscriber)) {
            LaravelLog::error('Subscriber does not exist');
            return view('somethingWentWrong', ['message' => trans('subscriber.invalid')]);
        }

        if ($subscriber->isUnsubscribed()) {
            return view('notice', ['message' => trans('messages.you_are_already_unsubscribed')]);
        }

        // User Tracking Information
        $trackingInfo = [
            'message_id' => $message_id,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ];

        // GeoIP information
        $location = IpLocation::add($request->ip());
        if (!is_null($location)) {
            $trackingInfo['ip_address'] = $location->ip_address;
        }

        // Actually Unsubscribe with tracking information
        $subscriber->unsubscribe($trackingInfo);

        // Page content
        $list = $subscriber->mailList;
        $layout = \Acelle\Model\Layout::where('alias', 'unsubscribe_success_page')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);

        $page->renderContent(null, $subscriber);

        return view('pages.default', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Tracking logs.
     */
    public function trackingLog(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->trackingLogs();

        return view('campaigns.tracking_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Tracking logs ajax listing.
     */
    public function trackingLogListing(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = TrackingLog::search($request, $campaign)->paginate($request->per_page);

        return view('campaigns.tracking_logs_list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Download tracking logs.
     */
    public function trackingLogDownload(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $logtype = $request->input('logtype');

        $job = new ExportCampaignLog($campaign, $logtype);
        $monitor = $campaign->dispatchWithMonitor($job);

        return view('campaigns.download_tracking_log', [
            'campaign' => $campaign,
            'job' => $monitor,
        ]);
    }

    /**
     * Tracking logs export progress.
     */
    public function trackingLogExportProgress(Request $request)
    {
        $job = JobMonitor::findByUid($request->uid);

        $progress = $job->getJsonData();
        $progress['status'] = $job->status;
        $progress['error'] = $job->error;
        $progress['download'] = action('CampaignController@download', ['uid' => $job->uid]);

        return response()->json($progress);
    }

    /**
     * Actually download.
     */
    public function download(Request $request)
    {
        $job = JobMonitor::findByUid($request->uid);
        $path = $job->getJsonData()['path'];
        return response()->download($path)->deleteFileAfterSend(true);
    }

    /**
     * Bounce logs.
     */
    public function bounceLog(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->bounceLogs();

        return view('campaigns.bounce_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Bounce logs listing.
     */
    public function bounceLogListing(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\BounceLog::search($request, $campaign)->paginate($request->per_page);

        return view('campaigns.bounce_logs_list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * FBL logs.
     */
    public function feedbackLog(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->openLogs();

        return view('campaigns.feedback_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * FBL logs listing.
     */
    public function feedbackLogListing(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\FeedbackLog::search($request, $campaign)->paginate($request->per_page);

        return view('campaigns.feedback_logs_list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Open logs.
     */
    public function openLog(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->openLogs();

        return view('campaigns.open_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Open logs listing.
     */
    public function openLogListing(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\OpenLog::search($request, $campaign)->paginate($request->per_page);

        return view('campaigns.open_log_list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Click logs.
     */
    public function clickLog(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->clickLogs();

        return view('campaigns.click_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Click logs listing.
     */
    public function clickLogListing(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\ClickLog::search($request, $campaign)->paginate($request->per_page);

        return view('campaigns.click_log_list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Unscubscribe logs.
     */
    public function unsubscribeLog(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = $campaign->unsubscribeLogs();

        return view('campaigns.unsubscribe_log', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Unscubscribe logs listing.
     */
    public function unsubscribeLogListing(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\UnsubscribeLog::search($request, $campaign)->paginate($request->per_page);

        return view('campaigns.unsubscribe_logs_list', [
            'items' => $items,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Open map.
     */
    public function openMap(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.open_map', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Delete confirm message.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteConfirm(Request $request)
    {
        $lists = Campaign::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        return view('campaigns.delete_confirm', [
            'lists' => $lists,
        ]);
    }

    /**
     * Pause the specified campaign.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function pause(Request $request)
    {
        $customer = $request->user()->customer;
        $campaigns = Campaign::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($campaigns->get() as $campaign) {
            if (\Gate::allows('pause', $campaign)) {
                $campaign->pause();

                // Log
                $campaign->log('paused', $customer);
            }
        }

        //
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.campaigns.paused'),
        ]);
    }

    /**
     * Pause the specified campaign.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function restart(Request $request)
    {
        $customer = $request->user()->customer;
        if (!is_array($request->uids)) {
            $request->uids = explode(',', $request->uids);
        }

        $items = Campaign::whereIn('uid', $request->uids);

        foreach ($items->get() as $item) {
            if (\Gate::allows('restart', $item)) {
                $item->resume();

                // Log
                $item->log('restarted', $customer);
            }
        }

        // Redirect to my lists page
        echo trans('messages.campaigns.restarted');
    }

    /**
     * Subscribers list.
     */
    public function subscribers(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        $subscribers = $campaign->subscribers();

        return view('campaigns.subscribers', [
            'subscribers' => $subscribers,
            'campaign' => $campaign,
            'list' => $campaign->defaultMailList,
        ]);
    }

    /**
     * Subscribers listing.
     */
    public function subscribersListing(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return;
        }

        // Subscribers
        $subscribers = $campaign->getDeliveryReport()
                                ->addSelect('subscribers.*')
                                ->addSelect('bounce_logs.raw AS bounced_message')
                                ->addSelect('feedback_logs.feedback_type AS feedback_message')
                                ->addSelect('tracking_logs.error AS failed_message');

        // Check open conditions
        if ($request->open) {
            // Query of email addresses that DID open
            $openByEmails = $campaign->openLogs()->join('subscribers', 'tracking_logs.subscriber_id', '=', 'subscribers.id')->groupBy('subscribers.email')->select('subscribers.email');

            if ($request->open == 'yes') {
                $subscribers = $subscribers->joinSub($openByEmails, 'OpenedByEmails', function ($join) {
                    $join->on('subscribers.email', '=', 'OpenedByEmails.email');
                });
            } elseif ($request->open = 'no') {
                $subscribers = $subscribers->leftJoinSub($openByEmails, 'OpenedByEmails', function ($join) {
                    $join->on('subscribers.email', '=', 'OpenedByEmails.email');
                })->whereNull('OpenedByEmails.email');
            }
        }

        // Check click conditions
        if ($request->click) {
            // Query of email addresses that DID click
            $clickByEmails = $campaign->clickLogs()->join('subscribers', 'tracking_logs.subscriber_id', '=', 'subscribers.id')->groupBy('subscribers.email')->select('subscribers.email');

            if ($request->click == 'clicked') {
                $subscribers = $subscribers->joinSub($clickByEmails, 'ClickedByEmails', function ($join) {
                    $join->on('subscribers.email', '=', 'ClickedByEmails.email');
                });
            } elseif ($request->click = 'not_clicked') {
                $subscribers = $subscribers->leftJoinSub($clickByEmails, 'ClickedByEmails', function ($join) {
                    $join->on('subscribers.email', '=', 'ClickedByEmails.email');
                })->whereNull('ClickedByEmails.email');
            }
        }

        // Paging
        $subscribers = $subscribers->search($request->keyword)->paginate($request->per_page);

        // Field information
        $fields = $campaign->defaultMailList->getFields->whereIn('uid', $request->columns);

        return view('campaigns._subscribers_list', [
            'subscribers' => $subscribers,
            'list' => $campaign->defaultMailList,
            'campaign' => $campaign,
            'fields' => $fields,
        ]);
    }

    /**
     * Buiding email template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateBuild(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        $elements = [];
        if (isset($request->style)) {
            $elements = \Acelle\Model\Template::templateStyles()[$request->style];
        }

        return view('campaigns.template_build', [
            'campaign' => $campaign,
            'elements' => $elements,
            'list' => $campaign->defaultMailList,
        ]);
    }

    /**
     * Re-Buiding email template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateRebuild(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template_rebuild', [
            'campaign' => $campaign,
            'list' => $campaign->defaultMailList,
        ]);
    }

    /**
     * Copy campaign.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        $campaign = Campaign::findByUid($request->copy_campaign_uid);

        // authorize
        if (\Gate::denies('copy', $campaign)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('campaigns.copy', [
                    'campaign' => $campaign,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $campaign->copy($request->name);
            return trans('messages.campaign.copied');
        }

        return view('campaigns.copy', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Send email for testing campaign.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendTestEmail(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        if ($request->isMethod('post')) {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            //
            if ($validator->fails()) {
                return response()->view('campaigns.sendTestEmail', [
                    'campaign' => $campaign,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $sending = $campaign->sendTestEmail($request->email);

            return response()->json($sending);
        }

        return view('campaigns.sendTestEmail', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Preview template.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        $campaign = Campaign::findByUid($id);

        // authorize
        if (\Gate::denies('preview', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.preview', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Preview content template.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function previewContent(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);
        $subscriber = Subscriber::findByUid($request->subscriber_uid);

        // authorize
        if (\Gate::denies('preview', $campaign)) {
            return $this->notAuthorized();
        }

        echo $campaign->getHtmlContent($subscriber);
    }

    /**
     * List segment form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function listSegmentForm(Request $request)
    {
        // Get current user
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns._list_segment_form', [
            'campaign' => $campaign,
            'lists_segment_group' => [
                'list' => null,
                'is_default' => false,
            ],
        ]);
    }

    /**
     * Change template from exist template.
     *
     */
    public function templateChangeTemplate(Request $request, $uid, $template_uid)
    {
        // Generate info
        $campaign = Campaign::findByUid($uid);
        $changeTemplate = Template::findByUid($template_uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        $campaign->changeTemplate($changeTemplate);
    }

    /**
     * Email web view.
     */
    public function webView(Request $request)
    {
        $message_id = StringHelper::base64UrlDecode($request->message_id);
        $tracking_log = TrackingLog::where('message_id', '=', $message_id)->first();

        try {
            if (!is_object($tracking_log)) {
                throw new \Exception(trans('messages.web_view_can_not_find_tracking_log_with_message_id'));
            }

            $subscriber = $tracking_log->subscriber;
            $campaign = $tracking_log->campaign;

            if (!is_object($campaign) || !is_object($subscriber)) {
                throw new \Exception(trans('messages.web_view_can_not_find_campaign_or_subscriber'));
            }

            return view('campaigns.web_view', [
                'campaign' => $campaign,
                'subscriber' => $subscriber,
                'message_id' => $message_id,
            ]);
        } catch (\Exception $e) {
            MailLog::error($e->getMessage());

            return view('somethingWentWrong', ['message' => trans('messages.the_email_no_longer_exists')]);
        }
    }

    /**
     * Email web view for previewing before sending
     */
    public function webViewPreview(Request $request)
    {
        $subscriber = Subscriber::findByUid($request->subscriber_uid);
        $campaign = Campaign::findByUid($request->campaign_uid);

        if (is_null($subscriber) || is_null($campaign)) {
            throw new \Exception('Invalid subscriber or campaign UID');
        }

        return view('campaigns.web_view', [
            'campaign' => $campaign,
            'subscriber' => $subscriber,
            'message_id' => null,
        ]);
    }

    /*
     * Select campaign type page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function selectType(Request $request)
    {
        // authorize
        if (\Gate::denies('create', new Campaign())) {
            return $this->notAuthorized();
        }

        return view('campaigns.select_type');
    }

    /**
     * Template review.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateReview(Request $request)
    {
        // Get current user
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template_review', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Template review iframe.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateReviewIframe(Request $request)
    {
        // Get current user
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template_review_iframe', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Resend the specified campaign.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request, $uid)
    {
        $customer = $request->user()->customer;
        $campaign = Campaign::findByUid($uid);

        // do resend with option: $request->option : not_receive|not_open|not_click
        if ($request->isMethod('post')) {
            // authorize
            if (\Gate::allows('resend', $campaign)) {
                $campaign->resend($request->option);
                // Redirect to my lists page
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.campaign.resent'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('messages.not_authorized_message'),
                ], 400);
            }
        }

        return view('campaigns.resend', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Get spam score.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function spamScore(Request $request, $uid)
    {
        // Get current user
        $campaign = Campaign::findByUid($uid);

        try {
            $score = $campaign->score();
        } catch (\Exception $e) {
            return response()->json("Cannot get score. Make sure you setup for SpamAssassin correctly.\r\n".$e->getMessage(), 500); // Status code here
        }

        return view('campaigns.spam_score', [
            'score' => $score,
        ]);
    }

    /**
     * Edit email content.
     *
     */
    public function builderClassic(Request $request, $uid)
    {
        // Generate info
        $campaign = Campaign::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $rules = array(
                'html' => 'required',
            );

            // make validator
            $validator = \Validator::make($request->all(), $rules);

            // redirect if fails
            if ($validator->fails()) {
                // faled
                return response()->json($validator->errors(), 400);
            }

            // UGLY CODE here, @todo: find a better place to house this type of validation
            $plan = $request->user()->customer->activeSubscription()->plan;
            if ($plan->getOption('unsubscribe_url_required') == 'yes' && Setting::isYes('campaign.enforce_unsubscribe_url_check')) {
                if (strpos($request->html, '{UNSUBSCRIBE_URL}') === false) {
                    return response()->json(['message' => trans('messages.template.validation.unsubscribe_url_required')], 400);
                }
            }

            // Save template
            $campaign->setTemplateContent($request->html);
            $campaign->preheader = $request->preheader;
            $campaign->save();

            // update plain
            $campaign->updatePlainFromHtml();

            // success
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.template.updated'),
            ], 201);
        }

        return view('campaigns.builderClassic', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Edit plain text.
     *
     */
    public function builderPlainEdit(Request $request, $uid)
    {
        // Generate info
        $campaign = Campaign::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $rules = array(
                'plain' => 'required',
            );

            // make validator
            $validator = \Validator::make($request->all(), $rules);

            // redirect if fails
            if ($validator->fails()) {
                // faled
                return response()->json($validator->errors(), 400);
            }

            // Save template
            $campaign->plain = $request->plain;
            $campaign->save();

            // success
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.template.updated'),
            ], 201);
        }

        return view('campaigns.builderPlainEdit', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Upload attachment.
     *
     */
    public function uploadAttachment(Request $request, $uid)
    {
        // Generate info
        $campaign = Campaign::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        foreach ($request->file as $file) {
            $campaign->uploadAttachment($file);
        }
    }

    /**
     * Download attachment.
     *
     */
    public function downloadAttachment(Request $request, $uid)
    {
        // Generate info
        $campaign = Campaign::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        return response()->download($campaign->getAttachmentPath($request->name), $request->name);
    }

    /**
     * Remove attachment.
     *
     */
    public function removeAttachment(Request $request, $uid)
    {
        // Generate info
        $campaign = Campaign::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        unlink($campaign->getAttachmentPath($request->name));
    }

    public function updateStats(Request $request, $uid)
    {
        $campaign = Campaign::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        $campaign->updateCache();
        echo $campaign->status;
    }

    public function notification(Request $request)
    {
        $message = StringHelper::base64UrlDecode($request->message);
        return response($message, 200)->header('Content-Type', 'text/plain');
    }

    public function customPlainOn(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        $campaign->plain = 'something';
        $campaign->save();

        return redirect()->action('CampaignController@builderPlainEdit', [
            'uid' => $campaign->uid,
        ]);
    }

    public function customPlainOff(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (!$request->user()->customer->can('update', $campaign)) {
            return $this->notAuthorized();
        }

        $campaign->plain = null;
        $campaign->save();

        return redirect()->action('CampaignController@builderPlainEdit', [
            'uid' => $campaign->uid,
        ]);
    }

    public function previewAs(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        return view('campaigns.previewAs', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Subscribers listing.
     */
    public function previewAsList(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return;
        }

        // Subscribers
        $subscribers = $campaign->subscribers()
            ->search($request->keyword)->paginate($request->per_page);

        return view('campaigns.previewAsList', [
            'subscribers' => $subscribers,
            'campaign' => $campaign,
        ]);
    }

    public function webhooks(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.webhooks', [
            'campaign' => $campaign,
        ]);
    }

    public function webhooksList(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.webhooksList', [
            'campaign' => $campaign,
        ]);
    }

    public function webhooksAdd(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);
        $webhook = $campaign->newWebhook();

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            list($webhook, $validator) = $webhook->createFromArray($request->all());

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('campaigns.webhooksAdd', [
                    'campaign' => $campaign,
                    'webhook' => $webhook,
                    'errors' => $validator->errors(),
                ], 400);
            }

            return response()->json([
                'message' => trans('messages.webhook.added'),
            ]);
        }

        return view('campaigns.webhooksAdd', [
            'campaign' => $campaign,
            'webhook' => $webhook,
        ]);
    }

    public function webhooksLinkSelect(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.webhooksLinkSelect', [
            'campaign' => $campaign,
        ]);
    }

    public function webhooksEdit(Request $request)
    {
        $webhook = \Acelle\Model\CampaignWebhook::findByUid($request->webhook_uid);

        // authorize
        if (\Gate::denies('update', $webhook->campaign)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            list($webhook, $validator) = $webhook->updateFromArray($request->all());

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('campaigns.webhooksEdit', [
                    'webhook' => $webhook,
                    'errors' => $validator->errors(),
                ], 400);
            }

            return response()->json([
                'message' => trans('messages.webhook.updated'),
            ]);
        }

        return view('campaigns.webhooksEdit', [
            'webhook' => $webhook,
        ]);
    }

    public function webhooksDelete(Request $request)
    {
        $webhook = \Acelle\Model\CampaignWebhook::findByUid($request->webhook_uid);

        // authorize
        if (\Gate::denies('update', $webhook->campaign)) {
            return $this->notAuthorized();
        }

        $webhook->delete();

        return response()->json([
            'message' => trans('messages.webhook.deleted'),
        ]);
    }

    public function webhooksSampleRequest(Request $request)
    {
        $webhook = \Acelle\Model\CampaignWebhook::findByUid($request->webhook_uid);

        // authorize
        if (\Gate::denies('read', $webhook->campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.webhooksSampleRequest', [
            'webhook' => $webhook,
        ]);
    }

    public function webhooksTest(Request $request)
    {
        $webhook = \Acelle\Model\CampaignWebhook::findByUid($request->webhook_uid);
        $result = null;

        // authorize
        if (\Gate::denies('read', $webhook->campaign)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $client = new \GuzzleHttp\Client();

            try {
                $response = $client->request('GET', $webhook->endpoint, [
                    'headers' => [
                        "content-type" => "application/json"
                    ],
                    'body' => '{hello: "world"}',
                    'http_errors' => false,
                ]);

                $result = [
                    'status' => 'sent',
                    'code' => $response->getStatusCode(),
                    'message' => $response->getReasonPhrase(),
                ];
            } catch (\Exception $e) {
                $result = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return view('campaigns.webhooksTest', [
            'webhook' => $webhook,
            'result' => $result,
        ]);
    }

    public function webhooksTestMessage(Request $request, $webhook_uid, $message_id)
    {
        $webhook = \Acelle\Model\CampaignWebhook::findByUid($request->webhook_uid);
        $result = null;

        // authorize
        if (\Gate::denies('read', $webhook->campaign)) {
            return $this->notAuthorized();
        }

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('GET', $webhook->endpoint, [
                'headers' => [
                    "content-type" => "application/json"
                ],
                'body' => '{hello: "world"}',
                'http_errors' => false,
            ]);

            $result = [
                'status' => 'sent',
                'code' => $response->getStatusCode(),
                'message' => $response->getReasonPhrase(),
                'message_id' => $message_id,
                'endpoint' => $webhook->endpoint,
                'responseBody' => $response->getBody(),
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'message_id' => $message_id,
                'endpoint' => $webhook->endpoint,
                'responseBody' => $response->getBody(),
            ];
        }

        return view('campaigns.webhooksTestMessage', [
            'webhook' => $webhook,
            'result' => $result,
        ]);
    }

    /**
     * Click logs execute.
     */
    public function clickLogExecute(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.clickLogExecute', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Open logs execute.
     */
    public function openLogExecute(Request $request)
    {
        $campaign = Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.openLogExecute', [
            'campaign' => $campaign,
        ]);
    }
}
