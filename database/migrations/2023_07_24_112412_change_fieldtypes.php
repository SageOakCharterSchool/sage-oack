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
    public function up() {
        Schema::table('student_lists', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
            $table->longText('column_g')->change();
            $table->longText('column_k')->change();
        });
        Schema::table('consolidateds', function (Blueprint $table) {
            $table->longText('column_b')->change();
            $table->longText('column_c')->change();
            $table->longText('column_g')->change();
        });
        Schema::table('sheet15s', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
            $table->longText('column_c')->change();
        });
        Schema::table('math_lists', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
            $table->longText('column_g')->change();
        });
        Schema::table('attendance_maths', function (Blueprint $table) {
            $table->longText('column_b')->change();
            $table->longText('column_c')->change();
        });
        Schema::table('attendance_elas', function (Blueprint $table) {
            $table->longText('column_b')->change();
            $table->longText('column_c')->change();
        });
        Schema::table('freckle_minutes', function (Blueprint $table) {
            $table->longText('column_a')->change();
        });


        Schema::table('star_fall_maths', function (Blueprint $table) {
            $table->longText('column_b')->change();
        });
        Schema::table('star_fall_readings', function (Blueprint $table) {
            $table->longText('column_b')->change();
        });
        Schema::table('star_mid_year_maths', function (Blueprint $table) {
            $table->longText('column_b')->change();
        });
        Schema::table('star_mid_year_readings', function (Blueprint $table) {
            $table->longText('column_b')->change();
        });
        Schema::table('easy_cbm_falls', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('easy_cbm_progmons', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('read180_minutes', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('math180_minutes', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('v_math_minutes', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_math_boys', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_reading_boy_s', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_math_mid_years', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_reading_mid_years', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_math_eoy_s', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_reading_eoy_s', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_math_minutes', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });
        Schema::table('i_ready_reading_minutes', function (Blueprint $table) {
            $table->longText('column_a')->change();
            $table->longText('column_b')->change();
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
