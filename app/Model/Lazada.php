<?php

namespace Acelle\Model;

use Validator;
use Illuminate\Database\Eloquent\Model;
use Acelle\Model\Source;
use Acelle\Model\Product;
use Acelle\Library\Lazada\LazadaConnection;

class Lazada extends Source
{
    /**
     * Get service.
     *
     * @var object | collect
     */
    public function service()
    {
        if ($this->service) {
            return $this->service;
        }

        $this->service = new LazadaConnection(false, false, $this->getData());
        return $this->service;
    }

    /**
     * Update access token.
     *
     * @var object | collect
     */
    public function init($code)
    {
        // get access token by code
        $this->service()->getAccessToken($code);

        // update data
        $this->updateData($this->service()->data);
    }

    public function sync()
    {
        // import products
        $this->importProducts();
    }

    public function importProducts()
    {
        $data = $this->service()->getProducts(['offset' => 0, 'limit' => 1])['data'];
        $total = $data['total_products'];

        // starting
        $this->updateData([
            'sync' => [
                'status' => 'starting',
                'imported' => 0,
                'total' => $total,
                'progress' => 0,
            ]
        ]);

        $perPage = 12;
        $pages = ceil($total/$perPage);
        $imported = 0;
        for ($i=0; $i < $pages; $i++) {
            $products = $this->service()->getProducts(['offset' => $i*$perPage, 'limit' => $perPage])['data']['products'];
            foreach ($products as $key => $product) {
                // try {
                // find exist product
                $p = Product::firstOrNew([
                    'source_item_id' => $product["item_id"]
                ]);
                $p->customer_id = $this->customer_id;
                $p->source_id = $this->id;

                $p->title = $product["attributes"]["name"];
                if (isset($product["attributes"]["short_description"])) {
                    $p->description = $product["attributes"]["short_description"];
                }
                $p->meta = json_encode($product);
                $p->save();

                // upload image
                if (isset($product["skus"][0]["Images"]) && isset($product["skus"][0]["Images"][0])) {
                    $p->uploadImage($product["skus"][0]["Images"][0]);
                }

                // get price
                if (isset($product["skus"][0]["price"])) {
                    $p->price = $product["skus"][0]["price"];
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
                // } catch(\Exception $e) {
                //     // write error
                //     \Log::error($e->getMessage());
                // }
            }

            sleep(3);
        }

        // done
        $this->updateData([
            'sync' => [
                'status' => 'done',
                'imported' => $this->getData()['sync']['imported'],
                'total' => $this->getData()['sync']['total'],
                'progress' => 100,
            ]
        ]);
    }
}
