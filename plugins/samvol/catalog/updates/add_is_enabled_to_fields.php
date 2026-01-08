<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class AddIsEnabledToFields extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('samvol_catalog_fields', 'is_enabled')) {
            Schema::table('samvol_catalog_fields', function (Blueprint $table) {
                $table->boolean('is_enabled')->default(true)->after('is_required');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('samvol_catalog_fields', 'is_enabled')) {
            Schema::table('samvol_catalog_fields', function (Blueprint $table) {
                $table->dropColumn('is_enabled');
            });
        }
    }
}
