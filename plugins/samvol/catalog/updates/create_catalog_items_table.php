<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateCatalogItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('samvol_catalog_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id')->unsigned();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('status')->default('draft');
            $table->json('data')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('catalog_id');
            $table->index('category_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samvol_catalog_items');
    }
}
