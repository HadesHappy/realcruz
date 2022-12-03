<?php

namespace Acelle\Http\Controllers\Admin;

use Acelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Acelle\Model\Warmup;
use Acelle\Model\Schedule;
use Acelle\Model\Week1;
use Acelle\Model\Week2;
use Acelle\Model\Week3;
use Acelle\Model\Week4;

class WarmUpController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        // Trigger admin monitoring events when admin is logged in
        event(new \Acelle\Events\AdminLoggedIn());
    }

    /**
     * Show the application admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function warmup(Request $request)
    {
        if (!config('app.saas')) {
            return redirect()->action('Admin\WarmUpController@warmup');
        }

        $currentTimezone = $request->user()->admin->getTimezone();
        
        $datas_week1 = Week1::get();
        $datas_week2 = Week2::get();
        $datas_week3 = Week3::get();
        $datas_week4 = Week4::get();
        
        return view('admin.warmup.warmup', compact('datas_week1', 'datas_week2', 'datas_week3', 'datas_week4'));
    }
    
    public function week1_update(Request $request, $id)
    {
        $day = Week1::find($id);
        $day->count= $request->input('count');
        $day->update();
        
        return redirect()->action('Admin\WarmUpController@warmup');
    }

    public function week2_update(Request $request, $id)
    {
        $day = Week2::find($id);
        $day->count= $request->input('count');
        $day->update();
        
        return redirect()->action('Admin\WarmUpController@warmup');
    }

    public function week3_update(Request $request, $id)
    {
        $day = Week3::find($id);
        $day->count= $request->input('count');
        $day->update();
        
        return redirect()->action('Admin\WarmUpController@warmup');
    }

    public function week4_update(Request $request, $id)
    {
        $day = Week4::find($id);
        $day->count= $request->input('count');
        $day->update();
        
        return redirect()->action('Admin\WarmUpController@warmup');
    }
    
    public function schedule(Request $request)
    {
        if (!config('app.saas')) {
            return redirect()->action('Admin\WarmUpController@schedule');
        }
        $id = 1;
        $gap = Schedule::find($id);
        $currentTimezone = $request->user()->admin->getTimezone();

        return view('admin.warmup.schedule', compact('gap'));
    }

    public function gap_update(Request $request, $id)
    {
        $newGap = Schedule::find($id);
        $newGap->gap= $request->input('gap');
        $newGap->update();
        return redirect()->action('Admin\WarmUpController@schedule');
    }
    
}