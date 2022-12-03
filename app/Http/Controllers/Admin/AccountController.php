<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Update user profile.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function profile(Request $request)
    {
        // Get current user
        $admin = $request->user()->admin;
        $admin->getColorScheme();

        // Authorize
        if (\Gate::denies('profile', $admin)) {
            return $this->notAuthorized();
        }

        // Save posted data
        if ($request->isMethod('post')) {
            // Prenvent save from demo mod
            if ($this->isDemoMode()) {
                return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
            }

            // Update user account for admin
            $user = $request->user();
            $user->fill($request->all());

            $this->validate($request, $user->rules());

            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            // Save current user info
            $admin->fill($request->all());

            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    $user->uploadProfileImage($request->file('image'));
                }
            }

            // Remove image
            if ($request->_remove_image == 'true') {
                $user->removeProfileImage();
            }

            if ($admin->save()) {
                $request->session()->flash('alert-success', trans('messages.profile.updated'));
            }

            return redirect()->action('Admin\AccountController@profile');
        }

        if (!empty($request->old())) {
            $admin->fill($request->old());
            // User info
            $admin->user->fill($request->old());
        }

        return view('admin.account.profile', [
            'admin' => $admin,
            'user' => $request->user(),
        ]);
    }

    /**
     * Update user contact information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function contact(Request $request)
    {
        // Get current user
        $admin = $request->user()->admin;
        if (is_object($admin->contact)) {
            $contact = $admin->contact;
        } else {
            $contact = new \Acelle\Model\Contact([
                                        'first_name' => $request->user()->first_name,
                                        'last_name' => $request->user()->last_name,
                                        'email' => $request->user()->email,
                                    ]);
        }

        // Create new company if null
        if (!is_object($contact)) {
            $contact = new \Acelle\Model\Contact();
        }

        // save posted data
        if ($request->isMethod('post')) {
            // Prenvent save from demo mod
            if ($this->isDemoMode()) {
                return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
            }

            $this->validate($request, \Acelle\Model\Contact::$rules);

            $contact->fill($request->all());

            // Save current user info
            if ($contact->save()) {
                if (is_object($contact)) {
                    $admin->contact_id = $contact->id;
                    $admin->save();
                }
                $request->session()->flash('alert-success', trans('messages.customer_contact.updated'));
            }
        }

        return view('admin.account.contact', [
            'admin' => $admin,
            'contact' => $contact->fill($request->old()),
        ]);
    }

    /**
     * Api token.
     */
    public function api(Request $request)
    {
        return view('admin.account.api');
    }

    /**
     * Renew api token.
     */
    public function renewToken(Request $request)
    {
        $user = $request->user();

        $user->api_token = str_random(60);
        $user->save();

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.user_api.renewed'));

        return redirect()->action('Admin\AccountController@api');
    }

    public function leftbarState(Request $request)
    {
        $request->session()->put('admin-leftbar-state', $request->state);
    }

    public function saveAutoThemeMode(Request $request)
    {
        $request->session()->put('admin-auto-theme-mode', $request->theme_mode);
    }

    public function changeThemeMode(Request $request)
    {
        $admin = $request->user()->admin;

        // Save color scheme
        if ($request->isMethod('post')) {
            $admin->theme_mode = $request->theme_mode;
            $admin->save();
        }
    }
}
