<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class AddIconSvgToCategories extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('samvol_catalog_categories', 'icon_svg')) {
            Schema::table('samvol_catalog_categories', function (Blueprint $table) {
                $table->text('icon_svg')->nullable()->after('description');
            });
        }

    }

    public function down(): void
    {
        if (Schema::hasColumn('samvol_catalog_categories', 'icon_svg')) {
            Schema::table('samvol_catalog_categories', function (Blueprint $table) {
                $table->dropColumn('icon_svg');
            });
        }
    }
}
