<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'options', 'permissions',
    ];

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('*');
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
    );

    public function creator()
    {
        return $this->belongsTo('Acelle\Model\User', 'creator_id');
    }

    /**
     * Rules.
     *
     * @return array
     */
    public static function rules()
    {
        $rules = [
            'name' => 'required',
        ];

        $options = self::defaultOptions();
        foreach ($options as $type => $option) {
            $rules['options.'.$type] = 'required';
        }

        $permissions = self::defaultPermissions();
        foreach ($permissions as $type => $value) {
            $rules['permissions.'.$type] = 'required';
        }

        return $rules;
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function admins()
    {
        return $this->hasMany('Acelle\Model\Admin');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('admin_groups.*');

        // Keyword
        if (!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%'.$request->keyword.'%');
        }

        if (!empty($request->creator_id)) {
            $query = $query->where('admin_groups.creator_id', '=', $request->creator_id);
        }

        return $query;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = self::filter($request);

        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions($admin = null)
    {
        $query = self::getAll();

        if ($admin && !$admin->can('readAll', new self())) {
            $query = $query->where('admin_groups.creator_id', '=', $admin->user_id);
        }

        $options = $query->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Default options for new groups.
     *
     * @return array
     */
    public static function defaultOptions()
    {
        return [
        ];
    }

    /**
     * Default permissions for new groups.
     *
     * @return array
     */
    public static function defaultPermissions()
    {
        return [
            'admin_group_read' => 'all',
            'admin_group_create' => 'yes',
            'admin_group_update' => 'all',
            'admin_group_delete' => 'own',
            'admin_read' => 'own',
            'admin_create' => 'yes',
            'admin_update' => 'own',
            'admin_delete' => 'own',
            'admin_login_as' => 'own',
            'customer_read' => 'own',
            'customer_create' => 'yes',
            'customer_update' => 'own',
            'customer_delete' => 'own',
            'customer_login_as' => 'own',
            'subscription_read' => 'own',
            'subscription_create' => 'yes',
            'subscription_update' => 'own',
            'subscription_delete' => 'own',
            'subscription_disable' => 'own',
            'subscription_enable' => 'own',
            'subscription_paid' => 'own',
            'subscription_unpaid' => 'own',
            'plan_read' => 'own',
            'plan_create' => 'yes',
            'plan_update' => 'own',
            'plan_delete' => 'own',
            'plan_copy' => 'all',
            'payment_method_read' => 'yes',
            'payment_method_update' => 'yes',
            'sending_server_read' => 'all',
            'sending_server_create' => 'yes',
            'sending_server_update' => 'own',
            'sending_server_delete' => 'own',
            'bounce_handler_read' => 'own',
            'bounce_handler_create' => 'yes',
            'bounce_handler_update' => 'own',
            'bounce_handler_delete' => 'own',
            'fbl_handler_read' => 'own',
            'fbl_handler_create' => 'yes',
            'fbl_handler_update' => 'own',
            'fbl_handler_delete' => 'own',
            'sending_domain_read' => 'own',
            'sending_domain_create' => 'yes',
            'sending_domain_update' => 'own',
            'sending_domain_delete' => 'own',
            'email_verification_server_read' => 'all',
            'email_verification_server_create' => 'yes',
            'email_verification_server_update' => 'own',
            'email_verification_server_delete' => 'own',
            'template_read' => 'own',
            'template_create' => 'yes',
            'template_update' => 'own',
            'template_delete' => 'own',
            'layout_read' => 'yes',
            'layout_update' => 'yes',
            'setting_general' => 'yes',
            'setting_sending' => 'yes',
            'setting_system_urls' => 'yes',
            'setting_access_when_offline' => 'yes',
            'setting_background_job' => 'yes',
            'setting_upgrade_manager' => 'no',
            'language_read' => 'yes',
            'language_create' => 'yes',
            'language_update' => 'yes',
            'language_delete' => 'yes',
            'currency_read' => 'own',
            'currency_create' => 'yes',
            'currency_update' => 'own',
            'currency_delete' => 'own',
            'report_blacklist' => 'yes',
            'report_tracking_log' => 'yes',
            'report_bounce_log' => 'yes',
            'report_feedback_log' => 'yes',
            'report_click_log' => 'yes',
            'report_open_log' => 'yes',
            'report_unsubscribe_log' => 'yes',
        ];
    }

    /**
     * Backend roles.
     *
     * @return array
     */
    public static function allPermissions()
    {
        return [
            'admin_group' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'admin' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'login_as' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'customer' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'login_as' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'subscription' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'disable' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'enable' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'paid' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'unpaid' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'plan' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'copy' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'payment_method' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
            'sending_server' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'bounce_handler' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'fbl_handler' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'sending_domain' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'email_verification_server' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'template' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.yes')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'layout' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
            'setting' => [
                'general' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'sending' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'system_urls' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'access_when_offline' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'background_job' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'upgrade_manager' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
            'language' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
            'currency' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'report' => [
                'blacklist' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'tracking_log' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'bounce_log' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'feedback_log' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'open_log' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'click_log' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'unsubscribe_log' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->options)) {
            return self::defaultOptions();
        } else {
            $defaul_options = self::defaultOptions();
            $saved_options = json_decode($this->options, true);
            foreach ($defaul_options as $x => $group) {
                if (isset($saved_options[$x])) {
                    $defaul_options[$x] = $saved_options[$x];
                }
            }

            return $defaul_options;
        }
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getPermissions()
    {
        if (empty($this->permissions)) {
            return self::defaultPermissions();
        } else {
            $defaul_permissions = self::defaultPermissions();
            $saved_permissions = json_decode($this->permissions, true);
            foreach ($defaul_permissions as $x => $permission) {
                if (isset($saved_permissions[$x])) {
                    $defaul_permissions[$x] = $saved_permissions[$x];
                }
            }

            return $defaul_permissions;
        }
    }

    /**
     * Get option.
     *
     * @return string
     */
    public function getOption($name)
    {
        return $this->getOptions()[$name];
    }

    /**
     * Get permissions.
     *
     * @return string
     */
    public function getPermission($name)
    {
        return $this->getPermissions()[$name];
    }

    /**
     * Save options.
     *
     * @return array
     */
    public function saveOptions($options)
    {
        return true;
    }

    /**
     * Quota time unit options.
     *
     * @return array
     */
    public static function timeUnitOptions()
    {
        return [
            ['value' => 'minute', 'text' => trans('messages.minute')],
            ['value' => 'hour', 'text' => trans('messages.hour')],
            ['value' => 'day', 'text' => trans('messages.day')],
            ['value' => 'week', 'text' => trans('messages.week')],
            ['value' => 'month', 'text' => trans('messages.month')],
            ['value' => 'year', 'text' => trans('messages.year')],
        ];
    }

    /**
     * Get sending servers ids.
     *
     * @return array
     */
    public function getSendingServerIds()
    {
        $arr = [];
        foreach ($this->sending_servers as $server) {
            $arr[] = $server->uid;
        }

        return $arr;
    }
}
