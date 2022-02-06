<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropShopImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('shop_images');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('shop_images', function (Blueprint $table) {
            $table->id();
            $table->string('src');
            $table->enum('status', ['bg_big', 'bg_small', 'avatar']);
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
}
