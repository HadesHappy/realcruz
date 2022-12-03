<?php

namespace Acelle\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Acelle\Model\MailList;
use Acelle\Model\EmailVerificationServer;
use Acelle\Events\MailListSubscription;
use Acelle\Model\Setting;
use Acelle\Model\Customer;
use Acelle\Model\Product;
use Acelle\Model\Source;
use Acelle\Model\WooCommerce;
use Acelle\Model\Lazada;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('sources.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $sources = $request->user()->customer->sources()
            ->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        return view('sources._list', [
            'sources' => $sources,
        ]);
    }

    /**
     * Show source details.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $uid)
    {
        $source = Source::findByUid($uid);
        $automation = $request->user()->customer->getAbandonedEmailAutomation($source);
        // var_dump($automation);die();

        return view('sources.show', [
            'source' => $source,
            'automation' => $automation,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Generate info
        $customer = $request->user()->customer;

        // Get lazada connection
        $lazadaSource = $request->user()->customer->newProductSource('Lazada');

        // authorize
        if (\Gate::denies('create', Source::class)) {
            return $this->notAuthorized();
        }

        return view('sources.create', [
            'lazadaConnectLink' => $lazadaSource->service()->getConnectLink(),
        ]);
    }

    /**
     * Receive code and generate token.
     *
     * @return \Illuminate\Http\Response
     */
    public function connect(Request $request)
    {
        // connection
        $lazadaSource = $request->user()->customer->newProductSource('Lazada');
        $lazadaSource->init($request->code);

        // redirect
        $request->session()->flash('success', 'Connected to Lazada!');
        return redirect()->action('SourceController@index');
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
            echo trans('messages.operation_not_allowed_in_demo');
            return;
        }

        $sources = \Acelle\Model\Source::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($sources->get() as $source) {
            // authorize
            if (!\Gate::allows('delete', $source)) {
                // Redirect to my lists page
                return response()->json([
                    'status' => 'error',
                    'message' => trans('messages.source.delete.can_not'),
                ]);
            }

            // do delete
            $source->delete();
        }

        // Redirect to my lists page
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.source.deleted'),
        ]);
    }

    /**
     * Import product from source.
     *
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request, $uid)
    {
        // connection
        $source = Source::findByUid($uid);
        $source = $source->classMapping();

        // import products
        $source->sync();

        // redirect
        $request->session()->flash('alert-success', 'Products were imported!');
        return redirect()->action('SourceController@index');
    }

    /**
     * Connect to WooCommerce.
     *
     * @return \Illuminate\Http\Response
     */
    public function wooConnect(Request $request)
    {
        // saving
        if ($request->isMethod('post')) {
            list($source, $validator) = WooCommerce::init($request->connect_url, $request->user()->customer);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('sources.wooConnect', [
                    'errors' => $validator->errors(),
                ], 400);
            }

            // success
            return response()->json([
                'status' => 'success',
                'redirect' => action('SourceController@index'),
            ]);
        }

        return view('sources.wooConnect');
    }
}
