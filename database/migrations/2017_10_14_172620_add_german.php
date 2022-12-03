<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Acelle\Model\Language;

class AddGerman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Language::where('code', 'de')->exists()) {
            return;
        }
        $newId = Language::max('id') + 1;

        // reserve ID of 1,2 for English and Spanish which will be loaded from the database_init.sql file
        if ($newId < 3) {
            $newId = 3;
        }

        $lang = new Language();
        $lang->id = $newId;
        $lang->name = 'German';
        $lang->code = 'de';
        $lang->region_code = 'de';
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
        // n/a
    }
}
