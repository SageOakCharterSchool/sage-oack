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
        Schema::create('i_ready_math_minutes', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->integer('student_id')->index()->comment('student id');
            $table->string('cycle_id')->comment('cycle_id')->nullable();
            $table->string('column_a')->comment('Last Name')->nullable();
            $table->string('column_b')->comment('First Name')->nullable();
            $table->string('column_c')->comment('Student ID')->nullable();
            $table->string('column_d')->comment('Student Grade')->nullable();
            $table->string('column_e')->comment('Academic Year')->nullable();
            $table->string('column_f')->comment('School')->nullable();
            $table->string('column_g')->comment('Subject')->nullable();
            $table->string('column_h')->comment('Enrolled')->nullable();
            $table->string('column_i')->comment('User Name')->nullable();
            $table->string('column_j')->comment('Sex')->nullable();
            $table->string('column_k')->comment('Hispanic or Latino')->nullable();
            $table->string('column_l')->comment('Race')->nullable();
            $table->string('column_m')->comment('English Language Learner')->nullable();
            $table->string('column_n')->comment('Special Education')->nullable();
            $table->string('column_o')->comment('Economically Disadvantaged')->nullable();
            $table->string('column_p')->comment('Migrant')->nullable();
            $table->string('column_q')->comment('Class(es)')->nullable();
            $table->string('column_r')->comment('Class Teacher(s)')->nullable();
            $table->string('column_s')->comment('Report Group(s)')->nullable();
            $table->string('column_t')->comment('First Lesson Completion Date')->nullable();
            $table->string('column_u')->comment('Most Recent Lesson Completion Date')->nullable();
            $table->string('column_v')->comment('Year-to-Date Overall Time on Task (min)')->nullable();
            $table->string('column_w')->comment('Year-to-Date Overall Lessons Passed')->nullable();
            $table->string('column_x')->comment('Year-to-Date Overall Lessons Completed')->nullable();
            $table->string('column_y')->comment('Year-to-Date Overall % Lessons Passed')->nullable();
            $table->string('column_z')->comment('Year-to-Date Number and Operations Time on Task (min)')->nullable();
            $table->string('column_aa')->comment('Year-to-Date Number and Operations Lessons Passed')->nullable();
            $table->string('column_ab')->comment('Year-to-Date Number and Operations Lessons Completed')->nullable();
            $table->string('column_ac')->comment('Year-to-Date Number and Operations % Lessons Passed')->nullable();
            $table->string('column_ad')->comment('Year-to-Date Algebra and Algebraic Thinking Time on Task (min)')->nullable();
            $table->string('column_ae')->comment('Year-to-Date Algebra and Algebraic Thinking Lessons Passed')->nullable();
            $table->string('column_af')->comment('Year-to-Date Algebra and Algebraic Thinking Lessons Completed')->nullable();
            $table->string('column_ag')->comment('Year-to-Date Algebra and Algebraic Thinking % Lessons Passed')->nullable();
            $table->string('column_ah')->comment('Year-to-Date Measurement and Data Time on Task (min)')->nullable();
            $table->string('column_ai')->comment('Year-to-Date Measurement and Data Lessons Passed')->nullable();
            $table->string('column_aj')->comment('Year-to-Date Measurement and Data Lessons Completed')->nullable();
            $table->string('column_ak')->comment('Year-to-Date Measurement and Data % Lessons Passed')->nullable();
            $table->string('column_al')->comment('Year-to-Date Geometry Time on Task (min)')->nullable();
            $table->string('column_am')->comment('Year-to-Date Geometry Lessons Passed')->nullable();
            $table->string('column_an')->comment('Year-to-Date Geometry Lessons Completed')->nullable();
            $table->string('column_ao')->comment('Year-to-Date Geometry % Lessons Passed')->nullable();

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
        Schema::dropIfExists('i_ready_math_minutes');
    }
};
