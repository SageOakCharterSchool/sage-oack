<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('star_eoy_maths', function (Blueprint $table) {
            $table->string('column_ae')->nullable();
        });
        Schema::table('star_eoy_readings', function (Blueprint $table) {
            $table->string('column_ae')->nullable();
        });
        Schema::table('star_mid_year_maths', function (Blueprint $table) {
            $table->string('column_ad')->nullable();
        });
        Schema::table('star_mid_year_readings', function (Blueprint $table) {
            $table->string('column_ae')->nullable();
        });
        Schema::table('star_fall_maths', function (Blueprint $table) {
            $table->string('column_ad')->nullable();
        });
        Schema::table('star_fall_readings', function (Blueprint $table) {
            $table->string('column_ae')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {




    }
};
