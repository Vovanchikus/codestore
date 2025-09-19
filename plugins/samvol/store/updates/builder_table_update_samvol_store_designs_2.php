<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreDesigns2 extends Migration
{
    public function up()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->decimal('original_price', 10, 0)->nullable();
    });
}

public function down()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->dropColumn('original_price');
    });
}
}
