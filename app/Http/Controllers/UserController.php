<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Library\Log as MailLog;
use Acelle\Model\Customer;
use Acelle\Model\User;
use Acelle\Library\Facades\Hook;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    /**
     * Log in back user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function loginBack(Request $request)
    {
        $id = \Session::pull('orig_user_id');
        $orig_user = User::findByUid($id);

        \Auth::login($orig_user);

        return redirect()->action('Admin\UserController@index');
    }

    /**
     * Activate user account.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, $token)
    {
        $userActivation = \Acelle\Model\UserActivation::where('token', '=', $token)->first();

        if (!$userActivation) {
            return view('notAuthorized');
        } else {
            $userActivation->user->setActivated();

            // Execute registered hooks
            Hook::execute('customer_added', [$userActivation->user->customer]);

            $request->session()->put('user-activated', trans('messages.user.activated'));

            if (isset($request->redirect)) {
                return redirect()->away(urldecode($request->redirect));
            } else {
                return redirect()->action('HomeController@index');
            }
        }
    }

    /**
     * Resen activation confirmation email.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resendActivationEmail(Request $request)
    {
        $user = User::findByUid($request->uid);

        try {
            $user->sendActivationMail($user->email, action('HomeController@index'));
        } catch (\Exception $e) {
            return view('somethingWentWrong', ['message' => trans('messages.something_went_wrong_with_email_service').': '.$e->getMessage() ]);
        }

        return view('users.registration_confirmation_sent');
    }

    /**
     * User registration.
     */
    public function register(Request $request)
    {
        if (\Acelle\Model\Setting::get('enable_user_registration') == 'no') {
            return $this->notAuthorized();
        }

        // If already logged in
        if (!is_null($request->user())) {
            return redirect()->action('SubscriptionController@index');
        }

        // Initiate customer object for filling the form
        $customer = Customer::newCustomer();
        $user = new User();
        if (!empty($request->old())) {
            $customer->fill($request->old());
            $user->fill($request->old());
        }

        // save posted data
        if ($request->isMethod('post')) {
            $user->fill($request->all());
            $rules = $user->registerRules();

            // Captcha check
            if (\Acelle\Model\Setting::get('registration_recaptcha') == 'yes') {

                // @hCaptcha
                if (\Acelle\Model\Setting::getCaptchaProvider() == 'hcaptcha') {
                    $hcaptcha = \Acelle\Hcaptcha\Client::initialize();
                    $success = $hcaptcha->check($request);
                    if (!$success) {
                        $rules['captcha_invalid'] = 'required';
                    }
                } else {
                    $success = \Acelle\Library\Tool::checkReCaptcha($request);
                    if (!$success) {
                        $rules['recaptcha_invalid'] = 'required';
                    }
                }
            }

            $this->validate($request, $rules);

            // Okay, create it
            $user = $customer->createAccountAndUser($request);

            // user email verification
            if (true) {
                // Send registration confirmation email
                try {
                    $user->sendActivationMail($user->displayName());
                } catch (\Exception $e) {
                    return view('somethingWentWrong', ['message' => trans('messages.something_went_wrong_with_email_service') . ": " . $e->getMessage()]);
                }

                return view('users.register_confirmation_notice');

            // no email verification
            } else {
                $user->setActivated();
                return redirect()->route('login');
            }
        }

        return view('users.register', [
            'customer' => $customer,
            'user' => $user,
        ]);
    }
}
