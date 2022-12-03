<?php

namespace Acelle\Model;

use Validator;
use Illuminate\Database\Eloquent\Model;
use Acelle\Model\Source;
use Acelle\Model\Product;

class WooCommerce extends Source
{
    /**
     * Connect to WordPress WooCommerce.
     *
     * @var array
     */
    public static function init($connectUrl, $customer)
    {
        $source = $customer->newProductSource('WooCommerce');
        $meta = [
            'connect_url' => $connectUrl,
            'data' => null,
        ];
        $validator = Validator::make(['connect_url' => $connectUrl], [
            'connect_url' => 'required|url',
        ]);

        // Step1: check endpoint can connect
        $validator->after(function ($validator) use ($connectUrl, &$source, &$meta) {
            $client = new \GuzzleHttp\Client();

            $sign = (strpos($connectUrl, '?') !== false) ? '&' : '?';

            // Step 1.1: check valid endpoint
            try {
                $response = $client->request('GET', $connectUrl, [
                    'headers' => [
                        "content-type" => "application/json"
                    ],
                ]);
            } catch (\Exception $e) {
                $validator->errors()->add('connect_url', trans('messages.can_not_connect_acelle_sync', ['error' => $e->getMessage()]));
                return;
            }

            // Step 1.2: get shop information
            try {
                $response = $client->request('GET', $connectUrl . $sign . 'action=shop_info', [
                    'headers' => [
                        "content-type" => "application/json"
                    ],
                ]);

                $meta['data'] = json_decode($response->getBody(), true);
            } catch (\Exception $e) {
                $validator->errors()->add('connect_url', trans('messages.source.can_not_get_woo_data', ['error' => $e->getMessage()]));
                return;
            }
        });

        // save
        if (!$validator->fails()) {
            $source->meta = json_encode($meta);
            $source->save();
        }

        return [$source, $validator];
    }

    public function getShopInfo()
    {
        $client = new \GuzzleHttp\Client();
        $connectUrl = $this->getData()['connect_url'];

        $sign = (strpos($connectUrl, '?') !== false) ? '&' : '?';

        // Get shop information
        try {
            $response = $client->request('GET', $connectUrl . $sign . 'action=shop_info', [
                'headers' => [
                    "content-type" => "application/json"
                ],
            ]);

            $meta = json_decode($response->getBody(), true);
            $this->updateData([
                'data' => $meta,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Can not connect to WooCommerce Store: ' . $e->getMessage());
        }
    }

    public function sync()
    {
        // import products
        $this->importProducts();

        // get shopinfo
        $this->getShopInfo();
    }

    public function importProducts()
    {
        $client = new \GuzzleHttp\Client();
        $sign = (strpos($this->getData()['connect_url'], '?') !== false) ? '&' : '?';
        $uri =  $this->getData()['connect_url'] . $sign .
                'action=list&max=100&sort_by=';

        $response = $client->request('GET', $uri, [
            'Content-Type' => 'application/json',
        ]);
        $products = json_decode($response->getBody(), true);

        $total = count($products);
        $imported = 0;

        foreach ($products as $key => $product) {
            try {
                // find exist product
                $p = Product::firstOrNew([
                    'source_item_id' => $product["id"]
                ]);
                $p->customer_id = $this->customer_id;
                $p->source_id = $this->id;

                $p->title = $product["name"];
                $p->description = $product["description"];
                $p->meta = json_encode($product);
                $p->save();

                // upload image
                if ($product["image"]) {
                    $p->uploadImage($product["image"]);
                }

                $p->save();

                $imported++;
                // importing
                $this->updateData([
                    'sync' => [
                        'status' => 'importing',
                        'imported' => $imported,
                        'total' => $total,
                        'progress' => round(($imported / $total) * 100, 2),
                    ]
                ]);
            } catch (\Exception $e) {
                // write error
                \Log::error($e->getMessage());
            }
        }
    }
}
