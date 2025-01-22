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
        Schema::create('i_ready_math_eoy_s', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('Last Name')->nullable();
            $table->string('column_b')->comment('First Name')->nullable();
            $table->string('column_c')->comment('Student ID')->nullable();
            $table->string('column_d')->comment('Enrolled')->nullable();
            $table->string('column_e')->comment('Student Grade')->nullable();
            $table->string('column_f')->comment('Academic Year')->nullable();
            $table->string('column_g')->comment('School')->nullable();
            $table->string('column_h')->comment('Subject')->nullable();
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
            $table->string('column_t')->comment('Number of Completed Diagnostics during the time frame')->nullable();
            $table->string('column_u')->comment('Annual Typical Growth Measure')->nullable();
            $table->string('column_v')->comment('Annual Stretch Growth Measure')->nullable();
            $table->string('column_w')->comment('Diagnostic Gain (Note: negative gains=zero)')->nullable();
            $table->longText('column_x')->comment('Diagnostic: Start Date (Most Recent)')->nullable();
            $table->longText('column_y')->comment('Diagnostic: Completion Date (Most Recent)')->nullable();
            $table->longText('column_z')->comment('Diagnostic: Time on Task (min) (Most Recent)')->nullable();
            $table->longText('column_aa')->comment('Diagnostic: Rush Flag (Most Recent)')->nullable();
            $table->longText('column_ab')->comment('Diagnostic: Overall Scale Score (Most Recent)')->nullable();
            $table->longText('column_ac')->comment('Diagnostic: Overall Placement (Most Recent)')->nullable();
            $table->longText('column_ad')->comment('Diagnostic: Percentile (Most Recent)')->nullable();
            $table->longText('column_ae')->comment('Diagnostic: Overall Relative Placement (Most Recent)')->nullable();
            $table->longText('column_af')->comment('Diagnostic: Tier (Most Recent)')->nullable();
            $table->longText('column_ag')->comment('Diagnostic: Quantile Measure (Most Recent)')->nullable();
            $table->longText('column_ah')->comment('Diagnostic: Quantile Range (Most Recent)')->nullable();
            $table->longText('column_ai')->comment('Diagnostic: Grouping (Most Recent)')->nullable();
            $table->longText('column_aj')->comment('Diagnostic: Start Date (1)')->nullable();
            $table->longText('column_ak')->comment('Diagnostic: Completion Date (1)')->nullable();
            $table->longText('column_al')->comment('Diagnostic: Time on Task (min) (1)')->nullable();
            $table->longText('column_am')->comment('Diagnostic: Rush Flag (1)')->nullable();
            $table->longText('column_an')->comment('Diagnostic: Overall Scale Score (1)')->nullable();
            $table->longText('column_ao')->comment('Diagnostic: Overall Placement (1)')->nullable();
            $table->longText('column_ap')->comment('Diagnostic: Percentile (1)')->nullable();
            $table->longText('column_aq')->comment('Diagnostic: Overall Relative Placement (1)')->nullable();
            $table->longText('column_ar')->comment('Diagnostic: Tier (1)')->nullable();
            $table->longText('column_as')->comment('Diagnostic: Start Date (2)')->nullable();
            $table->longText('column_at')->comment('Diagnostic: Completion Date (2)')->nullable();
            $table->longText('column_au')->comment('Diagnostic: Time on Task (min) (2)')->nullable();
            $table->longText('column_av')->comment('Diagnostic: Rush Flag (2)')->nullable();
            $table->longText('column_aw')->comment('Diagnostic: Overall Scale Score (2)')->nullable();
            $table->longText('column_ax')->comment('Diagnostic: Overall Placement (2)')->nullable();
            $table->longText('column_ay')->comment('Diagnostic: Percentile (2)')->nullable();
            $table->longText('column_az')->comment('Diagnostic: Overall Relative Placement (2)')->nullable();
            $table->longText('column_ba')->comment('Diagnostic: Tier (2)')->nullable();
            $table->longText('column_bb')->comment('Diagnostic: Start Date (3)')->nullable();
            $table->longText('column_bc')->comment('Diagnostic: Completion Date (3)')->nullable();
            $table->longText('column_bd')->comment('Diagnostic: Time on Task (min) (3)')->nullable();
            $table->longText('column_be')->comment('Diagnostic: Rush Flag (3)')->nullable();
            $table->longText('column_bf')->comment('Diagnostic: Overall Scale Score (3)')->nullable();
            $table->longText('column_bg')->comment('Diagnostic: Overall Placement (3)')->nullable();
            $table->longText('column_bh')->comment('Diagnostic: Percentile (3)')->nullable();
            $table->longText('column_bi')->comment('Diagnostic: Overall Relative Placement (3)')->nullable();
            $table->longText('column_bj')->comment('Diagnostic: Tier (3)')->nullable();
            $table->longText('column_bk')->comment('Diagnostic: Start Date (4)')->nullable();
            $table->longText('column_bl')->comment('Diagnostic: Completion Date (4)')->nullable();
            $table->longText('column_bm')->comment('Diagnostic: Time on Task (min) (4)')->nullable();
            $table->longText('column_bn')->comment('Diagnostic: Rush Flag (4)')->nullable();
            $table->longText('column_bo')->comment('Diagnostic: Overall Scale Score (4)')->nullable();
            $table->longText('column_bp')->comment('Diagnostic: Overall Placement (4)')->nullable();
            $table->longText('column_bq')->comment('Diagnostic: Percentile (4)')->nullable();
            $table->longText('column_br')->comment('Diagnostic: Overall Relative Placement (4)')->nullable();
            $table->longText('column_bs')->comment('Diagnostic: Tier (4)')->nullable();
            $table->longText('column_bt')->comment('Diagnostic: Start Date (5)')->nullable();
            $table->longText('column_bu')->comment('Diagnostic: Completion Date (5)')->nullable();
            $table->longText('column_bv')->comment('Diagnostic: Time on Task (min) (5)')->nullable();
            $table->longText('column_bw')->comment('Diagnostic: Rush Flag (5)')->nullable();
            $table->longText('column_bx')->comment('Diagnostic: Overall Scale Score (5)')->nullable();
            $table->longText('column_by')->comment('Diagnostic: Overall Placement (5)')->nullable();
            $table->longText('column_bz')->comment('Diagnostic: Percentile (5)')->nullable();
            $table->longText('column_ca')->comment('Diagnostic: Overall Relative Placement (5)')->nullable();
            $table->longText('column_cb')->comment('Diagnostic: Tier (5)')->nullable();
            $table->longText('column_cc')->comment('Instruction: Overall Lessons Passed')->nullable();
            $table->longText('column_cd')->comment('Instruction: Overall Lessons Not Passed')->nullable();
            $table->longText('column_ce')->comment('Instruction: Overall Lessons Completed')->nullable();
            $table->longText('column_cf')->comment('Instruction: Overall Pass Rate (%)')->nullable();
            $table->longText('column_cg')->comment('Instruction: Overall Time on Task (min)')->nullable();

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
        Schema::dropIfExists('i_ready_math_e_o_y_s');
    }
};
