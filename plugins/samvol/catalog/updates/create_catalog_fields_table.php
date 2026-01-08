<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateCatalogFieldsTable extends Migration
{
    public function up(): void
    {
        Schema::create('samvol_catalog_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('catalog_id');
            $table->unique(['catalog_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samvol_catalog_fields');
    }
}
