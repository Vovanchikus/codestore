<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreDesign extends Migration
{
    public function up()
{
    Schema::table('samvol_store_design', function($table)
    {
        $table->timestamp('deleted_at')->nullable();
    });
}

public function down()
{
    Schema::table('samvol_store_design', function($table)
    {
        $table->dropColumn('deleted_at');
    });
}
}
