<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Model\Source;
use File;
use Acelle\Library\Traits\HasUid;

class Product extends Model
{
    use HasUid;

    public static $itemsPerPage = 16;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['source_item_id'];

    // belongs to customer
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    // belongs to source
    public function source()
    {
        return $this->belongsTo('Acelle\Model\Source');
    }

    public function scopeFilter($query, $attribute, $value)
    {
        $query->where($attribute, '=', $value);
    }

    public function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $k) {
                $query = $query->where(function ($q) use ($k) {
                    $q->orwhere('products.title', 'like', '%'.strtolower($k).'%');
                });
            }
        }
    }

    // upload image
    public function uploadImage($url)
    {
        copy($url, $this->getImagePath());
    }

    // get image path
    public function getImagePath()
    {
        return $this->customer->getProductsPath('product-image-' . $this->uid);
    }

    public function getImageUrl()
    {
        if (file_exists($this->getImagePath())) {
            return \Acelle\Helpers\generatePublicPath($this->getImagePath());
        } else {
            return \URL::asset('images/no-product-image.png');
        }
    }

    public static function generateWidgetProductListHtmlContent($params)
    {
        $products = Product::limit($params['count']);
        $sort = explode('-', $params['sort']);

        if (!isset($sort[1]) || !isset($params['count']) || !isset($params['cols'])) {
            return "";
        }

        $products = $products->orderBy(explode('-', $params['sort'])[0], explode('-', $params['sort'])[1]);
        $products = $products->get();

        return view('products.widgetProductListHtmlContent', [
            'products' => $products,
            'options' => $params,
        ]);
    }

    public static function generateWidgetProductHtmlContent($params)
    {
        $product = self::findByUid($params['id']);

        // replace tags
        $html = $params['content'];
        $html = str_replace('*|PRODUCT_NAME|*', $product->title, $html);
        $html = str_replace('*|PRODUCT_DESCRIPTION|*', substr(strip_tags($product->description), 0, 200), $html);
        $html = str_replace('*|PRODUCT_PRICE|*', format_price($product->price), $html);
        $html = str_replace('*|PRODUCT_QUANTITY|*', $product->title, $html);
        $html = str_replace('*|PRODUCT_URL|*', action('ProductController@index'), $html);
        $html = str_replace('*%7CPRODUCT_URL%7C*', action('ProductController@index'), $html);

        // try to replace product image
        $dom = new \DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NODEFDTD);

        $imgs = $dom->getElementsByTagName("img");
        foreach ($imgs as $img) {
            $att = $img->getAttribute('builder-element');
            if ($att == 'ProductImgElement') {
                $img->setAttribute('src', $product->getImageUrl());
            }
        }

        return $dom->saveHTML();
    }
}
