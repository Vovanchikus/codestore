<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreDesigns4 extends Migration
{
    public function up()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->integer('user_id')->unsigned();
    });
}

public function down()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->dropColumn('user_id');
    });
}
}
