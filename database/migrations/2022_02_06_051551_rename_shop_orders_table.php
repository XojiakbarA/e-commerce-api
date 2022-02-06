<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameShopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->renameIndex('shop_orders_shop_id_foreign', 'sub_orders_shop_id_foreign');
            $table->renameIndex('shop_orders_order_id_foreign', 'sub_orders_order_id_foreign');
        });
        Schema::rename('shop_orders', 'sub_orders');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->renameIndex('sub_orders_shop_id_foreign', 'shop_orders_shop_id_foreign');
            $table->renameIndex('sub_orders_order_id_foreign', 'shop_orders_order_id_foreign');
        });
        Schema::rename('sub_orders', 'shop_orders');
    }
}
