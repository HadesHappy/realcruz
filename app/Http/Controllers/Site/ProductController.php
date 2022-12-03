<?php

namespace Acelle\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('site.products.index');
    }

    public function index2(Request $request)
    {
        return view('site.products.index2');
    }

    public function listing(Request $request)
    {
        $wordpress = new \Acelle\Library\WordpressManager();

        // view
        $view = $request->view ? $request->view : 'grid';

        // wordpress themes
        $products = \Acelle\Model\WpPost::products()
            ->search($request->keyword)
            ->paginate($request->per_page ? $request->per_page : 8);

        return view('site.products._list_' . $view, [
            'products' => $products,
        ]);
    }

    public function add(Request $request)
    {
        $wordpress = new \Acelle\Library\WordpressManager();

        $product = $request->user()->customer->newProduct();

        wp_login('lusn', '123456');

        return view('site.products.add', [
            'product' => $product,
            'categories' => $wordpress->getProductCategories(),
        ]);
    }

    public function edit(Request $request, $id)
    {
        $wordpress = new \Acelle\Library\WordpressManager();

        $wooProduct = wc_get_product($id);

        return view('site.products.edit', [
            'wooProduct' => $wooProduct,
        ]);
    }

    public function delete(Request $request, $id)
    {
        $wordpress = new \Acelle\Library\WordpressManager();

        wp_delete_post($id);

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.product.deleted'),
        ]);
    }
}
