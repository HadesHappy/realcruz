<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Acelle\Model\Language;

class AddPortuguese extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newId = Language::max('id') + 1;

        // reserve ID of 1,2 for English and Spanish which will be loaded from the database_init.sql file
        if ($newId < 3) {
            $newId = 3;
        }

        $lang = new Language();
        $lang->id = $newId;
        $lang->name = 'Portuguese';
        $lang->code = 'pt';
        $lang->region_code = 'pt';
        $lang->status = 'active';
        $lang->save();
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
