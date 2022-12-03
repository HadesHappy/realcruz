<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Library\Log as MailLog;
use Acelle\Model\Customer;
use Acelle\Model\User;
use Acelle\Library\Facades\Hook;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Google OAuth 2.0.
     */
    public function getGoogleOAuthProvider()
    {
        $config = [
            'client_id' => \Acelle\Model\Setting::get('oauth.google_client_id'),
            'client_secret' => \Acelle\Model\Setting::get('oauth.google_client_secret'),
            'redirect' => action('AuthController@googleCallback'),
        ];

        $provider = Socialite::buildProvider(
            \Laravel\Socialite\Two\GoogleProvider::class,
            $config
        );

        return $provider;
    }

    /**
     * Google OAuth 2.0.
     */
    public function googleRedirect(Request $request)
    {
        return $this->getGoogleOAuthProvider()
            ->with(['hl' => language_code()])
            ->redirect();
    }

    /**
     * Google OAuth 2.0.
     */
    public function googleCallback(Request $request)
    {
        if (isSiteDemo()) {
            // return redirect('/login')->with('alert-error', trans('messages.operation_not_allowed_in_demo'));
            return view('demoLogin');
        }

        $googleUser = $this->getGoogleOAuthProvider()->user();

        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            $user->update([
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);
        } else {
            // check if email exists
            if (User::where('email', $googleUser->email)->first()) {
                return redirect('/login')->with('alert-error', trans('messages.oauth.email_exist', [
                    'email' => $googleUser->email,
                ]));
            }

            \Acelle\Model\User::createCustomer([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);

            $user = User::where('google_id', $googleUser->id)->first();
        }

        // login
        \Auth::login($user);
        return redirect()->action('HomeController@index');
    }

    /**
     * Facebook OAuth 2.0.
     */
    public function getFacebookOAuthProvider()
    {
        $config = [
            'client_id' => \Acelle\Model\Setting::get('oauth.facebook_client_id'),
            'client_secret' => \Acelle\Model\Setting::get('oauth.facebook_client_secret'),
            'redirect' => action('AuthController@facebookCallback'),
        ];

        $provider = Socialite::buildProvider(
            \Laravel\Socialite\Two\FacebookProvider::class,
            $config
        );

        return $provider;
    }

    /**
     * Facebook OAuth 2.0.
     */
    public function facebookRedirect(Request $request)
    {
        $langCode = str_replace('-', '_', getFullCodeByLanguageCode(language_code()));
        return $this->getFacebookOAuthProvider()
            ->with(['locale' => $langCode])
            ->redirect();
    }

    /**
     * Facebook OAuth 2.0.
     */
    public function facebookCallback(Request $request)
    {
        if (isSiteDemo()) {
            // return redirect('/login')->with('alert-error', trans('messages.operation_not_allowed_in_demo'));
            return view('demoLogin');
        }

        $facebookUser = $this->getFacebookOAuthProvider()->user();

        $user = User::where('facebook_id', $facebookUser->id)->first();

        if ($user) {
            $user->update([
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken,
            ]);
        } else {
            // check if email exists
            if (User::where('email', $facebookUser->email)->first()) {
                return redirect('/login')->with('alert-error', trans('messages.oauth.email_exist', [
                    'email' => $facebookUser->email,
                ]));
            }

            \Acelle\Model\User::createCustomer([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'facebook_id' => $facebookUser->id,
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken,
            ]);

            $user = User::where('facebook_id', $facebookUser->id)->first();
        }

        // login
        \Auth::login($user);
        return redirect()->action('HomeController@index');
    }
}
