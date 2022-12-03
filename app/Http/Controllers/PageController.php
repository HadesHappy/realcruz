<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Acelle\Events\MailListSubscription;
use Acelle\Model\Setting;
use Acelle\Model\MailList;
use Acelle\Model\IpLocation;

class PageController extends Controller
{
    /**
     * Redirect page if use outside url.
     */
    public function checkOutsideUrlRedirect($page)
    {
        if ($page->use_outside_url) {
            return redirect($page->outside_url);
        }
    }

    /**
     * Update list page content.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        $layout = \Acelle\Model\Layout::where('alias', $request->alias)->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);

        // storing
        if ($request->isMethod('post')) {
            $page->fill($request->all());

            $validate = 'required';
            foreach ($layout->tags() as $tag) {
                if ($tag['required']) {
                    $validate .= '|substring:'.$tag['name'];
                }
            }

            $rules = array();

            // Check if use outside url
            if ($request->use_outside_url) {
                $rules['outside_url'] = 'active_url';
            } else {
                $rules['content'] = $validate;
                $rules['subject'] = 'required';
            }

            // Validation
            $this->validate($request, $rules);

            // save
            $page->save();

            // Log
            $page->log('updated', $request->user()->customer);

            $request->session()->flash('alert-success', trans('messages.page.updated'));

            return redirect()->action('PageController@update', array('list_uid' => $list->uid, 'alias' => $layout->alias));
        }

        // return back
        $page->fill($request->old());

        return view('pages.update', [
            'list' => $list,
            'page' => $page,
            'layout' => $layout,
        ]);
    }

    /**
     * Preview page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        $layout = \Acelle\Model\Layout::where('alias', $request->alias)->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $page->content = $request->content;

        // render content
        $page->renderContent();

        return view('pages.preview_'.$page->layout->type, [
            'list' => $list,
            'page' => $page,
        ]);
    }

    /**
     * Sign up form page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function signUpForm(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'sign_up_form')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        // Get old post values
        $values = [];
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        $page->renderContent($values);

        // Create subscriber
        if ($request->isMethod('post')) {
            try {
                list($validator, $subscriber) = $list->subscribe($request, MailList::SOURCE_WEB);
            } catch (\Exception $ex) {
                return view('somethingWentWrong', ['message' => $ex->getMessage()]);
            }

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if ($request->redirect_url) {
                return redirect()->away($request->redirect_url);
            } elseif ($list->subscribe_confirmation && !$subscriber->isSubscribed()) {
                // tell subscriber to check email for confirmation
                return redirect()->action('PageController@signUpThankyouPage', ['list_uid' => $list->uid, 'subscriber_uid' => $subscriber->uid]);
            } else {
                // All done, confirmed
                return redirect()->action(
                    'PageController@signUpConfirmationThankyou',
                    [
                        'list_uid' => $list->uid,
                        'uid' => $subscriber->uid,
                        'code' => 'empty',
                    ]
                );
            }
        }

        return view('pages.form', [
            'list' => $list,
            'page' => $page,
            'values' => $values,
        ]);
    }

    /**
     * Sign up thank you page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function signUpThankyouPage(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'sign_up_thankyou_page')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->subscriber_uid);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        // redirect if use outside url
        if ($page->use_outside_url) {
            return redirect($page->getOutsideUrlWithUid($subscriber));
        }

        $page->renderContent(null, $subscriber);

        return view('pages.default', [
            'list' => $list,
            'page' => $page,
        ]);
    }

    /**
     * Sign up confirmation thank you page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function signUpConfirmationThankyou(Request $request)
    {
        $user = $request->user();
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'sign_up_confirmation_thankyou')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        if (is_null($subscriber)) {
            echo "Subscriber no longer exists";
            return;
        }

        $page->renderContent(null, $subscriber);

        if ($subscriber->getSecurityToken('subscribe-confirm') == $request->code && $subscriber->status == 'unconfirmed') {
            $subscriber->confirm();
        }

        // redirect if use outside url
        if ($page->use_outside_url) {
            return redirect($page->getOutsideUrlWithUid($subscriber));
        }

        return view('pages.default', [
            'list' => $list,
            'page' => $page,
        ]);
    }

    /**
     * Unsibscribe form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function unsubscribeForm(Request $request)
    {
        // IMPORTANT: it does not create TrackingLog!!!
        $user = $request->user();
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'unsubscribe_form')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        $page->renderContent(null, $subscriber);

        if ($request->isMethod('post')) {
            if ($subscriber->getSecurityToken('unsubscribe') == $request->code && $subscriber->status == 'subscribed') {

                // User Tracking Information
                $trackingInfo = [
                    'message_id' => null,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                ];

                // GeoIP information
                $location = IpLocation::add($_SERVER['REMOTE_ADDR']);
                if (!is_null($location)) {
                    $trackingInfo['ip_address'] = $location->ip_address;
                }

                $subscriber->unsubscribe($trackingInfo);
            }

            return redirect()->action('PageController@unsubscribeSuccessPage', ['list_uid' => $list->uid, 'uid' => $subscriber->uid]);
        }

        return view('pages.form', [
            'list' => $list,
            'page' => $page,
        ]);
    }

    /**
     * Unsibscribe form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function unsubscribeSuccessPage(Request $request)
    {
        $user = $request->user();
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'unsubscribe_success_page')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        $page->renderContent(null, $subscriber);

        // redirect if use outside url
        if ($page->use_outside_url) {
            return redirect($page->getOutsideUrlWithUid($subscriber));
        }

        return view('pages.default', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Update profile form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpdateForm(Request $request)
    {
        $user = $request->user();
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'profile_update_form')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        $values = [];

        // Fetch subscriber fields to values
        foreach ($list->fields as $key => $field) {
            $value = $subscriber->getValueByField($field);
            if (is_array($value)) {
                $values[str_replace('[]', '', $key)] = implode(',', $value);
            } else {
                $values[$field->tag] = $value;
            }
        }

        // Get old post values
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        $page->renderContent($values, $subscriber);

        if ($request->isMethod('post')) {
            if ($subscriber->getSecurityToken('update-profile') == $request->code) {
                $rules = $subscriber->getRules();
                $rules['EMAIL'] .= '|in:'.$subscriber->email;
                // Validation
                $this->validate($request, $rules);

                // Update field
                $subscriber->updateFields($request->all());

                return redirect()->action('PageController@profileUpdateSuccessPage', ['list_uid' => $list->uid, 'uid' => $subscriber->uid]);
            }
        }

        return view('pages.form', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Update profile success.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpdateSuccessPage(Request $request)
    {
        $user = $request->user();
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'profile_update_success_page')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        $page->renderContent(null, $subscriber);

        // redirect if use outside url
        if ($page->use_outside_url) {
            return redirect($page->getOutsideUrlWithUid($subscriber));
        }

        return view('pages.default', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Send update profile request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpdateEmailSent(Request $request)
    {
        $user = $request->user();
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);
        $layout = \Acelle\Model\Layout::where('alias', 'profile_update_email_sent')->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);
        $subscriber = \Acelle\Model\Subscriber::findByUid($request->uid);

        // Language
        if (is_object($list->customer) && is_object($list->customer->language)) {
            \App::setLocale($list->customer->language->code);
            \Carbon\Carbon::setLocale($list->customer->language->code);
        }

        $page->renderContent(null, $subscriber);

        // SEND EMAIL
        try {
            $list->sendProfileUpdateEmail($subscriber);
        } catch (\Exception $ex) {
            return view('somethingWentWrong', ['message' => $ex->getMessage()]);
        }

        // redirect if use outside url
        if ($page->use_outside_url) {
            return redirect($page->getOutsideUrlWithUid($subscriber));
        }

        return view('pages.default', [
            'list' => $list,
            'page' => $page,
            'subscriber' => $subscriber,
        ]);
    }

    public function restoreDefault(Request $request)
    {
        $list = \Acelle\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('update', $list)) {
            return $this->notAuthorized();
        }

        $layout = \Acelle\Model\Layout::where('alias', $request->alias)->first();
        $page = \Acelle\Model\Page::findPage($list, $layout);

        $page->delete();

        $request->session()->flash('alert-success', trans('messages.page.reset.success'));
        return redirect()->action('PageController@update', array('list_uid' => $list->uid, 'alias' => $layout->alias));
    }
}
