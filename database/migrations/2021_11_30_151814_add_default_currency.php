<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Acelle\Model\Currency::count()) {
            $currency = Acelle\Model\Currency::firstOrNew([
                'code' => 'USD',
            ], [
                'name' => 'US Dollar',
                'format' => '${PRICE}',
            ]);

            $currency->status = 'active';
            $currency->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
