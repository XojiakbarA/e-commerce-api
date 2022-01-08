<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteAddressColumnAndAddNewColumnsToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->string('first_name')->after('rating');
            $table->string('last_name')->after('first_name');
            $table->foreignId('user_id')->nullable()->after('last_name')->constrained()->onDelete('cascade');;
            $table->foreignId('region_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('district_id')->nullable()->after('region_id')->constrained()->onDelete('cascade');
            $table->string('street')->after('district_id');
            $table->string('home')->after('street');
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
            $table->string('address')->after('rating');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('region_id');
            $table->dropConstrainedForeignId('district_id');
            $table->dropColumn('street');
            $table->dropColumn('home');
        });
    }
}
