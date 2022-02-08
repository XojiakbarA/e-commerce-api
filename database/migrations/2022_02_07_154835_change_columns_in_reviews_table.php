<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('name');
            $table->tinyInteger('published')->default(0)->after('product_id');
            $table->foreignId('user_id')->after('text')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('published');
            $table->dropConstrainedForeignId('user_id');
            $table->string('name')->after('id');
            $table->string('image')->after('product_id');
        });
    }
}
