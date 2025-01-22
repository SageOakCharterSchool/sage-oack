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
        Schema::table('i_ready_reading_mid_years', function (Blueprint $table) {
            $table->dropColumn('column_a'); //Grade
            $table->dropColumn('column_b'); //Student
            $table->dropColumn('column_c'); //Assignment Type
            $table->dropColumn('column_d'); //Growth Proficiency Category
            $table->dropColumn('column_e'); //SGP (Expectation=50)
            $table->dropColumn('column_f'); //Test 1 Test Type
            $table->dropColumn('column_g'); //Test 1 Test Date
            $table->dropColumn('column_h'); //Test 1 Test Duration
            $table->dropColumn('column_i'); //Test 1 SS
            $table->dropColumn('column_j'); //Test 1 Benchmark Category
            $table->dropColumn('column_k'); //Test 1 PR
            $table->dropColumn('column_l'); //Test 1 NCE
            $table->dropColumn('column_m'); //Test 2 Test Type
            $table->dropColumn('column_n'); //Test 2 Test Date
            $table->dropColumn('column_o'); //Test 2 Test Duration
            $table->dropColumn('column_p'); //Test 2 SS
            $table->dropColumn('column_q'); //Test 2 Benchmark Category
            $table->dropColumn('column_r'); //Test 2 PR
            $table->dropColumn('column_s'); //Test 2 NCE
            $table->dropColumn('column_t'); //Test 3 Test Type
            $table->dropColumn('column_u'); //Test 3 Test Date
            $table->dropColumn('column_v'); //Test 3 Test Duration
            $table->dropColumn('column_w'); //Test 3 SS
            $table->dropColumn('column_x'); //Test 3 Benchmark Category
            $table->dropColumn('column_y'); //Test 3 PR
            $table->dropColumn('column_z'); //Test 3 NCE
            $table->dropColumn('column_aa'); //Latest Change in Score
            $table->dropColumn('column_ab'); //Latest Change in PR
            $table->dropColumn('column_ac'); //Latest Change in NCE
            $table->dropColumn('column_ad'); //Student Id
            $table->dropColumn('column_ae');
            $table->dropColumn('column_af');
            $table->dropColumn('column_ag');
            $table->dropColumn('column_ah');
            $table->dropColumn('column_ai');
            $table->dropColumn('column_aj');
            $table->dropColumn('column_ak');
            $table->dropColumn('column_al');
            $table->dropColumn('column_am');
            $table->dropColumn('column_an');
            $table->dropColumn('column_ao');
            $table->dropColumn('column_ap');
            $table->dropColumn('column_aq');
            $table->dropColumn('column_ar');
            $table->dropColumn('column_as');
            $table->dropColumn('column_at');
            $table->dropColumn('column_au');
            $table->dropColumn('column_av');
            $table->dropColumn('column_aw');
            $table->dropColumn('column_ax');
            $table->dropColumn('column_ay');
            $table->dropColumn('column_az');
            $table->dropColumn('column_ba');
            $table->dropColumn('column_bb');
            $table->dropColumn('column_bc');
            $table->dropColumn('column_bd');
            $table->dropColumn('column_be');
            $table->dropColumn('column_bf');
            $table->dropColumn('column_bg');
            $table->dropColumn('column_bh');
            $table->dropColumn('column_bi');
            $table->dropColumn('column_bj');
            $table->dropColumn('column_bk');
            $table->dropColumn('column_bl');
            $table->dropColumn('column_bm');
            $table->dropColumn('column_bn');
            $table->dropColumn('column_bo');
            $table->dropColumn('column_bp');
            $table->dropColumn('column_bq');
            $table->dropColumn('column_br');
            $table->dropColumn('column_bs');
            $table->dropColumn('column_bt');
            $table->dropColumn('column_bu');
            $table->dropColumn('column_bv');
            $table->dropColumn('column_bw');
            $table->dropColumn('column_bx');
            $table->dropColumn('column_by');
            $table->dropColumn('column_bz');
            $table->dropColumn('column_ca');
            $table->dropColumn('column_cb');
            $table->dropColumn('column_cc');
            $table->dropColumn('column_cd');
            $table->dropColumn('column_ce');
            $table->dropColumn('column_cf');
            $table->dropColumn('column_cg');
        });

        Schema::table('i_ready_reading_mid_years', function (Blueprint $table) {
            $table->string('column_a')->comment('Last Name')->nullable();
            $table->string('column_b')->comment('First Name')->nullable();
            $table->string('column_c')->comment('Student ID')->nullable();
            $table->string('column_d')->comment('Student Grade')->nullable();
            $table->string('column_e')->comment('Academic Year')->nullable();
            $table->string('column_f')->comment('School')->nullable();
            $table->string('column_g')->comment('Enrolled')->nullable();
            $table->string('column_h')->comment('District State ID')->nullable();
            $table->string('column_i')->comment('Account State ID')->nullable();
            $table->string('column_j')->comment('School State ID')->nullable();
            $table->string('column_k')->comment('Student State ID')->nullable();
            $table->string('column_l')->comment('User Name')->nullable();
            $table->string('column_m')->comment('Sex')->nullable();
            $table->string('column_n')->comment('Hispanic or Latino')->nullable();
            $table->string('column_o')->comment('Race')->nullable();
            $table->string('column_p')->comment('English Language Learner')->nullable();
            $table->string('column_q')->comment('Special Education')->nullable();
            $table->string('column_r')->comment('Economically Disadvantaged')->nullable();
            $table->string('column_s')->comment('Migrant')->nullable();
            $table->string('column_t')->comment('Class(es)')->nullable();
            $table->string('column_u')->comment('Class Teacher(s)')->nullable();
            $table->string('column_v')->comment('Report Group(s)')->nullable();
            $table->string('column_w')->comment('Start Date')->nullable();
            $table->string('column_x')->comment('Completion Date')->nullable();
            $table->string('column_y')->comment('Baseline Diagnostic (Y/N)')->nullable();
            $table->string('column_z')->comment('Most Recent Diagnostic YTD (Y/N)')->nullable();
            $table->string('column_aa')->comment('Duration (min)')->nullable();
            $table->string('column_ab')->comment('Rush Flag')->nullable();
            $table->string('column_ac')->comment('Overall Scale Score')->nullable();
            $table->string('column_ad')->comment('Overall Placement')->nullable();
            $table->string('column_ae')->comment('Overall Relative Placement')->nullable();
            $table->string('column_af')->comment('Percentile')->nullable();
            $table->string('column_ag')->comment('Grouping')->nullable();
            $table->string('column_ah')->comment('Lexile Measure')->nullable();
            $table->string('column_ai')->comment('Lexile Range')->nullable();
            $table->string('column_aj')->comment('Phonological Awareness Scale Score')->nullable();
            $table->string('column_ak')->comment('Phonological Awareness Placement')->nullable();
            $table->string('column_al')->comment('Phonological Awareness Relative Placement')->nullable();
            $table->string('column_am')->comment('Phonics Scale Score')->nullable();
            $table->string('column_an')->comment('Phonics Placement')->nullable();
            $table->string('column_ao')->comment('Phonics Relative Placement')->nullable();
            $table->string('column_ap')->comment('High-Frequency Words Scale Score')->nullable();
            $table->string('column_aq')->comment('High-Frequency Words Placement')->nullable();
            $table->string('column_ar')->comment('High-Frequency Words Relative Placement')->nullable();
            $table->string('column_as')->comment('Vocabulary Scale Score')->nullable();
            $table->string('column_at')->comment('Vocabulary Placement')->nullable();
            $table->string('column_au')->comment('Vocabulary Relative Placement')->nullable();
            $table->string('column_av')->comment('Comprehension: Overall Scale Score')->nullable();
            $table->string('column_aw')->comment('Comprehension: Overall Placement')->nullable();
            $table->string('column_ax')->comment('Comprehension: Overall Relative Placement')->nullable();
            $table->string('column_ay')->comment('Comprehension: Literature Scale Score')->nullable();
            $table->string('column_az')->comment('Comprehension: Literature Placement')->nullable();
            $table->string('column_ba')->comment('Comprehension: Literature Relative Placement')->nullable();
            $table->string('column_bb')->comment('Comprehension: Informational Text Scale Score')->nullable();
            $table->string('column_bc')->comment('Comprehension: Informational Text Placement')->nullable();
            $table->string('column_bd')->comment('Comprehension: Informational Text Relative Placement')->nullable();
            $table->string('column_be')->comment('Diagnostic Gain')->nullable();
            $table->string('column_bf')->comment('Annual Typical Growth Measure')->nullable();
            $table->string('column_bg')->comment('Annual Stretch Growth Measure')->nullable();
            $table->string('column_bh')->comment('Percent Progress to Annual Typical Growth (%)')->nullable();
            $table->string('column_bi')->comment('Percent Progress to Annual Stretch Growth (%)')->nullable();
            $table->string('column_bj')->comment('Mid On Grade Level Scale Score')->nullable();
            $table->string('column_bk')->comment('Reading Difficulty Indicator (Y/N)')->nullable();
            $table->string('column_bl')->comment('504 Plan')->nullable();
            $table->string('column_bm')->comment('English Language Acquisition')->nullable();
            $table->string('column_bn')->comment('Foster Youth')->nullable();
            $table->string('column_bo')->comment('Gifted and Talented (GATE)')->nullable();
            $table->string('column_bp')->comment('Homeless Youth')->nullable();
            $table->string('column_bq')->comment('Student with Disabilities')->nullable();
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
