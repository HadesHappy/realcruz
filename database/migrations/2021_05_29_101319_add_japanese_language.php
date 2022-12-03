<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Acelle\Model\Language;

class AddJapaneseLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Language::where('code', 'ja')->exists()) {
            $japanese = new Language();
            $japanese->name = '日本語 (Japanese)';
            $japanese->code = 'ja';
            $japanese->region_code = 'ja';
            $japanese->status = Language::STATUS_ACTIVE;
            $japanese->save();
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
