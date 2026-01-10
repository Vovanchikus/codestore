<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class AddCountersToItems extends Migration
{
    public function up(): void
    {
        Schema::table('samvol_catalog_items', function (Blueprint $table) {
            $table->bigInteger('downloads_count')->unsigned()->default(0)->index();
            $table->bigInteger('views_count')->unsigned()->default(0)->index();
        });
    }

    public function down(): void
    {
        Schema::table('samvol_catalog_items', function (Blueprint $table) {
            $table->dropColumn(['downloads_count', 'views_count']);
        });
    }
}
