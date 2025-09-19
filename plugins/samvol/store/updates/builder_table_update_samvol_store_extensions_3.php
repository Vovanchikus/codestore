<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreExtensions3 extends Migration
{
    public function up()
{
    Schema::table('samvol_store_extensions', function($table)
    {
        $table->integer('user_id')->unsigned();
    });
}

public function down()
{
    Schema::table('samvol_store_extensions', function($table)
    {
        $table->dropColumn('user_id');
    });
}
}
