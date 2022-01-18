<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsInShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropConstrainedForeignId('region_id');
            $table->dropColumn('bg_image');
            $table->dropColumn('av_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->foreignId('region_id')->after('user_id')->nullable()->constrained();
            $table->string('bg_image')->after('phone');
            $table->string('av_image')->after('bg_image');
        });
    }
}
