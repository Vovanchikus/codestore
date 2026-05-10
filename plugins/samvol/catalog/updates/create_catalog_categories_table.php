<?php namespace Samvol\Catalog\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateCatalogCategoriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('samvol_catalog_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('data')->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
            $table->timestamps();

            $table->index('catalog_id');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samvol_catalog_categories');
    }
}
