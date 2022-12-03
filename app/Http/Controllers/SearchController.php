<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Exception;

class SearchController extends Controller
{
    public function general(Request $request)
    {
        $items = [
            [
                'names' => [trans('messages.api')],
                'url' => action('AccountController@api'),
                'keywords' => [
                    trans('messages.api'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.profile')],
                'url' => action('AccountController@profile'),
                'keywords' => [
                    trans('messages.theme_mode'),
                    trans('messages.account.menu_layout'),
                    trans('messages.account.personality'),
                    trans('messages.profile_photo'),
                    trans('messages.basic_information'),
                    trans('messages.email'),
                    trans('messages.password'),
                    trans('messages.change_password'),
                    trans('messages.color_scheme'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.billing')],
                'url' => action('AccountController@billing'),
                'keywords' => [
                    trans('messages.payment_method'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.subscription')],
                'url' => action('SubscriptionController@index'),
                'keywords' => [
                    trans('messages.subscription.resume'),
                    trans('messages.subscription.cancel_now'),
                    trans('messages.subscription.change_plan'),
                    trans('messages.invoices'),
                    trans('messages.transactions'),
                    trans('messages.subscription.logs'),
                    trans('messages.payment_method'),
                ],
            ],
            [
                'names' => [trans('messages.dashboard')],
                'url' => action('HomeController@index'),
                'keywords' => [
                    trans('messages.home'),
                ],
            ],
            [
                'names' => [trans('messages.lists')],
                'url' => action('MailListController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.campaigns')],
                'url' => action('CampaignController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.campaigns'), trans('messages.create')],
                'url' => action('CampaignController@selectType'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.automations')],
                'url' => action('Automation2Controller@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates')],
                'url' => action('TemplateController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.create')],
                'url' => action('TemplateController@builderCreate'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.upload')],
                'url' => action('TemplateController@uploadTemplate'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.gallery')],
                'url' => action('TemplateController@index', [
                    'view' => 'grid',
                    'from' => 'gallery',
                ]),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.content'), trans('messages.products')],
                'url' => action('ProductController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.content'), trans('messages.stores_connections')],
                'url' => action('SourceController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.content'), trans('messages.stores_connections'), trans('messages.source.add_new')],
                'url' => action('SourceController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.activities')],
                'url' => action('AccountController@logs'),
                'keywords' => [
                    trans('messages.log'),
                ],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.sending_domains')],
                'url' => action('SendingDomainController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.sending_domains'), trans('messages.add')],
                'url' => action('SendingDomainController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.tracking_domains')],
                'url' => action('TrackingDomainController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.tracking_domains'), trans('messages.add')],
                'url' => action('TrackingDomainController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.forms')],
                'url' => action('FormController@create'),
                'keywords' => [

                ],
            ],
            [
                'names' => [trans('messages.forms'), trans('messages.create')],
                'url' => action('FormController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.blacklist')],
                'url' => action('BlacklistController@index'),
                'keywords' => [],
            ],
        ];

        // search
        $results = filterSearchArray($items, $request->keyword);

        return view('search.general', [
            'keyword' => $request->keyword,
            'results' => $results,
        ]);
    }

    public function campaigns(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $campaigns = $customer->campaigns()
            ->search($request->keyword)
            ->filter($request)
            ->orderBy('campaigns.name', 'asc')
            ->paginate($count);

        return view('search.campaigns', [
            'keyword' => $request->keyword,
            'campaigns' => $campaigns,
        ]);
    }

    public function lists(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $lists = $customer->mailLists()
            ->search($request->keyword)
            ->orderBy('mail_lists.name', 'asc')
            ->paginate($count);

        return view('search.lists', [
            'keyword' => $request->keyword,
            'lists' => $lists,
        ]);
    }

    public function automations(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $automations = $customer->automation2s()
            ->search($request->keyword)
            ->orderBy('automation2s.name', 'asc')
            ->paginate($count);

        return view('search.automations', [
            'keyword' => $request->keyword,
            'automations' => $automations,
        ]);
    }

    public function templates(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $templates = $customer->templates()
            ->email()
            ->search($request->keyword)
            ->notPreserved()
            ->orderBy('templates.name', 'asc')
            ->paginate($count);

        return view('search.templates', [
            'keyword' => $request->keyword,
            'templates' => $templates,
        ]);
    }

    public function subscribers(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $subscribers = $customer->subscribers()
            ->search($request->keyword)
            ->paginate($count);

        return view('search.subscribers', [
            'keyword' => $request->keyword,
            'subscribers' => $subscribers,
        ]);
    }

    public function forms(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $forms = $customer->forms()
            ->search($request->keyword)
            ->paginate($count);

        return view('search.forms', [
            'keyword' => $request->keyword,
            'forms' => $forms,
        ]);
    }

    public function websites(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $websites = $customer->websites()
            ->search($request->keyword)
            ->paginate($count);

        return view('search.websites', [
            'keyword' => $request->keyword,
            'websites' => $websites,
        ]);
    }
}
