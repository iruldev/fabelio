<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code');
            $table->string('name');
            $table->text('description');
            $table->text('specification');
            $table->string('category');
            $table->string('subcategory');
            $table->string('item');
            $table->bigInteger('stock')->default(0);
            $table->bigInteger('price')->default(0);
            $table->bigInteger('discount')->default(0);
            $table->json('display_at');
            $table->bigInteger('default_image_id')->nullable();
            $table->boolean('is_promo')->default(false);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
