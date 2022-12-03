<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class SamplesController extends Controller
{
    public function index()
    {
        return view('samples.index');
    }
}
