<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolStoreComments extends Migration
{
    public function up()
{
    Schema::create('samvol_store_comments', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('user_id')->unsigned();
        $table->integer('item_id')->unsigned();
        $table->string('item_type');
        $table->text('comment_text');
        $table->integer('parent_id')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_store_comments');
}
}
