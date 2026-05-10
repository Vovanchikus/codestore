<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class AddItemsPerPageToCatalogs extends Migration
{
    public function up()
    {
        // No-op: items_per_page field is now stored in settings JSON, not a DB column.
        return;
    }

    public function down()
    {
        // No-op
        return;
    }
}
