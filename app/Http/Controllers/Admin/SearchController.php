<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Exception;

class SearchController extends Controller
{
    public function general(Request $request)
    {
        $items = [
            [
                'names' => [trans('messages.settings'), trans('messages.general')],
                'url' => action('Admin\SettingController@general'),
                'keywords' => [
                    trans('messages.api'),
                    trans('messages.site_logo'),
                    trans('messages.site_favicon'),
                    trans('messages.site_offline_message'),
                    trans('messages.site_keyword'),
                    trans('messages.site_description'),
                    trans('messages.site_online'),
                    trans('messages.login_recaptcha'),
                    trans('messages.builder'),
                ],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.system_email')],
                'url' => action('Admin\SettingController@mailer'),
                'keywords' => [
                    trans('messages.smtp'),
                    trans('messages.sendmail'),
                ],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.system_urls')],
                'url' => action('Admin\SettingController@urls'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.upgrade_manager')],
                'url' => action('Admin\SettingController@upgrade'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.background_job')],
                'url' => action('Admin\SettingController@cronjob'),
                'keywords' => [
                    trans('messages.smtp'),
                    'crontab cron',
                ],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.license_tab')],
                'url' => action('Admin\SettingController@license'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.api')],
                'url' => action('Admin\AccountController@api'),
                'keywords' => [
                    trans('messages.api'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.profile')],
                'url' => action('Admin\AccountController@profile'),
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
                'names' => [trans('messages.subscriptions')],
                'url' => action('Admin\SubscriptionController@index'),
                'keywords' => [
                    trans('messages.subscription.resume'),
                    trans('messages.subscription.cancel_now'),
                    trans('messages.subscription.change_plan'),
                    trans('messages.invoices'),
                    trans('messages.transactions'),
                    trans('messages.payment_method'),
                ],
            ],
            [
                'names' => [trans('messages.dashboard')],
                'url' => action('Admin\HomeController@index'),
                'keywords' => [
                    trans('messages.home'),
                ],
            ],
            [
                'names' => [trans('messages.templates')],
                'url' => action('Admin\TemplateController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.create')],
                'url' => action('Admin\TemplateController@builderCreate'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.upload')],
                'url' => action('Admin\TemplateController@uploadTemplate'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending_servers')],
                'url' => action('Admin\SendingServerController@index'),
                'keywords' => [
                    'Amazon SMTP sendgrid mailgun elastic Sparkpost smtp sendmail',
                ],
            ],
            [
                'names' => [trans('messages.sending_servers'), trans('messages.add')],
                'url' => action('Admin\SendingServerController@select'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                    'Amazon SMTP sendgrid mailgun elastic Sparkpost smtp sendmail',
                ],
            ],
            [
                'names' => [trans('messages.page_form_layout')],
                'url' => action('Admin\LayoutController@index'),
                'keywords' => [
                    trans('messages.sign_up_form'),
                    trans('messages.sign_up_thankyou_page'),
                    trans('messages.final_welcome_email'),
                    trans('messages.unsubscribe_form'),
                    trans('messages.unsubscribe_success_page'),
                    trans('messages.goodbye_email'),
                    trans('messages.unsubscribe'),
                    trans('messages.update_profile'),
                    trans('messages.update_profile_form'),
                    trans('messages.update_profile_success_page'),
                ],
            ],
            [
                'names' => [trans('messages.customers')],
                'url' => action('Admin\CustomerController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.customers'), trans('messages.add')],
                'url' => action('Admin\CustomerController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.plans')],
                'url' => action('Admin\PlanController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.admins')],
                'url' => action('Admin\AdminController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.admins'), trans('messages.add')],
                'url' => action('Admin\AdminController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.admin_groups')],
                'url' => action('Admin\AdminGroupController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.admin_groups'), trans('messages.create')],
                'url' => action('Admin\AdminGroupController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.plan'), trans('messages.currencies')],
                'url' => action('Admin\CurrencyController@index'),
                'keywords' => [
                    trans('messages.payment'),
                ],
            ],
            [
                'names' => [trans('messages.plan'), trans('messages.tax_settings')],
                'url' => action('Admin\TaxController@settings'),
                'keywords' => [
                ],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.sub_accounts')],
                'url' => action('Admin\SubAccountController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.bounce_handlers')],
                'url' => action('Admin\BounceHandlerController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.bounce_handlers'), trans('messages.create')],
                'url' => action('Admin\BounceHandlerController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.feedback_loop_handlers')],
                'url' => action('Admin\FeedbackLoopHandlerController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.feedback_loop_handlers'), trans('messages.create')],
                'url' => action('Admin\FeedbackLoopHandlerController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.email_verification_servers')],
                'url' => action('Admin\EmailVerificationServerController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending'), trans('messages.email_verification_servers'), trans('messages.create')],
                'url' => action('Admin\EmailVerificationServerController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.languages')],
                'url' => action('Admin\LanguageController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.languages'), trans('messages.add')],
                'url' => action('Admin\LanguageController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.payment_gateways')],
                'url' => action('Admin\PaymentController@index'),
                'keywords' => [
                    'Stripe Braintree Direct PayPal Paystack coinpayments bitcoin digital razorpay',
                    trans('messages.payment'),
                ],
            ],
            [
                'names' => [trans('messages.settings'), trans('messages.plugins')],
                'url' => action('Admin\PluginController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.report'), trans('messages.blacklist')],
                'url' => action('Admin\BlacklistController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.report'), trans('messages.tracking_log')],
                'url' => action('Admin\TrackingLogController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.report'), trans('messages.bounce_log')],
                'url' => action('Admin\BounceLogController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.report'), trans('messages.feedback_log')],
                'url' => action('Admin\FeedbackLogController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.report'), trans('messages.open_log')],
                'url' => action('Admin\OpenLogController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.report'), trans('messages.unsubscribe_log')],
                'url' => action('Admin\UnsubscribeLogController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.notifications')],
                'url' => action('Admin\NotificationController@index'),
                'keywords' => [],
            ],
        ];

        $results = filterSearchArray($items, $request->keyword);

        return view('admin.search.general', [
            'keyword' => $request->keyword,
            'results' => $results,
        ]);
    }

    public function customers(Request $request)
    {
        $count = 5;

        $customers = \Acelle\Model\customer::search($request->keyword)
            ->paginate($count);

        return view('admin.search.customers', [
            'keyword' => $request->keyword,
            'customers' => $customers,
        ]);
    }

    public function templates(Request $request)
    {
        $count = 5;

        $templates = \Acelle\Model\Template::shared()
            ->email()
            ->search($request->keyword)
            ->orderBy('templates.name', 'asc')
            ->paginate($count);

        return view('admin.search.templates', [
            'keyword' => $request->keyword,
            'templates' => $templates,
        ]);
    }

    public function plans(Request $request)
    {
        $count = 5;

        $plans = \Acelle\Model\Plan::search($request->keyword)
            ->paginate($count);

        return view('admin.search.plans', [
            'keyword' => $request->keyword,
            'plans' => $plans,
        ]);
    }

    public function sending_servers(Request $request)
    {
        $count = 5;

        $sending_servers = \Acelle\Model\SendingServer::search($request->keyword)
            ->paginate($count);

        return view('admin.search.sending_servers', [
            'keyword' => $request->keyword,
            'sending_servers' => $sending_servers,
        ]);
    }
}
