<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolStoreRatings extends Migration
{
    public function up()
{
    Schema::create('samvol_store_ratings', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('user_id')->unsigned();
        $table->integer('item_id')->unsigned();
        $table->string('item_type');
        $table->integer('value');
        $table->timestamp('created_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_store_ratings');
}
}
