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

        Schema::table('consolidateds', function (Blueprint $table) {
            $table->dropColumn('column_a');
            $table->dropColumn('column_b');
            $table->dropColumn('column_c');
            $table->dropColumn('column_d');
            $table->dropColumn('column_e');
            $table->dropColumn('column_f');
            $table->dropColumn('column_g');
            $table->dropColumn('column_h');
            $table->dropColumn('column_i');
            $table->dropColumn('column_j');
            $table->dropColumn('column_k');
            $table->dropColumn('column_l');
            $table->dropColumn('column_m');
            $table->dropColumn('column_n');
            $table->dropColumn('column_o');
            $table->dropColumn('column_p');
            $table->dropColumn('column_q');
            $table->dropColumn('column_r');
            $table->dropColumn('column_s');
            $table->dropColumn('column_t');
            $table->dropColumn('column_u');
            $table->dropColumn('column_v');
            $table->dropColumn('column_w');
            $table->dropColumn('column_x');
            $table->dropColumn('column_y');
            $table->dropColumn('column_z');
            $table->dropColumn('column_aa');
            $table->dropColumn('column_ab');
            $table->dropColumn('column_ac');
            $table->dropColumn('column_ad');
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
        });
        Schema::table('consolidateds', function (Blueprint $table) {
            $table->string('column_a')->comment('Student ID')->nullable();
            $table->string('column_b')->comment('Student Last Name')->nullable();
            $table->string('column_c')->comment('Student First Name')->nullable();
            $table->string('column_d')->comment('Grade')->nullable();
            $table->string('column_e')->comment('SIS')->nullable();
            $table->longText('column_f')->comment('Qualifying Subject 1')->nullable();
            $table->longText('column_g')->comment('Teacher Name')->nullable();
            $table->longText('column_h')->comment('Qualifying Subject 2')->nullable();
            $table->longText('column_i')->comment('INTERVENTION PROGRAM RECOMMENDED ')->nullable();
            $table->longText('column_j')->comment('INTERVENTION PROGRAM RECOMMENDED ')->nullable();
            $table->longText('column_k')->comment('INTERVENTION PROGRAM SELECTED')->nullable();
            $table->longText('column_l')->comment('INTERVENTION PROGRAM SELECTED')->nullable();
            $table->longText('column_m')->comment('IREADY POINTS MATH FALL')->nullable();
            $table->longText('column_n')->comment('IREADY RELATIVE PLACEMENT MATH FALL')->nullable();
            $table->longText('column_o')->comment('IREADY LEVEL MATH FALL')->nullable();
            $table->longText('column_p')->comment('IREADY POINTS READING FALL')->nullable();
            $table->longText('column_q')->comment('IREADY RELATIVE PLACEMENT READING FALL')->nullable();
            $table->longText('column_r')->comment('IREADY LEVEL READING FALL')->nullable();
            $table->longText('column_s')->comment('IREADY POINTS MATH MID YEAR')->nullable();
            $table->longText('column_t')->comment('IREADY RELATIVE PLACEMENT MATH MID YEAR')->nullable();
            $table->longText('column_u')->comment('IREADY LEVEL MATH MID YEAR')->nullable();
            $table->longText('column_v')->comment('IREADY POINTS READING MID YEAR')->nullable();
            $table->longText('column_w')->comment('IREADY RELATIVE PLACEMENT READING MID YEAR')->nullable();
            $table->longText('column_x')->comment('IREADY LEVEL READING MID YEAR')->nullable();
            $table->longText('column_y')->comment('IREADY POINTS MATH END OF YEAR')->nullable();
            $table->longText('column_z')->comment('IREADY RELATIVE PLACEMENT MATH END OF YEAR')->nullable();
            $table->longText('column_aa')->comment('IREADY LEVEL MATH END OF YEAR')->nullable();
            $table->longText('column_ab')->comment('IREADY POINTS READING END OF YEAR')->nullable();
            $table->longText('column_ac')->comment('IREADY RELATIVE PLACEMENT READING END OF YAER')->nullable();
            $table->longText('column_ad')->comment('IREADY LEVEL READING END OF YEAR')->nullable();
            $table->longText('column_ae')->comment('IREADY GROWTH POINTS MATH MID YEAR')->nullable();
            $table->longText('column_af')->comment('IREADY LEVELS MATH GROWTH MID YEAR')->nullable();
            $table->longText('column_ag')->comment('IREADY GROWTH POINTS READING MID YEAR')->nullable();
            $table->longText('column_ah')->comment('IREADY LEVELS READING GROWTH MID YEAR')->nullable();
            $table->longText('column_ai')->comment('IREADY GROWTH POINTS MATH END OF YEAR')->nullable();
            $table->longText('column_aj')->comment('IREADY LEVELS MATH GROWTH END OF YEAR')->nullable();
            $table->longText('column_ak')->comment('IREADY GROWTH POINTS READING END OF YEAR')->nullable();
            $table->longText('column_al')->comment('IREADY LEVELS READING GROWTH END OF YEAR')->nullable();
            $table->longText('column_am')->comment('FLUENCY Percentile')->nullable();
            $table->longText('column_an')->comment('VOCAB Percentile')->nullable();
            $table->longText('column_ao')->comment('PROF Passage Reading')->nullable();
            $table->longText('column_ap')->comment('letter name accuracy')->nullable();
            $table->longText('column_aq')->comment('letter sound accuracy')->nullable();
            $table->longText('column_ar')->comment('word accuracy')->nullable();
            $table->longText('column_as')->comment('phoneme accuracy')->nullable();
            $table->longText('column_at')->comment('READING RISK')->nullable();
            $table->longText('column_au')->comment('PROF MATH PERCENTILE')->nullable();
            $table->longText('column_av')->comment('MATH RISK')->nullable();
            $table->longText('column_aw')->comment('Progress Monitoring Test Given')->nullable();
            $table->longText('column_ax')->comment('Progress Monitoring Accuracy Percentile')->nullable();
            $table->longText('column_ay')->comment('STAR Assessment Math Fall ')->nullable();
            $table->longText('column_az')->comment('STAR Assessment Reading Fall')->nullable();
            $table->longText('column_ba')->comment('STAR Assessment Math Mid Year')->nullable();
            $table->longText('column_bb')->comment('STAR Assessment Reading Mid Year')->nullable();
            $table->longText('column_bc')->comment('STAR Assessment Math End of Year')->nullable();
            $table->longText('column_bd')->comment('STAR Assessment Reading End of Year')->nullable();
            $table->longText('column_be')->comment('STAR Assessment GROWTH Math Mid Year')->nullable();
            $table->longText('column_bf')->comment('STAR Assessment GROWTH Reading Mid Year')->nullable();
            $table->longText('column_bg')->comment('STAR Assessment GROWTH Math End of Year')->nullable();
            $table->longText('column_bh')->comment('STAR Assessment GROWTH Reading End of Year')->nullable();
            $table->longText('column_bi')->comment('Intervention class attendance')->nullable();
            $table->longText('column_bj')->comment('IREADY MINUTES MATH')->nullable();
            $table->longText('column_bk')->comment('IREADY MINUTES READING')->nullable();
            $table->longText('column_bl')->comment('FRECKLE MINUTES MATH')->nullable();
            $table->longText('column_bm')->comment('FRECKLE MINUTES READING')->nullable();
            $table->longText('column_bn')->comment('Read 180 Minutes')->nullable();
            $table->longText('column_bo')->comment('Vmath Minutes')->nullable();
            $table->longText('column_bp')->comment('Math 180 Minutes')->nullable();
            $table->longText('column_bq')->comment('CLASS INFO')->nullable();
            $table->longText('column_br')->comment('Notes')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consolidateds', function (Blueprint $table) {
            //
        });
    }
};
