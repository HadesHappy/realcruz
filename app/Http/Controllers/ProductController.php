<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Acelle\Model\MailList;
use Acelle\Model\EmailVerificationServer;
use Acelle\Events\MailListSubscription;
use Acelle\Model\Setting;
use Acelle\Model\Customer;
use Acelle\Model\Product;
use Acelle\Model\Source;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('products.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        // search
        $products = $request->user()->customer->products()
            ->search($request->keyword);

        // filter
        if ($request->source_uid) {
            $products = $products->filter('source_id', Source::findByUid($request->source_uid)->id);
        }

        // order + pagination
        $products = $products->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        // view
        $view = $request->view ? $request->view : 'grid';

        return view('products._list_' . $view, [
            'products' => $products,
        ]);
    }

    public function widgetProductList(Request $request)
    {
        return Product::generateWidgetProductListHtmlContent($request->all());
    }

    public function widgetProductOptions(Request $request)
    {
        $results = Product::search($request->keyword)->paginate($request->per_page)
            ->map(function ($item, $key) {
                return ['text' => $item->title, 'id' => $item->uid];
            })->toArray();

        $json = '{
            "items": ' .json_encode($results). ',
            "more": ' . (empty($results) ? 'false' : 'true') . '
        }';

        return $json;
    }

    public function widgetProduct(Request $request)
    {
        return Product::generateWidgetProductHtmlContent($request->all());
    }
}
