<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameShopOrderIdColumnInOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->renameColumn('shop_order_id', 'sub_order_id');
            $table->renameIndex('order_products_shop_order_id_foreign', 'order_products_sub_order_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->renameColumn('sub_order_id', 'shop_order_id');
            $table->renameIndex('order_products_sub_order_id_foreign', 'order_products_shop_order_id_foreign');
        });
    }
}
