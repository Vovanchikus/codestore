<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolStoreDesign extends Migration
{
    public function up()
{
    Schema::create('samvol_store_design', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('title');
        $table->string('slug');
        $table->text('desc');
        $table->integer('category_id')->unsigned();
        $table->decimal('price', 10, 0)->nullable();
        $table->text('screenshots')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_store_design');
}
}
