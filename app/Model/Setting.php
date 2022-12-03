<?php

/**
 * Setting class.
 *
 * Model class for applications settings
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
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

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Cashier\Cashier;
use Acelle\Model\Plugin;
use Acelle\Library\Facades\Billing;

class Setting extends Model
{
    public const UPLOAD_PATH = 'app/setting/';

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        $settings = self::select('*')->get();
        $result = self::defaultSettings();

        foreach ($settings as $setting) {
            $result[$setting->name]['value'] = $setting->value;
        }

        return $result;
    }

    /**
     * Get setting.
     *
     * @return object
     */
    public static function get($name, $defaultValue=null)
    {
        $setting = self::where('name', $name)->first();

        if (is_object($setting)) {
            return $setting->value;
        } elseif (isset(self::defaultSettings()[$name])) {
            return self::defaultSettings()[$name]['value'];
        } else {
            // @todo exception case not handled
            return $defaultValue;
        }
    }

    /**
     * Check setting EQUAL.
     *
     * @return object
     */
    public static function isYes($key)
    {
        return self::get($key) == 'yes';
    }

    /**
     * Set YES.
     *
     * @return object
     */
    public static function setYes($key)
    {
        return self::set($key, 'yes');
    }

    /**
     * Set setting value.
     *
     * @return object
     */
    public static function set($name, $val)
    {
        $option = self::where('name', $name)->first();

        if (is_object($option)) {
            $option->value = $val;
        } else {
            $option = new self();
            $option->name = $name;
            $option->value = $val;
        }
        $option->save();

        return $option;
    }

    /**
     * Get setting rules.
     *
     * @return object
     */
    public static function rules()
    {
        $rules = [];
        $settings = self::getAll();

        foreach ($settings as $name => $setting) {
            if (!isset($setting['not_required'])) {
                $rules[$name] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Default setting.
     *
     * @return object
     */
    public static function defaultSettings()
    {
        return [
            'site_name' => [
                'cat' => 'general',
                'value' => 'Email Marketing Application',
                'type' => 'text',
            ],
            'site_keyword' => [
                'cat' => 'general',
                'value' => 'Email Marketing, Campaigns, Lists',
                'type' => 'text',
            ],
            'site_logo_small' => [
                'cat' => 'general',
                'value' => '',
                'type' => 'image',
            ],
            'site_logo_big' => [
                'cat' => 'general',
                'value' => '',
                'type' => 'image',
            ],
            'site_favicon' => [
                'cat' => 'general',
                'value' => '',
                'type' => 'image',
            ],
            'license' => [
                'cat' => 'license',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'license_type' => [
                'cat' => 'system',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'site_online' => [
                'cat' => 'general',
                'value' => 'true',
                'type' => 'checkbox',
                'options' => [
                    'false', 'true',
                ],
            ],
            'site_offline_message' => [
                'cat' => 'general',
                'value' => 'Application currently offline. We will come back soon!',
                'type' => 'textarea',
            ],
            'site_description' => [
                'cat' => 'general',
                'value' => 'Makes it easy for you to create, send, and optimize your email marketing campaigns.',
                'type' => 'textarea',
            ],
            'default_language' => [
                'cat' => 'general',
                'value' => 'en',
                'type' => 'select',
                'options' => \Acelle\Model\Language::getSelectOptions(),
            ],
            'frontend_scheme' => [
                'cat' => 'general',
                'value' => 'default',
                'type' => 'select',
                'options' => self::colors(),
            ],
            'backend_scheme' => [
                'cat' => 'general',
                'value' => 'default',
                'type' => 'select',
                'options' => self::colors(),
            ],
            'captcha_engine' => [
                'cat' => 'general',
                'value' => 'recaptcha',
                'type' => 'select',
                'options' => array_map(function ($cap) {
                    return ['value' => $cap['id'], 'text' => $cap['title']];
                }, \Acelle\Library\Facades\Hook::execute('captcha_method')),
            ],
            'login_recaptcha' => [
                'cat' => 'general',
                'value' => 'no',
                'type' => 'checkbox',
                'options' => ['no', 'yes'],
            ],
            'embedded_form_recaptcha' => [
                'cat' => 'general',
                'value' => 'no',
                'type' => 'checkbox',
                'options' => ['no', 'yes'],
            ],
            'enable_user_registration' => [
                'cat' => 'general',
                'value' => 'yes',
                'type' => 'checkbox',
                'options' => ['no', 'yes'],
            ],
            'registration_recaptcha' => [
                'cat' => 'general',
                'value' => 'yes',
                'type' => 'checkbox',
                'options' => ['no', 'yes'],
            ],
            'custom_script' => [
                'cat' => 'general',
                'value' => '',
                'type' => 'textarea',
                'not_required' => 'yes',
            ],
            'builder' => [
                'cat' => 'general',
                'value' => 'both',
                'type' => 'select',
                'options' => self::builderOptions(),
            ],
            'import_subscribers_commitment' => [
                'cat' => 'others',
                'value' => null,
                'type' => 'textarea',
            ],
            'sending_campaigns_at_once' => [
                'cat' => 'sending',
                'value' => '10',
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_change_server_time' => [
                'cat' => 'sending',
                'value' => '300',
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_emails_per_minute' => [
                'cat' => 'sending',
                'value' => '150',
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_pause' => [
                'cat' => 'sending',
                'value' => '10',
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_at_once' => [
                'cat' => 'sending',
                'value' => '50',
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_subscribers_at_once' => [
                'cat' => 'sending',
                'value' => '100',
                'type' => 'text',
                'class' => 'numeric',
            ],
            'url_unsubscribe' => [
                'cat' => 'url',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'url_open_track' => [
                'cat' => 'url',
                'value' => '', // action('CampaignController@open', ["message_id" => trans("messages.MESSAGE_ID")]),
                'type' => 'text',
                'not_required' => true,
            ],
            'url_click_track' => [
                'cat' => 'url',
                'value' => '', // action('CampaignController@click', ["message_id" => trans("messages.MESSAGE_ID"), "url" => trans("messages.URL")]),
                'type' => 'text',
                'not_required' => true,
            ],
            'url_delivery_handler' => [
                'cat' => 'url',
                'value' => '', // action('DeliveryController@notify'),
                'type' => 'text',
                'not_required' => true,
            ],
            'url_update_profile' => [
                'cat' => 'url',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'url_web_view' => [
                'cat' => 'url',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'php_bin_path' => [
                'cat' => 'cronjob',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'remote_job_token' => [
                'cat' => 'cronjob',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'cronjob_last_execution' => [
                'cat' => 'monitor',
                'value' => 0,
                'type' => 'text',
                'not_required' => true,
            ],
            'cronjob_min_interval' => [
                'cat' => 'monitor',
                'value' => '15 minutes',
                'type' => 'text',
                'not_required' => true,
            ],
            'spf_record' => [
                'cat' => 'dns',
                'value' => null,
                'type' => 'text',
                'not_required' => true,
            ],
            'spf_host' => [
                'cat' => 'dns',
                'value' => null,
                'type' => 'text',
                'not_required' => true,
            ],
            'verification_hostname' => [
                'cat' => 'dns',
                'value' => 'emarketing',
                'type' => 'text',
                'not_required' => true,
            ],
            'dkim_selector' => [
                'cat' => 'dns',
                'value' => 'mailer',
                'type' => 'text',
                'not_required' => true,
            ],
            'allow_send_from_unverified_domain' => [
                'cat' => 'others',
                'value' => 'yes',
                'type' => 'text',
                'not_required' => true,
            ],
            'allow_turning_off_dkim_signing' => [
                'cat' => 'others',
                'value' => 'yes',
                'type' => 'text',
                'not_required' => true,
            ],
            'escape_dkim_dns_value' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'verify_subscriber_email' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'send_notification_email_for_list_subscription' => [
                'cat' => 'others',
                'value' => null,
                'type' => 'text',
                'not_required' => true,
            ],
            'aws_verification_server' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'geoip.engine' => [
                'cat' => 'others',
                'value' => 'sqlite', # available values are sqlite|nekudo|mysql
                'type' => 'text',
                'not_required' => true,
            ],
            'geoip.enabled' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'geoip.last_message' => [
                'cat' => 'others',
                'value' => null,
                'type' => 'text',
                'not_required' => true,
            ],
            'geoip.sqlite.dbname' => [
                'cat' => 'others',
                'value' => 'storage/app/GeoLite2-City.mmdb',
                'type' => 'text',
                'not_required' => true,
            ],
            'geoip.sqlite.source_url' => [
                'cat' => 'others',
                'value' => 'https://acellemail.s3.amazonaws.com/GeoLite2-City.mmdb',
                'type' => 'text',
                'not_required' => true,
            ],
            'geoip.sqlite.source_hash' => [
                'cat' => 'others',
                'value' => 'b95ecaff82017c4f52577196c41db946',
                'type' => 'text',
                'not_required' => true,
            ],
            'delivery.sendmail' => [
                'cat' => 'others',
                'value' => 'yes',
                'type' => 'text',
                'not_required' => true,
            ],
            'delivery.phpmail' => [
                'cat' => 'others',
                'value' => 'yes',
                'type' => 'text',
                'not_required' => true,
            ],
            'end_period_last_days' => [
                'cat' => 'payment',
                'value' => '7',
                'type' => 'text',
            ],
            'renew_free_plan' => [
                'cat' => 'payment',
                'value' => 'no',
                'type' => 'text',
            ],
            'theme.beta' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'spamassassin.command' => [
                'cat' => 'others',
                'value' => 'spamc -R',
                'type' => 'text',
                'not_required' => true,
            ],
            'spamassassin.required' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'spamassassin.enabled' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'mta.api_endpoint' => [
                'cat' => 'others',
                'value' => null,
                'type' => 'text',
                'not_required' => true,
            ],
            'mta.api_key' => [
                'cat' => 'others',
                'value' => null,
                'type' => 'text',
                'not_required' => true,
            ],
            'storage.s3' => [
                'cat' => 'others',
                'value' => null,
                'type' => 'text',
                'not_required' => true,
            ],
            'rss.enabled' => [
                'cat' => 'others',
                'value' => 'yes',
                'type' => 'text',
                'not_required' => true,
            ],
            'list.clone_for_others' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'gateways' => [
                'cat' => 'others',
                'value' => '["direct"]',
                'type' => 'array',
                'not_required' => true,
            ],
            'automation.trigger_imported_contacts' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'campaign.bcc' => [
                'cat' => 'others',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'campaign.cc' => [
                'cat' => 'others',
                'value' => '',
                'type' => 'text',
                'not_required' => true,
            ],
            'list.allow_single_optin' => [
                'cat' => 'others',
                'value' => 'yes',
                'type' => 'text',
                'not_required' => true,
            ],
            'campaign.enforce_unsubscribe_url_check' => [
                'cat' => 'others',
                'value' => 'no',
                'type' => 'text',
                'not_required' => true,
            ],
            'layout.menu_bar' => [
                'cat' => 'others',
                'value' => 'left',
                'type' => 'text',
                'not_required' => true,
            ],
            'invoice.current' => [
                'cat' => 'general',
                'value' => '1',
                'type' => 'number',
            ],
            'invoice.format' => [
                'cat' => 'general',
                'value' => '%08d',  // a number of 8 digit, for example: sprintf('%08d', 15) -> 00000015
                'type' => 'text',
            ],
        ];
    }

    /**
     * Color array.
     *
     * @return array
     */
    public static function colors()
    {
        return [
            ['value' => 'default', 'text' => trans('messages.default')],
            ['value' => 'blue', 'text' => trans('messages.blue')],
            ['value' => 'green', 'text' => trans('messages.green')],
            ['value' => 'brown', 'text' => trans('messages.brown')],
            ['value' => 'pink', 'text' => trans('messages.pink')],
            ['value' => 'grey', 'text' => trans('messages.grey')],
            // ['value' => 'white', 'text' => trans('messages.white')],
        ];
    }

    /**
     * Color array.
     *
     * @return array
     */
    public static function builderOptions()
    {
        return [
            ['value' => 'both', 'text' => trans('messages.builder.both')],
            ['value' => 'pro', 'text' => trans('messages.builder.pro')],
            ['value' => 'classic', 'text' => trans('messages.builder.classic')],
        ];
    }

    /**
     * Update license type.
     *
     * @return array
     */
    public static function updateLicense($license)
    {
        if (empty($license)) {
            self::set('license', '');
            self::set('license_type', '');
        } else {
            $license_type = \Acelle\Helpers\LicenseHelper::getLicenseType($license);
            self::set('license', $license);
            self::set('license_type', $license_type);
        }
    }

    /**
     * Upload site logo.
     *
     * @var bool
     */
    public static function uploadSiteLogo($file, $name = null)
    {
        $path = 'images/';
        $upload_path = public_path($path);

        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $md5file = \md5_file($file);

        $filename = $md5file.'.'.$file->getClientOriginalExtension();

        // save to server
        $file->move($upload_path, $filename);

        // create thumbnails
        $img = \Image::make($upload_path.$filename);

        self::set($name, $path.$filename);

        return true;
    }

    /**
     * Upload site logo.
     *
     * @var bool
     */
    public static function uploadFile($file, $type = null, $thumbnail = true)
    {
        $uploadPath = storage_path(self::UPLOAD_PATH);

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $md5file = \md5_file($file);

        $filename = $type.'-'.$md5file.'.'.$file->getClientOriginalExtension();

        // save to server
        $file->move($uploadPath, $filename);

        // create thumbnails
        if ($thumbnail) {
            $img = \Image::make($uploadPath.$filename);
        }

        self::set($type, $filename);

        return true;
    }

    /**
     * gET uploaded file location.
     *
     * @var bool
     */
    public static function getUploadFilePath($filename)
    {
        $uploadPath = storage_path(self::UPLOAD_PATH);

        return $uploadPath.$filename;
    }

    /**
     * Write default settings to DB.
     *
     * @var bool
     */
    public static function writeDefaultSettings()
    {
        foreach (self::defaultSettings() as $name => $setting) {
            if (!self::where('name', $name)->exists()) {
                $value = (is_null($setting['value'])) ? '' : $setting['value'];

                $setting = new self();
                $setting->name = $name;
                $setting->value = $value;
                $setting->save();
            }
        }
    }

    public static function getTaxSettings()
    {
        if (self::get('tax') == null) {
            return [
                'enabled' => 'no',
                'default_rate' => 10,
                'countries' => [],
            ];
        }

        return json_decode(self::get('tax'), true);
    }

    public static function setTaxSettings($params)
    {
        $settings = self::getTaxSettings();
        $countries = $settings['countries'];

        if (isset($params['countries'])) {
            $countries = array_merge($countries, $params['countries']);
        }

        $settings = array_merge($settings, $params);
        $settings['countries'] = $countries;

        self::set('tax', json_encode($settings));
    }

    public static function getTaxByCountry($country=null)
    {
        if (self::getTaxSettings()['enabled'] !== 'yes') {
            return 0;
        }

        if ($country == null) {
            return self::getTaxSettings()['default_rate'];
        }

        $countries = self::getTaxSettings()['countries'];

        if (isset($countries[$country->code])) {
            return $countries[$country->code];
        } else {
            return self::getTaxSettings()['default_rate'];
        }
    }

    public static function removeTaxCountryByCode($code)
    {
        $settings = self::getTaxSettings();
        $countries = $settings['countries'];

        unset($countries[$code]);

        $settings['countries'] = $countries;

        self::set('tax', json_encode($settings));
    }

    public static function getCaptchaProvider()
    {
        $captcha = self::get('captcha_engine');

        if (in_array(
            $captcha,
            array_map(
                function ($cap) {
                    return $cap['id'];
                },
                \Acelle\Library\Facades\Hook::execute('captcha_method')
            )
        )
        ) {
            return $captcha;
        }

        return 'recaptcha';
    }
}
