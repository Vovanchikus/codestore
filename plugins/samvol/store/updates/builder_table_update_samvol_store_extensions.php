<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreExtensions extends Migration
{
    public function up()
{
    Schema::table('samvol_store_extensions', function($table)
    {
        $table->decimal('original_price', 10, 0)->nullable();
        $table->decimal('price', 10, 0)->nullable()->change();
    });
}

public function down()
{
    Schema::table('samvol_store_extensions', function($table)
    {
        $table->dropColumn('original_price');
        $table->decimal('price', 10, 0)->nullable(false)->change();
    });
}
}
