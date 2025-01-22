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
        Schema::create('math_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->integer('student_id')->index()->comment('student id');
            $table->string('cycle_id')->comment('cycle_id')->nullable();
            $table->string('column_a')->comment('Student Last Name')->nullable();
            $table->string('column_b')->comment('Student First Name')->nullable();
            $table->string('column_c')->comment('SSID')->nullable();
            $table->string('column_d')->comment('Grade')->nullable();
            $table->string('column_e')->comment('SIS')->nullable();
            $table->string('column_f')->comment('Qualifying Subject')->nullable();
            $table->string('column_g')->comment('Teacher Name')->nullable();
            $table->string('column_h')->comment('Diagnostic Placement ')->nullable();
            $table->string('column_i')->comment('Qualified for Intervention')->nullable();
            $table->string('column_j')->comment('Recommended Program')->nullable();
            $table->string('column_k')->comment('Student School Email')->nullable();
            $table->string('column_l')->comment('SPED Y/N')->nullable();
            $table->string('column_m')->comment('SAI Teacher')->nullable();
            $table->string('column_n')->comment('Easycbm Fall Assessment Score')->nullable();
            $table->string('column_o')->comment('Intervention selection ')->nullable();
            $table->string('column_p')->comment('6-8th Grade Only                   PAPER REQUEST')->nullable();
            $table->string('column_q')->comment('iReady mid year Relative Placement ')->nullable();
            $table->string('column_r')->comment('Growth iReady')->nullable();
            $table->string('column_s')->comment('Easycbm Spring Assessment Score (add as comment)')->nullable();
            $table->string('column_t')->comment('iReady Post Test Relative Placement ')->nullable();
            $table->string('column_u')->comment('Growth iReady')->nullable();
            $table->string('column_v')->comment('Easycbm Fall Assessment Point/Percent')->nullable();
            $table->string('column_w')->comment('Easycbm Winter Assessment Point/Percent')->nullable();
            $table->string('column_x')->comment('Easycbm Spring Assessment Point/Percent')->nullable();
            $table->string('column_y')->comment('Growth Easycbm points/percent')->nullable();
            $table->string('column_z')->comment('Class info link')->nullable();
            $table->string('column_aa')->comment('Notes')->nullable();

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
        Schema::dropIfExists('math_lists');
    }
};
