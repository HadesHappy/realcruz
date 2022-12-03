<?php

namespace Acelle\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Acelle\Library\WordpressManager;

class SettingController extends Controller
{
    public function shop(Request $request)
    {
        return view('site.settings.shop');
    }

    public function sliderRevolution(Request $request)
    {
        return view('site.settings.sliderRevolution');
    }

    public function products(Request $request)
    {
        return view('site.settings.products');
    }

    public function shipping(Request $request)
    {
        return view('site.settings.shipping');
    }

    public function payments(Request $request)
    {
        return view('site.settings.payments');
    }

    public function account(Request $request)
    {
        return view('site.settings.account');
    }

    public function emails(Request $request)
    {
        return view('site.settings.emails');
    }

    public function site(Request $request)
    {
        return view('site.settings.site');
    }

    public function homePage(Request $request)
    {
        return view('site.settings.homePage');
    }
}
