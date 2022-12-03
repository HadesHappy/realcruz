<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Select2 plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        echo \Acelle\Model\Plan::select2($request);
    }
}
