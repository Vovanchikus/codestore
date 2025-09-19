<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolStoreDesignCategories extends Migration
{
    public function up()
{
    Schema::create('samvol_store_design_categories', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('name');
        $table->string('slug');
    });
}

public function down()
{
    Schema::dropIfExists('samvol_store_design_categories');
}
}
