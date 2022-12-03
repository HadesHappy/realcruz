<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Plan;
use Acelle\Cashier\Cashier;
use Acelle\Library\Facades\Hook;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        // If admin can view all customer
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Customer())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $customers = \Acelle\Model\Customer::search($request)
            ->filter($request);

        return view('admin.customers.index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        // If admin can view all customer
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Customer())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $customers = \Acelle\Model\Customer::search($request->keyword)
            ->filter($request)
            ->orderBy($request->sort_order, $request->sort_direction ? $request->sort_direction : 'asc')
            ->paginate($request->per_page);

        return view('admin.customers._list', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = \Acelle\Model\Customer::newCustomer();
        $customer->status = 'active';
        $customer->uid = '0';

        if (!empty($request->old())) {
            $customer->fill($request->old());
        }

        // User info
        $customer->user = new \Acelle\Model\User();
        $customer->user->fill($request->old());

        // authorize
        if (\Gate::denies('create', $customer)) {
            return $this->notAuthorized();
        }

        return view('admin.customers.create', [
            'customer' => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get current user
        $current_user = $request->user();
        $customer = \Acelle\Model\Customer::newCustomer();
        $contact = new \Acelle\Model\Contact();

        // authorize
        if (\Gate::denies('create', $customer)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $user = new \Acelle\Model\User();
            $user->fill($request->all());
            $user->activated = true;

            $this->validate($request, $user->rules());

            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            // Save current user info
            $customer->admin_id = $request->user()->admin->id;
            $customer->fill($request->all());
            $customer->status = 'active';

            if ($customer->save()) {
                $user->customer_id = $customer->id;
                $user->save();
                // Upload and save image
                if ($request->hasFile('image')) {
                    if ($request->file('image')->isValid()) {
                        // Remove old images
                        $user->uploadProfileImage($request->file('image'));
                    }
                }

                // Remove image
                if ($request->_remove_image == 'true') {
                    $user->removeProfileImage();
                }

                // Execute registered hooks
                Hook::execute('customer_added', [$customer]);

                $request->session()->flash('alert-success', trans('messages.customer.created'));

                return redirect()->action('Admin\CustomerController@index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $customer = \Acelle\Model\Customer::findByUid($id);
        event(new \Acelle\Events\UserUpdated($customer));

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $customer->fill($request->old());
            // User info
            $customer->user->fill($request->old());
        }

        return view('admin.customers.edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get current user
        $current_user = $request->user();
        $customer = \Acelle\Model\Customer::findByUid($id);

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        // Prenvent save from demo mod
        if ($this->isDemoMode()) {
            return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
        }

        // save posted data
        if ($request->isMethod('patch')) {
            // Prenvent save from demo mod
            if ($this->isDemoMode()) {
                return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
            }

            $user = $customer->user;
            $user->fill($request->all());

            $this->validate($request, $user->rules());

            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            // Save current user info
            $customer->fill($request->all());
            $customer->save();

            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $user->uploadProfileImage($request->file('image'));
                }
            }

            // Remove image
            if ($request->_remove_image == 'true') {
                $user->removeProfileImage();
            }

            if ($customer->save()) {
                $request->session()->flash('alert-success', trans('messages.customer.updated'));
                return redirect()->action('Admin\CustomerController@index');
            }
        }
    }

    /**
     * Enable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \Acelle\Model\Customer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->enable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.customers.disabled');
    }

    /**
     * Disable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \Acelle\Model\Customer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.customers.disabled');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (isSiteDemo()) {
            return response()->json(["message" => trans('messages.operation_not_allowed_in_demo')], 404);
        }

        $customers = \Acelle\Model\Customer::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($customers->get() as $customer) {
            // authorize
            if (\Gate::denies('delete', $customer)) {
                return;
            }
        }

        foreach ($customers->get() as $customer) {
            // Delete Customer account but KEEP user account if it is associated with an Admin
            $customer->deleteAccount();
        }

        // Redirect to my lists page
        echo trans('messages.customers.deleted');
    }

    /**
     * Switch user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function loginAs(Request $request)
    {
        $customer = \Acelle\Model\Customer::findByUid($request->uid);

        // authorize
        if (\Gate::denies('loginAs', $customer)) {
            return $this->notAuthorized();
        }

        $orig_id = $request->user()->uid;
        \Auth::login($customer->user);
        \Session::put('orig_customer_id', $orig_id);
        return redirect()->action('HomeController@index');
    }

    /**
     * Log in back user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function loginBack(Request $request)
    {
        $id = \Session::pull('orig_customer_id');
        $orig_user = \Acelle\Model\Customer::findByUid($id);

        \Auth::login($orig_user);

        return redirect()->action('Admin\UserController@index');
    }

    /**
     * Select2 customer.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        echo \Acelle\Model\Customer::select2($request);
    }

    /**
     * User's subscriptions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscriptions(Request $request, $uid)
    {
        $customer = \Acelle\Model\Customer::findByUid($uid);

        // authorize
        if (\Gate::denies('read', $customer)) {
            return $this->notAuthorized();
        }

        return view('admin.customers.subscriptions', [
            'customer' => $customer
        ]);
    }

    /**
     * Customers growth chart content.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function growthChart(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        $result = [
            'columns' => [],
            'data' => [],
        ];

        // columns
        for ($i = 4; $i >= 0; --$i) {
            $result['columns'][] = \Carbon\Carbon::now()->subMonthsNoOverflow($i)->format('m/Y');
            $result['data'][] = \Acelle\Model\Customer::customersCountByTime(
                \Carbon\Carbon::now()->subMonthsNoOverflow($i)->startOfMonth(),
                \Carbon\Carbon::now()->subMonthsNoOverflow($i)->endOfMonth(),
                $request->user()->admin
            );
        }

        return response()->json($result);
    }

    /**
     * Update customer contact information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function contact(Request $request, $uid)
    {
        // Get current user
        $customer = \Acelle\Model\Customer::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        if (is_object($customer->contact)) {
            $contact = $customer->contact;
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
            $this->validate($request, \Acelle\Model\Contact::$rules);

            $contact->fill($request->all());

            // Save current user info
            if ($contact->save()) {
                if (is_object($contact)) {
                    $customer->contact_id = $contact->id;
                    $customer->save();
                }
                $request->session()->flash('alert-success', trans('messages.customer_contact.updated'));
            }
        }

        return view('admin.customers.contact', [
            'customer' => $customer,
            'contact' => $contact->fill($request->old()),
        ]);
    }

    /**
     * Customer's sub-account list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function subAccount(Request $request, $uid)
    {
        // Get current user
        $customer = \Acelle\Model\Customer::findByUid($uid);

        // authorize
        if (\Gate::denies('viewSubAccount', $customer)) {
            return redirect()->action('Admin\CustomerController@edit', $customer->uid);
        }

        return view('admin.customers.sub_account', [
            'customer' => $customer
        ]);
    }

    /**
     * Assign plan to customer.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function assignPlan(Request $request, $uid)
    {
        $customer = \Acelle\Model\Customer::findByUid($uid);
        $plans = Plan::active()->get();

        // authorize
        if (\Gate::denies('assignPlan', $customer)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $plan = Plan::findByUid($request->plan_uid);

            $customer->assignPlan($plan);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.customer.plan.assigned', [
                    'plan' => $plan->name,
                    'customer' => $customer->user->displayName(),
                ]),
            ], 201);
        }

        return view('admin.customers.assign_plan', [
            'customer' => $customer,
            'plans' => $plans,
        ]);
    }
}
