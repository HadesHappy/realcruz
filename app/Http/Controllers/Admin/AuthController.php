<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Model\Setting;
use Acelle\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.index');
    }

    /**
     * Google OAuth 2.0.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleOAuth(Request $request)
    {
        if ($request->user()->admin->getPermission('setting_general') != 'yes') {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            if ($request->google_enabled == 'yes') {
                // make validator
                $validator = \Validator::make($request->all(), [
                    'google_client_id' => 'required',
                    'google_client_secret' => 'required',
                ]);

                // redirect if fails
                if ($validator->fails()) {
                    return response()->view('admin.auth.googleOAuth', [
                        'errors' => $validator->errors(),
                    ], 400);
                }

                // update settings table
                Setting::set('oauth.google_client_id', $request->google_client_id);
                Setting::set('oauth.google_client_secret', $request->google_client_secret);
            }

            // update settings table
            Setting::set('oauth.google_enabled', $request->google_enabled);

            return redirect()->action('Admin\AuthController@index')
                ->with('alert-success', trans('messages.setting.updated'));
        }

        return view('admin.auth.googleOAuth');
    }

    /**
     * Facebook OAuth 2.0.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookOAuth(Request $request)
    {
        if ($request->user()->admin->getPermission('setting_general') != 'yes') {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            if ($request->facebook_enabled == 'yes') {
                // make validator
                $validator = \Validator::make($request->all(), [
                    'facebook_client_id' => 'required',
                    'facebook_client_secret' => 'required',
                ]);

                // redirect if fails
                if ($validator->fails()) {
                    return response()->view('admin.auth.facebookOAuth', [
                        'errors' => $validator->errors(),
                    ], 400);
                }

                // update settings table
                Setting::set('oauth.facebook_client_id', $request->facebook_client_id);
                Setting::set('oauth.facebook_client_secret', $request->facebook_client_secret);
            }

            // update settings table
            Setting::set('oauth.facebook_enabled', $request->facebook_enabled);

            return redirect()->action('Admin\AuthController@index')
                ->with('alert-success', trans('messages.setting.updated'));
        }

        return view('admin.auth.facebookOAuth');
    }
}
