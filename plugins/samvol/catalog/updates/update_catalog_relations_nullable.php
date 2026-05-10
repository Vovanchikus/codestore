<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class UpdateCatalogRelationsNullable extends Migration
{
    public function up(): void
    {
        Schema::table('samvol_catalog_fields', function (Blueprint $table) {
            $table->integer('catalog_id')->unsigned()->nullable()->change();
        });

        Schema::table('samvol_catalog_categories', function (Blueprint $table) {
            $table->integer('catalog_id')->unsigned()->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('samvol_catalog_fields', function (Blueprint $table) {
            $table->integer('catalog_id')->unsigned()->nullable(false)->change();
        });

        Schema::table('samvol_catalog_categories', function (Blueprint $table) {
            $table->integer('catalog_id')->unsigned()->nullable(false)->change();
        });
    }
}
