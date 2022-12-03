<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('invoice_id')->unsigned();
            $table->string('item_id');
            $table->string('item_type');
            $table->double('amount', 16, 2)->default(0);
            $table->double('tax', 16, 2)->default(0);
            $table->double('discount', 16, 2)->default(0);
            $table->string('title');
            $table->text('description');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
