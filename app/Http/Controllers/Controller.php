<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Acelle\Model\Setting;
use Artisan;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
    }

    /**
     * Check if the site is in demo mode.
     *
     * @return \Illuminate\Http\Response
     */
    public function isDemoMode()
    {
        return config('app.demo');
    }

    /**
     * Check if the user is not authorized.
     *
     * @return \Illuminate\Http\Response
     */
    public function notAuthorized()
    {
        if (request()->ajax()) {
            return response()->json(['message' => trans('messages.not_authorized_message')], 403);
        }

        return response()->view('notAuthorized')->setStatusCode(403);
    }

    /**
     * Check if the user cannot create more item.
     *
     * @return \Illuminate\Http\Response
     */
    public function noMoreItem()
    {
        return view('noMoreItem');
    }

    /**
     * When site status is offline.
     *
     * @return \Illuminate\Http\Response
     */
    public function offline()
    {
        return view('offline');
    }

    /**
     * Show demo home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function demo()
    {
        return view('demo');
    }

    /**
     * Go to demo admin/campaign page.
     *
     * @return \Illuminate\Http\Response
     */
    public function demoGo(Request $request)
    {
        \Auth::logout();

        if ($request->view == 'backend') {
            session()->put('demo', 'backend');
            return redirect()->action('Admin\HomeController@index');
        } else {
            session()->put('demo', 'frontend');
            return redirect()->action('HomeController@index');
        }
    }

    /**
     * Docs for api v1.
     *
     * @return \Illuminate\Http\Response
     */
    public function docsApiV1()
    {
        return view('docs.api.v1', ['view' => 'frontend']);
    }

    /**
     * Login from outsite.
     *
     * @return \Illuminate\Http\Response
     */
    public function autoLogin($api_token)
    {
        $user = \Acelle\Model\User::where('api_token', $api_token)->first();

        \Auth::login($user);

        return redirect()->action('HomeController@index');
    }

    public function validateToken($api_token)
    {
        $valid = is_object(\Acelle\Model\User::where('api_token', $api_token)->first());

        if ($valid) {
            return response()
                ->json([
                    'status' => 'success',
                ], 200);
        } else {
            return response()
                ->json([
                    'status' => 'failed',
                ], 401);
        }
    }

    /**
     * Login from outsite.
     *
     * @return \Illuminate\Http\Response
     */
    public function tokenLogin($token)
    {
        $user = \Acelle\Model\User::where('one_time_api_token', $token)->first();

        if (!$user) {
            return view('somethingWentWrong', ['message' => trans('messages.token_expired')]);
        }

        \Auth::login($user);
        $user->clearOneTimeToken();

        return redirect()->action('HomeController@index');
    }

    /**
     * Translate datatable.
     *
     * @return text
     */
    public function datatable_locale()
    {
        echo '{
            "sEmptyTable":     "'.trans('messages.datatable_sEmptyTable').'",
            "sProcessing":   "'.trans('messages.datatable_sProcessing').'",
            "sLengthMenu":   "'.trans('messages.datatable_sLengthMenu').'",
            "sZeroRecords":  "'.trans('messages.datatable_sZeroRecords').'",
            "sInfo":         "'.trans('messages.datatable_sInfo').'",
            "sInfoEmpty":    "'.trans('messages.datatable_sInfoEmpty').'",
            "sInfoFiltered": "'.trans('messages.datatable_sInfoFiltered').'",
            "sInfoPostFix":  "'.trans('messages.datatable_sInfoPostFix').'",
            "sSearch":       "'.trans('messages.datatable_sSearch').'",
            "sUrl":          "'.trans('messages.datatable_sUrl').'",
            "oPaginate": {
                "sFirst":    "'.trans('messages.datatable_sFirst').'",
                "sPrevious": "'.trans('messages.datatable_sPrevious').'",
                "sNext":     "'.trans('messages.datatable_sNext').'",
                "sLast":     "'.trans('messages.datatable_sLast').'"
            },
            "oAria": {
                "sSortAscending":  "'.trans('messages.datatable_sSortAscending').'",
                "sSortDescending": "'.trans('messages.datatable_sSortDescending').'"
            }
        }';
    }

    /**
     * Translate jqery validate.
     *
     * @return text
     */
    public function jquery_validate_locale()
    {
        return response('jQuery.extend(jQuery.validator.messages, {
            required: "'.trans('messages.jvalidate_required').'",
            remote: "'.trans('messages.jvalidate_remote').'",
            email: "'.trans('messages.jvalidate_email').'",
            url: "'.trans('messages.jvalidate_url').'",
            date: "'.trans('messages.jvalidate_date').'",
            dateISO: "'.trans('messages.jvalidate_dateISO').'",
            number: "'.trans('messages.jvalidate_number').'",
            digits: "'.trans('messages.jvalidate_digits').'",
            creditcard: "'.trans('messages.jvalidate_creditcard').'",
            equalTo: "'.trans('messages.jvalidate_equalTo').'",
            accept: "'.trans('messages.jvalidate_accept').'",
            maxlength: jQuery.validator.format("'.trans('messages.jvalidate_maxlength').'"),
            minlength: jQuery.validator.format("'.trans('messages.jvalidate_minlength').'"),
            rangelength: jQuery.validator.format("'.trans('messages.jvalidate_rangelength').'"),
            range: jQuery.validator.format("'.trans('messages.jvalidate_range').'"),
            max: jQuery.validator.format("'.trans('messages.jvalidate_max').'"),
            min: jQuery.validator.format("'.trans('messages.jvalidate_min').'")
        });')->header('Content-Type', 'application/javascript');
    }

    /**
     * When user is diabled.
     *
     * @return \Illuminate\Http\Response
     */
    public function userDisabled()
    {
        \Auth::logout();
        return view('isDisabled');
    }

    public function appkey()
    {
        echo get_app_identity();
    }

    public function termsOfService()
    {
        return view('termsOfService');
    }
}
