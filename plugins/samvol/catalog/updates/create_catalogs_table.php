<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateCatalogsTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('samvol_catalog_catalogs') && !Schema::hasTable('samvol_catalogs')) {
            Schema::rename('samvol_catalog_catalogs', 'samvol_catalogs');
            return;
        }

        Schema::create('samvol_catalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samvol_catalogs');
    }
}
