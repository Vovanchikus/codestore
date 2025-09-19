<?php namespace Samvol\Store\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolStoreDesigns extends Migration
{
    public function up()
{
    Schema::rename('samvol_store_design', 'samvol_store_designs');
}

public function down()
{
    Schema::rename('samvol_store_designs', 'samvol_store_design');
}
}
