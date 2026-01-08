<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class AddIconModeToCategories extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('samvol_catalog_categories', 'icon_mode')) {
            Schema::table('samvol_catalog_categories', function (Blueprint $table) {
                $table->string('icon_mode', 20)->nullable()->after('icon_svg');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('samvol_catalog_categories', 'icon_mode')) {
            Schema::table('samvol_catalog_categories', function (Blueprint $table) {
                $table->dropColumn('icon_mode');
            });
        }
    }
}
