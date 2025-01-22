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
        Schema::dropIfExists('i_ready_math_boys');
        Schema::dropIfExists('i_ready_reading_boy_s');
        Schema::create('i_ready_math_boys', function (Blueprint $table) {
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id');
            $table->string('cycle_id')->index()->comment('cycle_id')->nullable();
            $table->string('teacher_id')->index()->comment('teacher_id')->nullable();
            $table->longText('column_a')->comment('Last Name')->nullable();
            $table->longText('column_b')->comment('First Name')->nullable();
            $table->string('column_c')->comment('Student ID')->nullable();
            $table->string('column_d')->comment('Student Grade')->nullable();
            $table->string('column_e')->comment('Academic Year')->nullable();
            $table->longText('column_f')->comment('School')->nullable();
            $table->longText('column_g')->comment('Enrolled')->nullable();
            $table->longText('column_h')->comment('District State ID')->nullable();
            $table->longText('column_i')->comment('Account State ID')->nullable();
            $table->longText('column_j')->comment('School State ID')->nullable();
            $table->longText('column_k')->comment('Student State ID')->nullable();
            $table->longText('column_l')->comment('User Name')->nullable();
            $table->longText('column_m')->comment('Sex')->nullable();
            $table->longText('column_n')->comment('Hispanic or Latino')->nullable();
            $table->longText('column_o')->comment('Race')->nullable();
            $table->longText('column_p')->comment('English Language Learner')->nullable();
            $table->longText('column_q')->comment('Special Education')->nullable();
            $table->longText('column_r')->comment('Economically Disadvantaged')->nullable();
            $table->longText('column_s')->comment('Migrant')->nullable();
            $table->longText('column_t')->comment('Class(es)')->nullable();
            $table->longText('column_u')->comment('Class Teacher(s)')->nullable();
            $table->longText('column_v')->comment('Report Group(s)')->nullable();
            $table->longText('column_w')->comment('Start Date')->nullable();
            $table->longText('column_x')->comment('Completion Date')->nullable();
            $table->longText('column_y')->comment('Baseline Diagnostic (Y/N)')->nullable();
            $table->longText('column_z')->comment('Most Recent Diagnostic YTD (Y/N)')->nullable();
            $table->longText('column_aa')->comment('Duration (min)')->nullable();
            $table->longText('column_ab')->comment('Rush Flag')->nullable();
            $table->longText('column_ac')->comment('Overall Scale Score')->nullable();
            $table->longText('column_ad')->comment('Overall Placement')->nullable();
            $table->longText('column_ae')->comment('Overall Relative Placement')->nullable();
            $table->longText('column_af')->comment('Percentile')->nullable();
            $table->longText('column_ag')->comment('Grouping')->nullable();
            $table->longText('column_ah')->comment('Quantile Measure')->nullable();
            $table->longText('column_ai')->comment('Quantile Range')->nullable();
            $table->longText('column_aj')->comment('Number and Operations Scale Score')->nullable();
            $table->longText('column_ak')->comment('Number and Operations Placement')->nullable();
            $table->longText('column_al')->comment('Number and Operations Relative Placement')->nullable();
            $table->longText('column_am')->comment('Algebra and Algebraic Thinking Scale Score')->nullable();
            $table->longText('column_an')->comment('Algebra and Algebraic Thinking Placement')->nullable();
            $table->longText('column_ao')->comment('Algebra and Algebraic Thinking Relative Placement')->nullable();
            $table->longText('column_ap')->comment('Measurement and Data Scale Score')->nullable();
            $table->longText('column_aq')->comment('Measurement and Data Placement')->nullable();
            $table->longText('column_ar')->comment('Measurement and Data Relative Placement')->nullable();
            $table->longText('column_as')->comment('Geometry Scale Score')->nullable();
            $table->longText('column_at')->comment('Geometry Placement')->nullable();
            $table->longText('column_au')->comment('Geometry Relative Placement')->nullable();
            $table->longText('column_av')->comment('Diagnostic Gain')->nullable();
            $table->longText('column_aw')->comment('Annual Typical Growth Measure')->nullable();
            $table->longText('column_ax')->comment('Annual Stretch Growth Measure')->nullable();
            $table->longText('column_ay')->comment('Percent Progress to Annual Typical Growth (%)')->nullable();
            $table->longText('column_az')->comment('Percent Progress to Annual Stretch Growth (%)')->nullable();
            $table->longText('column_ba')->comment('Mid On Grade Level Scale Score')->nullable();
            $table->longText('column_bb')->comment('504 Plan')->nullable();
            $table->longText('column_bc')->comment('English Language Acquisition')->nullable();
            $table->longText('column_bd')->comment('Foster Youth')->nullable();
            $table->longText('column_be')->comment('Gifted and Talented (GATE)')->nullable();
            $table->longText('column_bf')->comment('Homeless Youth')->nullable();
            $table->longText('column_bg')->comment('Student with Disabilities')->nullable();
            $table->longText('column_bh')->comment('Transitional Kindergarten')->nullable();
            $table->timestamps();
        });
        Schema::create('i_ready_reading_boy_s', function (Blueprint $table) {
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id');
            $table->string('cycle_id')->index()->comment('cycle_id')->nullable();
            $table->string('teacher_id')->index()->comment('teacher_id')->nullable();
            $table->longText('column_a')->comment('Last Name')->nullable();
            $table->longText('column_b')->comment('First Name')->nullable();
            $table->string('column_c')->comment('Student ID')->nullable();
            $table->string('column_d')->comment('Student Grade')->nullable();
            $table->string('column_e')->comment('Academic Year')->nullable();
            $table->longText('column_f')->comment('School')->nullable();
            $table->longText('column_g')->comment('Enrolled')->nullable();
            $table->longText('column_h')->comment('District State ID')->nullable();
            $table->longText('column_i')->comment('Account State ID')->nullable();
            $table->longText('column_j')->comment('School State ID')->nullable();
            $table->longText('column_k')->comment('Student State ID')->nullable();
            $table->longText('column_l')->comment('User Name')->nullable();
            $table->longText('column_m')->comment('Sex')->nullable();
            $table->longText('column_n')->comment('Hispanic or Latino')->nullable();
            $table->longText('column_o')->comment('Race')->nullable();
            $table->longText('column_p')->comment('English Language Learner')->nullable();
            $table->longText('column_q')->comment('Special Education')->nullable();
            $table->longText('column_r')->comment('Economically Disadvantaged')->nullable();
            $table->longText('column_s')->comment('Migrant')->nullable();
            $table->longText('column_t')->comment('Class(es)')->nullable();
            $table->longText('column_u')->comment('Class Teacher(s)')->nullable();
            $table->longText('column_v')->comment('Report Group(s)')->nullable();
            $table->longText('column_w')->comment('Start Date')->nullable();
            $table->longText('column_x')->comment('Completion Date')->nullable();
            $table->longText('column_y')->comment('Baseline Diagnostic (Y/N)')->nullable();
            $table->longText('column_z')->comment('Most Recent Diagnostic YTD (Y/N)')->nullable();
            $table->longText('column_aa')->comment('Duration (min)')->nullable();
            $table->longText('column_ab')->comment('Rush Flag')->nullable();
            $table->longText('column_ac')->comment('Overall Scale Score')->nullable();
            $table->longText('column_ad')->comment('Overall Placement')->nullable();
            $table->longText('column_ae')->comment('Overall Relative Placement')->nullable();
            $table->longText('column_af')->comment('Percentile')->nullable();
            $table->longText('column_ag')->comment('Grouping')->nullable();
            $table->longText('column_ah')->comment('Lexile Measure')->nullable();
            $table->longText('column_ai')->comment('Lexile Range')->nullable();
            $table->longText('column_aj')->comment('Phonological Awareness Scale Score')->nullable();
            $table->longText('column_ak')->comment('Phonological Awareness Placement')->nullable();
            $table->longText('column_al')->comment('Phonological Awareness Relative Placement')->nullable();
            $table->longText('column_am')->comment('Phonics Scale Score')->nullable();
            $table->longText('column_an')->comment('Phonics Placement')->nullable();
            $table->longText('column_ao')->comment('Phonics Relative Placement')->nullable();
            $table->longText('column_ap')->comment('High-Frequency Words Scale Score')->nullable();
            $table->longText('column_aq')->comment('High-Frequency Words Placement')->nullable();
            $table->longText('column_ar')->comment('High-Frequency Words Relative Placement')->nullable();
            $table->longText('column_as')->comment('Vocabulary Scale Score')->nullable();
            $table->longText('column_at')->comment('Vocabulary Placement')->nullable();
            $table->longText('column_au')->comment('Vocabulary Relative Placement')->nullable();
            $table->longText('column_av')->comment('Comprehension: Overall Scale Score')->nullable();
            $table->longText('column_aw')->comment('Comprehension: Overall Placement')->nullable();
            $table->longText('column_ax')->comment('Comprehension: Overall Relative Placement')->nullable();
            $table->longText('column_ay')->comment('Comprehension: Literature Scale Score')->nullable();
            $table->longText('column_az')->comment('Comprehension: Literature Placement')->nullable();
            $table->longText('column_ba')->comment('Comprehension: Literature Relative Placement')->nullable();
            $table->longText('column_bb')->comment('Comprehension: Informational Text Scale Score')->nullable();
            $table->longText('column_bc')->comment('Comprehension: Informational Text Placement')->nullable();
            $table->longText('column_bd')->comment('Comprehension: Informational Text Relative Placement')->nullable();
            $table->longText('column_be')->comment('Diagnostic Gain')->nullable();
            $table->longText('column_bf')->comment('Annual Typical Growth Measure')->nullable();
            $table->longText('column_bg')->comment('Annual Stretch Growth Measure')->nullable();
            $table->longText('column_bh')->comment('Percent Progress to Annual Typical Growth (%)')->nullable();
            $table->longText('column_bi')->comment('Percent Progress to Annual Stretch Growth (%)')->nullable();
            $table->longText('column_bj')->comment('Mid On Grade Level Scale Score')->nullable();
            $table->longText('column_bk')->comment('Reading Difficulty Indicator (Y/N)')->nullable();
            $table->longText('column_bl')->comment('504 Plan')->nullable();
            $table->longText('column_bm')->comment('English Language Acquisition')->nullable();
            $table->longText('column_bn')->comment('Foster Youth')->nullable();
            $table->longText('column_bo')->comment('Gifted and Talented (GATE)')->nullable();
            $table->longText('column_bp')->comment('Homeless Youth')->nullable();
            $table->longText('column_bq')->comment('Student with Disabilities')->nullable();
            $table->longText('column_br')->comment('Transitional Kindergarten')->nullable();
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
        //
    }
};
