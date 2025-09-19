<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreDesigns5 extends Migration
{
    public function up()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->string('author_name')->nullable();
        $table->string('source')->default('Не указано');
        $table->renameColumn('user_id', 'added_by_user_id');
    });
}

public function down()
{
    Schema::table('samvol_store_designs', function($table)
    {
        $table->dropColumn('author_name');
        $table->dropColumn('source');
        $table->renameColumn('added_by_user_id', 'user_id');
    });
}
}
