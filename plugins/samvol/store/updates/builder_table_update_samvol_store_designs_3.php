<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreDesigns3 extends Migration
{
    public function up()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->string('archive_path')->nullable();
        $table->string('screenshots', 255)->nullable()->unsigned(false)->default(null)->change();
    });
}

public function down()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->dropColumn('archive_path');
        $table->text('screenshots')->nullable()->unsigned(false)->default(null)->change();
    });
}
}
