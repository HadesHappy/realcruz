<?php

/**
 * Page class.
 *
 * Model class for Page
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
use Acelle\Library\Traits\HasUid;

class Page extends Model
{
    use HasUid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'subject', 'use_outside_url', 'outside_url',
    ];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function layout()
    {
        return $this->belongsTo('Acelle\Model\Layout');
    }

    /**
     * Find a page belong to list with layout.
     *
     * @param List   $list
     * @param string $layout
     *
     * @var Page
     */
    public static function findPage($list, $layout)
    {
        $page = $list->page($layout);
        if (!isset($page)) {
            $page = new \Acelle\Model\Page();
            $page->layout_id = $layout->id;
            $page->mail_list_id = $list->id;
            $page->content = $layout->content;
            $page->subject = $layout->subject;
        }

        return $page;
    }

    /**
     * Render content.
     *
     * @var html
     */
    public function renderContent($values = null, $subscriber = null)
    {
        // BAISC INFO
        $this->content = str_replace('{LIST_NAME}', $this->mailList->name, $this->content);
        $this->content = str_replace('{CONTACT_NAME}', $this->mailList->contact->company, $this->content);
        $this->content = str_replace('{CONTACT_STATE}', $this->mailList->contact->state, $this->content);
        $this->content = str_replace('{CONTACT_ADDRESS_1}', $this->mailList->contact->address_1, $this->content);
        $this->content = str_replace('{CONTACT_ADDRESS_2}', $this->mailList->contact->address_2, $this->content);
        $this->content = str_replace('{CONTACT_CITY}', $this->mailList->contact->city, $this->content);
        $this->content = str_replace('{CONTACT_ZIP}', $this->mailList->contact->zip, $this->content);
        if ($this->mailList->contact->country) {
            $this->content = str_replace('{CONTACT_COUNTRY}', $this->mailList->contact->country->name, $this->content);
        }
        $this->content = str_replace('{CONTACT_PHONE}', $this->mailList->contact->phone, $this->content);
        $this->content = str_replace('{CONTACT_EMAIL}', $this->mailList->contact->email, $this->content);
        $this->content = str_replace('{CONTACT_URL}', $this->mailList->contact->url, $this->content);

        // FIELDS
        $fields = view('subscribers._form', array('list' => $this->mailList, 'is_page' => true, 'col' => '12', 'values' => $values, 'email_readonly' => true))->render();
        $this->content = str_replace('{FIELDS}', $fields, $this->content);
        $this->content = str_replace('{SUBSCRIBE_BUTTON}', '<button class="btn btn-info bg-teal-800" type="submit">'.trans('messages.subscribe').' <span class="material-icons-round">
east
</span></button>', $this->content);
        $this->content = str_replace('{EMAIL_FIELD}', view('helpers.form_control', ['type' => 'text', 'name' => 'EMAIL', 'label' => trans('messages.email_address'), 'value' => '', 'rules' => $this->mailList->getFieldRules()])->render(), $this->content);
        $this->content = str_replace('{UNSUBSCRIBE_BUTTON}', '<button class="btn btn-info bg-teal-800" type="submit">'.trans('messages.unsubscribe').' <span class="material-icons-round">
east
</span></button>', $this->content);
        $this->content = str_replace('{UPDATE_PROFILE_BUTTON}', '<button class="btn btn-info bg-teal-800" type="submit">'.trans('messages.update_profile').' <span class="material-icons-round">
east
</span></button>', $this->content);
        $this->content = str_replace('{SUBSCRIBE_URL}', config('app.url').action('PageController@signUpForm', $this->mailList->uid, false), $this->content);

        // SUBSCRIBE CONFIRM URL
        if (isset($subscriber)) {
            // [SUBSCRIBE_CONFIRM_URL]
            $this->content = str_replace('{SUBSCRIBE_CONFIRM_URL}', config('app.url').action('PageController@signUpConfirmationThankyou', array('list_uid' => $this->mailList->uid, 'uid' => $subscriber->uid, 'code' => $subscriber->getSecurityToken('subscribe-confirm')), false), $this->content);

            // Summary
            $summary = view('subscribers._summary', ['list' => $this->mailList, 'subscriber' => $subscriber])->render();
            $this->content = str_replace('{SUBSCRIBER_SUMMARY}', $summary, $this->content);

            // UBSUBSCRIBE URL
            $this->content = str_replace('{UNSUBSCRIBE_URL}', config('app.url').action('PageController@unsubscribeForm', array('list_uid' => $this->mailList->uid, 'uid' => $subscriber->uid, 'code' => $subscriber->getSecurityToken('unsubscribe')), false), $this->content);

            $this->content = str_replace('{UPDATE_PROFILE_URL}', config('app.url').action('PageController@profileUpdateForm', array('list_uid' => $this->mailList->uid, 'uid' => $subscriber->uid, 'code' => $subscriber->getSecurityToken('update-profile')), false), $this->content);

            // LIST FIELDS
            foreach ($this->mailList->fields as $field) {
                $this->content = str_replace('{SUBSCRIBER_'.$field->tag.'}', $subscriber->getValueByField($field), $this->content);
            }
        } else {
            $this->content = str_replace('{SUBSCRIBE_CONFIRM_URL}', "<a href='http://domain.example/secure_token'>http://domain.example/secure_token</a>", $this->content);
            $this->content = str_replace('{UPDATE_PROFILE_URL}', 'http://domain.example/secure_token', $this->content);
            $this->content = str_replace('{UNSUBSCRIBE_URL}', 'http://domain.example/secure_token', $this->content);
            $this->content = str_replace('{SUBSCRIBER_SUMMARY}', '<p><strong>'.trans('messages.email').':</strong></p><p><strong>'.trans('messages.first_name').':</strong></p><p><strong>'.trans('messages.last_name').':</strong></p>', $this->content);
        }
    }

    /**
     * Render transformed subject.
     *
     * @var string
     */
    public function getTransformedSubject($subscriber)
    {
        // BAISC INFO
        $subject = $this->subject;
        $subject = str_replace('{LIST_NAME}', $this->mailList->name, $subject);
        $subject = str_replace('{CONTACT_NAME}', $this->mailList->contact->company, $subject);
        $subject = str_replace('{CONTACT_STATE}', $this->mailList->contact->state, $subject);
        $subject = str_replace('{CONTACT_ADDRESS_1}', $this->mailList->contact->address_1, $subject);
        $subject = str_replace('{CONTACT_ADDRESS_2}', $this->mailList->contact->address_2, $subject);
        $subject = str_replace('{CONTACT_CITY}', $this->mailList->contact->city, $subject);
        $subject = str_replace('{CONTACT_ZIP}', $this->mailList->contact->zip, $subject);
        if ($this->mailList->contact->country) {
            $this->content = str_replace('{CONTACT_COUNTRY}', $this->mailList->contact->country->name, $this->content);
        }
        $subject = str_replace('{CONTACT_PHONE}', $this->mailList->contact->phone, $subject);
        $subject = str_replace('{CONTACT_EMAIL}', $this->mailList->contact->email, $subject);
        $subject = str_replace('{CONTACT_URL}', $this->mailList->contact->url, $subject);

        // FIELDS
        $subject = str_replace('{SUBSCRIBE_BUTTON}', '<button class="btn btn-info bg-teal-800" type="submit">'.trans('messages.subscribe').' <span class="material-icons-round">
east
</span></button>', $subject);
        $subject = str_replace('{EMAIL_FIELD}', view('helpers.form_control', ['type' => 'text', 'name' => 'EMAIL', 'label' => trans('messages.email_address'), 'value' => '', 'rules' => $this->mailList->getFieldRules()])->render(), $subject);
        $subject = str_replace('{UNSUBSCRIBE_BUTTON}', '<button class="btn btn-info bg-teal-800" type="submit">'.trans('messages.unsubscribe').' <span class="material-icons-round">
east
</span></button>', $subject);
        $subject = str_replace('{UPDATE_PROFILE_BUTTON}', '<button class="btn btn-info bg-teal-800" type="submit">'.trans('messages.update_profile').' <span class="material-icons-round">
east
</span></button>', $subject);
        $subject = str_replace('{SUBSCRIBE_URL}', config('app.url').action('PageController@signUpForm', $this->mailList->uid, false), $subject);

        // SUBSCRIBE CONFIRM URL
        if (isset($subscriber)) {
            // [SUBSCRIBE_CONFIRM_URL]
            $subject = str_replace('{SUBSCRIBE_CONFIRM_URL}', config('app.url').action('PageController@signUpConfirmationThankyou', array('list_uid' => $this->mailList->uid, 'uid' => $subscriber->uid, 'code' => $subscriber->getSecurityToken('subscribe-confirm')), false), $subject);

            // Summary
            $summary = view('subscribers._summary', ['list' => $this->mailList, 'subscriber' => $subscriber])->render();
            $subject = str_replace('{SUBSCRIBER_SUMMARY}', $summary, $subject);

            // UBSUBSCRIBE URL
            $subject = str_replace('{UNSUBSCRIBE_URL}', config('app.url').action('PageController@unsubscribeForm', array('list_uid' => $this->mailList->uid, 'uid' => $subscriber->uid, 'code' => $subscriber->getSecurityToken('unsubscribe')), false), $subject);

            $subject = str_replace('{UPDATE_PROFILE_URL}', config('app.url').action('PageController@profileUpdateForm', array('list_uid' => $this->mailList->uid, 'uid' => $subscriber->uid, 'code' => $subscriber->getSecurityToken('update-profile')), false), $subject);

            // LIST FIELDS
            foreach ($this->mailList->fields as $field) {
                $subject = str_replace('{SUBSCRIBER_'.$field->tag.'}', $subscriber->getValueByField($field), $subject);
            }
        } else {
            $subject = str_replace('{SUBSCRIBE_CONFIRM_URL}', "<a href='http://domain.example/secure_token'>http://domain.example/secure_token</a>", $subject);
            $subject = str_replace('{UPDATE_PROFILE_URL}', 'http://domain.example/secure_token', $subject);
            $subject = str_replace('{UNSUBSCRIBE_URL}', 'http://domain.example/secure_token', $subject);
            $subject = str_replace('{SUBSCRIBER_SUMMARY}', '<p><strong>'.trans('messages.email').':</strong></p><p><strong>'.trans('messages.first_name').':</strong></p><p><strong>'.trans('messages.last_name').':</strong></p>', $subject);
        }

        return $subject;
    }

    /**
     * Add customer action log.
     */
    public function log($name, $customer, $add_datas = [])
    {
        $data = [
            'id' => $this->id,
            'alias' => $this->layout->alias,
            'list_id' => $this->mail_list_id,
            'list_name' => $this->mailList->name,
        ];

        $data = array_merge($data, $add_datas);

        Log::create([
            'customer_id' => $customer->id,
            'type' => 'page',
            'name' => $name,
            'data' => json_encode($data),
        ]);
    }

    /**
     * Check if page has outside url.
     */
    public function canHasOutsideUrl()
    {
        return in_array($this->layout->alias, array(
            'sign_up_thankyou_page',
            'sign_up_confirmation_thankyou',
            'unsubscribe_success_page',
            'profile_update_email_sent',
            'profile_update_success_page',
        ));
    }

    /**
     * Get outside url with subscriber uid.
     */
    public function getOutsideUrlWithUid($subscriber)
    {
        $url = $this->outside_url;

        // LIST FIELDS
        foreach ($this->mailList->fields as $field) {
            $url = str_replace('{SUBSCRIBER_'.$field->tag.'}', urlencode($subscriber->getValueByField($field)), $url);
        }

        if (parse_url($this->outside_url, PHP_URL_QUERY)) {
            $url = $url.'&subscriber_id='.$subscriber->uid;
        } else {
            $url = $url.'?subscriber_id='.$subscriber->uid;
        }

        // replacing tags
        $url = str_replace('{EMAIL}', $subscriber->getValueByTag('EMAIL'), $url);
        $url = str_replace('{FIRST_NAME}', urlencode($subscriber->getValueByTag('FIRST_NAME')), $url);
        $url = str_replace('{LAST_NAME}', urlencode($subscriber->getValueByTag('LAST_NAME')), $url);
        $url = str_replace('{ID}', $subscriber->uid, $url);

        return $url;
    }
}
