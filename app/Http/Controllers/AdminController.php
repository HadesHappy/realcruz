<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
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
        $orig_user = \Acelle\Model\User::findByUid($id);

        \Auth::login($orig_user);

        return redirect()->action('Admin\UserController@index');
    }
}
