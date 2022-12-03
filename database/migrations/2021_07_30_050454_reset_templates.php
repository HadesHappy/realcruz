<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Acelle\Model\TemplateCategory;
use Acelle\Model\Template;

class ResetTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // UPDATE: delete only ones with UID below
        // Clean up
        Template::shared()->delete();

        // Delete category
        TemplateCategory::query()->delete();

        // Cateogries
        $categoryBasic = TemplateCategory::create(['name' => 'Basic']);
        $categoryFeatured = TemplateCategory::create(['name' => 'Featured']);
        $categoryTheme = TemplateCategory::create(['name' => 'Themes']);
        $categoryWoo = TemplateCategory::create(['name' => 'WooCommerce']);
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
