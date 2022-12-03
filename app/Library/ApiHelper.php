<?php

/**
 * Api class.
 *
 * Misc helper tool
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   Acelle Library
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Library;

class ApiHelper
{
    /**
     * Docs content.
     *
     * @var string
     */
    public static function docs()
    {
        $docs = [];

        // AUTHENTICATION
        $docs[] = [
            'view' => 'frontend',
            'title' => trans('messages.authentication'),
            'functions' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/login-token',
                    'description' => 'Generate one time login token',
                    'help' => 'User can login by visiting the following URL:<br>
                        <code>' . action('Controller@tokenLogin', ['token' => '*|token_string|*']) . '</code>',
                    'parameters' => [
                    ],
                    'returns' => 'Token string',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .str_replace('-uid-', '<redbold>{uid}</redbold>', action("Api\CustomerController@loginToken")).' \
-d api_token=' .\Auth::user()->api_token,
                ],
            ],
        ];

        // LIST
        $docs[] = [
            'view' => 'frontend',
            'title' => trans('messages.LISTS'),
            'functions' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/lists',
                    'description' => trans('messages.add_a_new_list'),
                    'parameters' => [
                        ['name' => '$name', 'description' => 'List\'s name'],
                        ['name' => '$from_email', 'description' => 'Default From email address'],
                        ['name' => '$from_name', 'description' => 'Default From name'],
                        ['name' => '$default_subject', 'description' => 'Default email subject', 'optional' => true],
                        ['name' => 'divider', 'description' => 'Contact information'],
                        ['name' => '$contact[company]', 'description' => 'Name'],
                        ['name' => '$contact[state]', 'description' => 'State / Province / Region'],
                        ['name' => '$contact[address_1]', 'description' => 'Address 1'],
                        ['name' => '$contact[address_2]', 'description' => 'Address 2'],
                        ['name' => '$contact[city]', 'description' => 'City'],
                        ['name' => '$contact[zip]', 'description' => 'Zip / Postal code'],
                        ['name' => '$contact[phone]', 'description' => 'Phone'],
                        ['name' => '$contact[country_id]', 'description' => 'Country id'],
                        ['name' => '$contact[email]', 'description' => 'Email'],
                        ['name' => '$contact[url]', 'description' => 'Home page', 'optional' => true],
                        ['name' => '$subscribe_confirmation', 'description' => 'Send subscription confirmation email (Double Opt-In)'],
                        ['name' => '$send_welcome_email', 'description' => 'Send a final welcome email'],
                        ['name' => '$unsubscribe_notification', 'description' => 'Send unsubscribe notification to subscribers'],
                        ['name' => '$send_welcome_email', 'description' => 'Send a final welcome email'],
                    ],
                    'returns' => 'Creation messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .action("Api\MailListController@store").' \
-d api_token=' .\Auth::user()->api_token.' \
-d name=List+1 \
-d from_email=admin@abccorp.org \
-d from_name=ABC+Corp. \
-d default_subject=Welcome+to+ABC+Corp. \
-d contact[company]=ABC+Corp. \
-d contact[state]=Armagh \
-d contact[address_1]=14+Tottenham+Court+Road+London+England \
-d contact[address_2]=44-46+Morningside+Road+Edinburgh+Scotland+EH10+4BF \
-d contact[city]=Noname \
-d contact[zip]=80000 \
-d contact[phone]=123+456+889 \
-d contact[country_id]=' .\Acelle\Model\Country::first()->id.' \
-d contact[email]=info@abccorp.org \
-d contact[url]=http://www.abccorp.org \
-d subscribe_confirmation=1 \
-d send_welcome_email=1 \
-d unsubscribe_notification=1',
                ],

                [
                    'method' => 'GET',
                    'uri' => '/api/v1/lists',
                    'description' => 'Get information about all lists',
                    'parameters' => [],
                    'returns' => 'List of all user\'s mail lists in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .action("Api\MailListController@index").' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'GET',
                    'uri' => '/api/v1/lists/{uid}',
                    'description' => 'Get information about a specific list',
                    'parameters' => [],
                    'returns' => 'All list informations in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\MailListController@show", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'POST',
                    'uri' => '/api/v1/lists/{uid}/add-field',
                    'description' => 'Add custom field to list',
                    'parameters' => [
                        ['name' => '$type', 'description' => 'Choose one of these types: text, number, datetime.'],
                        ['name' => '$label', 'description' => 'Field\' label'],
                        ['name' => '$tag', 'description' => 'The tag name may have alpha-numeric characters, as well as dashes and underscores.'],
                        ['name' => '$default_value', 'description' => 'Default value of the field', 'optional' => true],
                    ],
                    'returns' => 'Creation messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\MailListController@addField", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token . ' \
-d type=text \
-d label=Custom \
-d tag=CUSTOM_FIELD_1 \
-d default_value=test'
                ],

                [
                    'method' => 'DELETE',
                    'uri' => '/api/v1/lists/{uid}',
                    'description' => 'Delete a list',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'List\'s uid'],
                    ],
                    'returns' => 'Result messages in json',
                    'example' => 'curl -X DELETE -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', str_replace('-LIST_ID-', '<redbold>{list_uid}</redbold>', action("Api\MailListController@delete", ['uid' => '-ID-']))).' \
-d api_token=' .\Auth::user()->api_token,
                ],
            ],
        ];

        // CAMPAIGN
        $docs[] = [
            'view' => 'frontend',
            'title' => trans('messages.CAMPAIGNS'),
            'functions' => [
                [
                    'method' => 'GET',
                    'uri' => '/api/v1/campaigns',
                    'description' => 'Get information about all campaigns',
                    'parameters' => [],
                    'returns' => 'List of all user\'s campaigns in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .action("Api\CampaignController@index").' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'GET',
                    'uri' => '/api/v1/campaigns/{uid}',
                    'description' => 'Get information about a specific campaign',
                    'parameters' => [],
                    'returns' => 'Campaign\'s information in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\CampaignController@show", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'POST',
                    'uri' => '/api/v1/campaigns/{uid}/pause',
                    'description' => 'Pause a specific campaign',
                    'parameters' => [],
                    'returns' => 'Action messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\CampaignController@pause", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token,
                ],
            ],
        ];

        // SUBSCRIBER
        $docs[] = [
            'view' => 'frontend',
            'title' => trans('messages.SUBSCRIBERS'),
            'functions' => [
                [
                    'method' => 'GET',
                    'uri' => '/api/v1/subscribers',
                    'description' => 'Display list\'s subscribers',
                    'parameters' => [
                        ['name' => '$list_uid', 'description' => 'List\'s uid'],
                        ['name' => '$open', 'description' => 'yes - opened some campaigns<br>no - not opened any campaign', 'optional' => true, 'default' => 'all'],
                        ['name' => '$click', 'description' => 'yes - clicked some campaigns<br>no - not clicked any campaign', 'optional' => true, 'default' => 'all'],
                        ['name' => '$per_page', 'description' => 'Number of subscribers per page', 'optional' => true, 'default' => 25],
                        ['name' => '$page', 'description' => 'Page number'],
                    ],
                    'returns' => 'List of all list\'s subscribers in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .action("Api\SubscriberController@index").' \
-d api_token=' .\Auth::user()->api_token.' \
-d list_uid=<redbold>{list_uid}</redbold> \
-d per_page=20 \
-d page=1',
                ],

                [
                    'method' => 'POST',
                    'uri' => '/api/v1/subscribers',
                    'description' => 'Create subscriber for a mail list',
                    'parameters' => [
                        ['name' => '$list_uid', 'description' => 'List\'s uid'],
                        ['name' => '$EMAIL', 'description' => 'Subscriber\'s email'],
                        ['name' => '$tag', 'description' => 'Subscriber\'s tags, seperated by a comma (,).', 'optional' => true],
                        ['name' => '$[OTHER_FIELDS...]', 'description' => 'All subscriber\'s other fields: FIRST_NAME (?), LAST_NAME (?),... (depending on the list fields configuration)', 'optional' => true],
                    ],
                    'returns' => 'Creation messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .action("Api\SubscriberController@store").' \
-d api_token=' .\Auth::user()->api_token.' \
-d list_uid=<redbold>{list_uid}</redbold> \
-d EMAIL=test@gmail.com \
-d tag=foo,bar,tag+with+space, \
-d FIRST_NAME=Marine \
-d LAST_NAME=Joze',
                ],

                [
                    'method' => 'GET',
                    'uri' => '/api/v1/subscribers/{uid}',
                    'description' => 'Get information about a specific subscriber',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Subsciber\'s uid or email'],
                    ],
                    'returns' => 'All subscriber information in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', str_replace('-LIST_ID-', '<redbold>{list_uid}</redbold>', action("Api\SubscriberController@show", ['subscriber' => '-ID-']))).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'PATCH',
                    'uri' => '/api/v1/subscribers/{uid}',
                    'description' => 'Update subscriber for a mail list',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Subscriber\'s uid'],
                        ['name' => '$EMAIL', 'description' => 'Subscriber\'s email'],
                        ['name' => '$tag', 'description' => 'Subscriber\'s tags, seperated by a comma (,).', 'optional' => true],
                        ['name' => '$[OTHER_FIELDS...]', 'description' => 'All subscriber\'s other fields: FIRST_NAME (?), LAST_NAME (?),... (depending on the list fields configuration)', 'optional' => true],
                    ],
                    'returns' => 'Update messages in json',
                    'example' => 'curl -X PATCH -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', str_replace('-LIST_ID-', '<redbold>{list_uid}</redbold>', action("Api\SubscriberController@update", ['subscriber' => '-ID-']))).' \
-d api_token=' .\Auth::user()->api_token.' \
-d EMAIL=test@gmail.com \
-d tag=foo,bar,tag+with+space, \
-d FIRST_NAME=Marine \
-d LAST_NAME=Joze',
                ],

                [
                    'method' => 'POST',
                    'uri' => '/api/v1/subscribers/{uid}/add-tag',
                    'description' => 'Add tag(s) to subscriber',
                    'parameters' => [
                        ['name' => '$tag', 'description' => 'Subscriber\'s tags, seperated by a comma (,).', 'optional' => true],
                    ],
                    'returns' => 'Creation messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .str_replace('-UID-', '<redbold>{uid}</redbold>', action("Api\SubscriberController@addTag", ['uid' => '-UID-'])).' \
-d api_token=' .\Auth::user()->api_token.' \
-d tag=foo,bar,tag+with+space',
                ],

                [
                    'method' => 'GET',
                    'uri' => '/api/v1/subscribers/email/{email}',
                    'description' => 'Find subscribers with email',
                    'parameters' => [
                        ['name' => '$email', 'description' => 'Subsciber\'s email'],
                    ],
                    'returns' => 'All subscribers with the email',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{email}</redbold>', str_replace('-LIST_ID-', '<redbold>{list_uid}</redbold>', action("Api\SubscriberController@showByEmail", ['email' => '-ID-']))).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'PATCH',
                    'uri' => '/api/v1/lists/{list_uid}/subscribers/{uid}/subscribe',
                    'description' => 'Subscribe a subscriber',
                    'parameters' => [
                        ['name' => '$list_uid', 'description' => 'List\'s uid'],
                        ['name' => '$uid', 'description' => 'Subsciber\'s uid'],
                    ],
                    'returns' => 'Result messages in json',
                    'example' => 'curl -X PATCH -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', str_replace('-LIST_ID-', '<redbold>{list_uid}</redbold>', action("Api\SubscriberController@subscribe", [
    'list_uid' => '-LIST_ID-',
    'uid' => '-ID-',
]))).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'PATCH',
                    'uri' => '/api/v1/lists/{list_uid}/subscribers/{uid}/unsubscribe',
                    'description' => 'Unsubscribe a subscriber',
                    'parameters' => [
                        ['name' => '$list_uid', 'description' => 'List\'s uid'],
                        ['name' => '$uid', 'description' => 'Subsciber\'s uid'],
                    ],
                    'returns' => 'Result messages in json',
                    'example' => 'curl -X PATCH -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', str_replace('-LIST_ID-', '<redbold>{list_uid}</redbold>', action("Api\SubscriberController@unsubscribe", [
    'list_uid' => '-LIST_ID-',
    'uid' => '-ID-',
]))).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'DELETE',
                    'uri' => '/api/v1/subscribers/{uid}',
                    'description' => 'Delete a subscriber',
                    'parameters' => [
                        ['name' => '$list_uid', 'description' => 'List\'s uid'],
                        ['name' => '$uid', 'description' => 'Subsciber\'s uid'],
                    ],
                    'returns' => 'Result messages in json',
                    'example' => 'curl -X DELETE -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', str_replace('-LIST_ID-', '<redbold>{list_uid}</redbold>', action("Api\SubscriberController@delete", ['uid' => '-ID-']))).' \
-d api_token=' .\Auth::user()->api_token,
                ],
            ],
        ];

        // AUTHENTICATION
        $docs[] = [
            'view' => 'backend',
            'title' => trans('messages.authentication'),
            'functions' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/login-token',
                    'description' => 'Generate one time login token',
                    'help' => 'User can login by visiting the following URL:<br>
                        <code>' . action('Controller@tokenLogin', ['token' => '*|token_string|*']) . '</code>',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Customer\'s uid'],
                    ],
                    'returns' => 'Token string',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .str_replace('-uid-', '<redbold>{uid}</redbold>', action("Api\CustomerController@loginToken")).' \
-d api_token=' .\Auth::user()->api_token.' \
-d customer_uid=600684b234b98',
                ],
            ],
        ];

        // PLAN
        $docs[] = [
            'view' => 'backend',
            'title' => trans('messages.plan'),
            'functions' => [
                [
                    'method' => 'GET',
                    'uri' => '/api/v1/plans',
                    'description' => 'Get information about all plans',
                    'parameters' => [],
                    'returns' => 'List of all plans in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .action("Api\PlanController@index").' \
-d api_token=' .\Auth::user()->api_token,
                ],
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/plans',
                    'description' => 'Add new plan',
                    'parameters' => [
                        ['name' => '$name', 'description' => 'Plan\'s name'],
                        ['name' => '$currency_id', 'description' => 'Currency\'s id'],
                        ['name' => '$frequency_amount', 'description' => 'Billing recurs every this amount of time'],
                        ['name' => '$frequency_unit', 'description' => 'Time unit for billing recurs (day, week, month, year, unlimited)'],
                        ['name' => '$price', 'description' => 'Plan\'s price'],
                        ['name' => '$color', 'description' => 'Plan\'s color (red, blue, #008c6e, #917319,...)'],
                        ['name' => '$options[...]', 'description' => 'Plan\'s options...<br>
                            <ul>
                                <li><span class="text-semibold">email_max</span>: '.trans('messages.max_emails').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">list_max</span>: '.trans('messages.max_lists').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">subscriber_max</span>: '.trans('messages.max_subscribers').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">subscriber_per_list_max</span>: '.trans('messages.max_subscribers_per_list').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">segment_per_list_max</span>: '.trans('messages.segment_per_list_max').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">campaign_max</span>: '.trans('messages.max_campaigns').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">automation_max</span>: '.trans('messages.max_automations').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">sending_quota</span>: '.trans('messages.sending_quota').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">sending_quota_time</span>: '.trans('messages.quota_time').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">sending_quota_time_unit</span>: '.trans('messages.quota_time_unit').' (day|hour|minute)</li>
                                <li><span class="text-semibold">max_process</span>: '.trans('messages.max_number_of_processes').' (number)</li>
                                <li><span class="text-semibold">all_sending_servers</span>: '.trans('messages.use_all_sending_servers').' (yes|no)</li>
                                <li><span class="text-semibold">max_size_upload_total</span>: '.trans('messages.max_size_upload_total').' (number)</li>
                                <li><span class="text-semibold">max_file_size_upload</span>: '.trans('messages.max_file_size_upload').' (number)</li>
                                <li><span class="text-semibold">unsubscribe_url_required</span>: '.trans('messages.unsubscribe_url_required').' (yes|no)</li>
                                <li><span class="text-semibold">access_when_offline</span>: '.trans('messages.access_when_offline').' (yes|no)</li>
                                <li><span class="text-semibold">create_sending_domains</span>: '.trans('messages.allow_customer_create_sending_domains').' (yes|no)</li>
                                <li><span class="text-semibold">sending_servers_max</span>: '.trans('messages.max_sending_servers').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">sending_domains_max</span>: '.trans('messages.max_sending_domains').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">all_email_verification_servers</span>: '.trans('messages.use_all_email_verification_servers').' (yes|no)</li>
                                <li><span class="text-semibold">create_email_verification_servers</span>: '.trans('messages.allow_customer_create_email_verification_servers').' (yes|no)</li>
                                <li><span class="text-semibold">email_verification_servers_max</span>: '.trans('messages.max_email_verification_servers').' (number, -1 for unlimited)</li>
                                <li><span class="text-semibold">list_import</span>: '.trans('messages.can_import_list').' (yes|no)</li>
                                <li><span class="text-semibold">list_export</span>: '.trans('messages.can_export_list').' (yes|no)</li>
                                <li><span class="text-semibold">all_sending_server_types</span>: '.trans('messages.allow_adding_all_sending_server_types').' (yes|no)</li>
                                <li><span class="text-semibold">sending_server_types</span>: (array)</li>
                                <li><span class="text-semibold">sending_server_option</span>: ('.\Acelle\Model\Plan::SENDING_SERVER_OPTION_SYSTEM.'|'.\Acelle\Model\Plan::SENDING_SERVER_OPTION_OWN.'|'.\Acelle\Model\Plan::SENDING_SERVER_OPTION_SUBACCOUNT.')</li>
                                <li><span class="text-semibold">sending_server_subaccount_uid</span></li>
                            </ul>
                        ', 'optional' => true],
                    ],
                    'returns' => 'Creation messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .action("Api\PlanController@store").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d name=Advanced \
-d currency_id=' .\Acelle\Model\Currency::first()->id.' \
-d frequency_amount=1 \
-d frequency_unit=month \
-d price=20 \
-d color=red \
-d options[sending_server_option]=' .\Acelle\Model\Plan::SENDING_SERVER_OPTION_OWN.' \
-d options[email_max]=10000',
                ],
            ],
        ];

        // SENDING SERVER
        $docs[] = [
            'view' => 'backend',
            'title' => trans('messages.SENDING_SERVER'),
            'functions' => [
                [
                    'method' => 'GET',
                    'uri' => '/api/v1/sending_servers',
                    'description' => 'Get information about all sending servers',
                    'parameters' => [],
                    'returns' => 'List of all sending servers in json',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .action("Api\SendingServerController@index").' \
-d api_token=' .\Auth::user()->api_token,
                ],
            ],
        ];

        // CUSTOMER
        $docs[] = [
            'view' => 'backend',
            'title' => trans('messages.customer'),
            'functions' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/customers',
                    'description' => 'Add new customer',
                    'parameters' => [
                    ],
                    'returns' => 'Creation messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .action("Api\CustomerController@store").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d email=user_name@gmail.com \
-d first_name=Luan \
-d last_name=Pham \
-d timezone=America/Godthab \
-d language_id=1 \
-d password=123456',
                ],

                [
                    'view' => 'backend',
                    'method' => 'GET',
                    'uri' => '/api/v1/customers/{uid}',
                    'description' => 'Get information about a specific customer',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Customer\'s uid'],
                    ],
                    'returns' => 'Customer information in json (profile, contact, sunscriptions,... )',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\CustomerController@update", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'PATCH',
                    'uri' => '/api/v1/customers',
                    'description' => 'Update customer',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Customer\'s uid'],
                    ],
                    'returns' => 'Creation messages in json',
                    'example' => 'curl -X PATCH -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\CustomerController@update", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token.'  \
-d email=user_name@gmail.com \
-d first_name=Luan \
-d last_name=Pham \
-d timezone=America/Godthab \
-d language_id=1 \
-d password=123456',
                ],

                [
                    'method' => 'PATCH',
                    'uri' => '/api/v1/customers/{uid}/enable',
                    'description' => 'Enable customer',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Customer\'s uid'],
                    ],
                    'returns' => 'Action messages in json',
                    'example' => 'curl -X PATCH -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\CustomerController@enable", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'PATCH',
                    'uri' => '/api/v1/customers/{uid}/disable',
                    'description' => 'Disable customer',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Customer\'s uid'],
                    ],
                    'returns' => 'Action messages in json',
                    'example' => 'curl -X PATCH -H "accept:application/json" -G \
' .str_replace('-ID-', '<redbold>{uid}</redbold>', action("Api\CustomerController@disable", '-ID-')).' \
-d api_token=' .\Auth::user()->api_token,
                ],

                [
                    'method' => 'POST',
                    'uri' => '/api/v1/customers/{uid}/assign-plan/{plan_uid}',
                    'description' => 'Assign Plan to Customer',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Customer\'s uid'],
                        ['name' => '$plan_uid', 'description' => 'Plan\'s uid'],
                        ['name' => '$disable_billing', 'description' => 'Activate the subscription without checking out. Default: false'],
                    ],
                    'returns' => 'Action messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .str_replace('-plan_uid-', '<redbold>{plan_uid}</redbold>', str_replace('-uid-', '<redbold>{uid}</redbold>', action("Api\CustomerController@assignPlan", ['uid' => '-uid-', 'plan_uid' => '-plan_uid-']))).' \
-d api_token=' .\Auth::user()->api_token,
                ],
            ],
        ];

        // SUBSCRIPTION
        $docs[] = [
            'view' => 'backend',
            'title' => trans('messages.subscription'),
            'functions' => [
                [
                    'view' => 'backend',
                    'method' => 'GET',
                    'uri' => '/api/v1/subscriptions',
                    'description' => 'Get information about all subscriptions',
                    'parameters' => [
                        ['name' => '$per_page', 'description' => 'Number of subscriptions per page', 'optional' => true, 'default' => 25],
                        ['name' => '$page', 'description' => 'Page number'],
                    ],
                    'returns' => 'Subscriptions information in json (plan, customer,... )',
                    'example' => 'curl -X GET -H "accept:application/json" -G \
' .action("Api\SubscriptionController@index").' \
-d api_token=' .\Auth::user()->api_token.' \
-d page=1',
                ],

                [
                    'method' => 'POST',
                    'uri' => '/api/v1/customers/{uid}/assign-plan/{plan_uid}',
                    'description' => 'Assign Plan to Customer',
                    'parameters' => [
                        ['name' => '$uid', 'description' => 'Customer\'s uid'],
                        ['name' => '$plan_uid', 'description' => 'Plan\'s uid'],
                        ['name' => '$disable_billing', 'description' => 'Activate the subscription without checking out. Default: false'],
                    ],
                    'returns' => 'Action messages in json',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .str_replace('-plan_uid-', '<redbold>{plan_uid}</redbold>', str_replace('-uid-', '<redbold>{uid}</redbold>', action("Api\CustomerController@assignPlan", ['uid' => '-uid-', 'plan_uid' => '-plan_uid-']))).' \
-d api_token=' .\Auth::user()->api_token,
                ],
            ],
        ];

        // NOTIFICATION
        $docs[] = [
            'view' => 'backend',
            'title' => trans('messages.api.notification'),
            'functions' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/notification',
                    'description' => 'Send a delivery or abuse report to the application. It could be a success / bounce / feedback or abuse report',
                    'parameters' => [
                        ['name' => '$message_id', 'description' => 'Message\'s id'],
                        ['name' => '$type', 'description' => 'One of 4 types: sent | bounced | reported | failed'],
                        ['name' => '$bounce_type', 'description' => 'Required if type is bounced'],
                        ['name' => '$report_type', 'description' => 'Required if type is reported'],
                        ['name' => '$description', 'description' => 'Notification message'],
                    ],
                    'returns' => 'Action messages in json',
                    'example' => [
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=sent',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=bounced \
-d bounce_type=hard \
-d description=Email+address+does+not+exist',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=abuse \
-d report_type=hard \
-d description=Email+address+does+not+exist',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=spam \
-d report_type=hard \
-d description=Email+address+does+not+exist',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=failed \
-d description=Email+address+does+not+exist',
                    ],
                ],
            ],
        ];

        // NOTIFICATION
        $docs[] = [
            'view' => 'frontend',
            'title' => trans('messages.api.notification'),
            'functions' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/notification',
                    'description' => 'Send a delivery or abuse report to the application. It could be a success / bounce / feedback or abuse report',
                    'parameters' => [
                        ['name' => '$message_id', 'description' => 'Message\'s id'],
                        ['name' => '$type', 'description' => 'One of 4 types: sent | bounced | reported | failed'],
                        ['name' => '$bounce_type', 'description' => 'Required if type is bounced'],
                        ['name' => '$report_type', 'description' => 'Required if type is reported'],
                        ['name' => '$description', 'description' => 'Notification message'],
                    ],
                    'returns' => 'Action messages in json',
                    'example' => [
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=sent',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=bounced \
-d bounce_type=hard \
-d description=Email+address+does+not+exist',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=abuse \
-d report_type=hard \
-d description=Email+address+does+not+exist',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=spam \
-d report_type=hard \
-d description=Email+address+does+not+exist',
                        'curl -X POST -H "accept:application/json" -G \
' .action("Api\NotificationController@index").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d message_id=201637442604422402.6199642caf7f3@example.com  \
-d type=failed \
-d description=Email+address+does+not+exist',
                    ],
                ],
            ],
        ];

        // File
        $docs[] = [
            'view' => 'frontend',
            'title' => trans('messages.file'),
            'functions' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/v1/file/upload',
                    'description' => 'Upload file(s) to customer\'s storage',
                    'parameters' => [
                        ['name' => '$file_url', 'description' => 'File url'],
                        ['name' => '$subdirectory', 'description' => 'Custom subdirectory', 'optional' => true, 'default' => 'user root directory'],
                    ],
                    'returns' => 'Upload result message',
                    'example' => 'curl -X POST -H "accept:application/json" -G \
' .action("Api\FileController@upload").' \
-d api_token=' .\Auth::user()->api_token.'  \
-d files=\'[{"url":"http://demo.acellemail.com/images/logo_big.svg","subdirectory":"path/to/file"},{"url":"http://demo.acellemail.com/images/logo_big.svg","subdirectory":"path/to/file2"}]\'',
                ],
            ],
        ];

        return $docs;
    }
}
