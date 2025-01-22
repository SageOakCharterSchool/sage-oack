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
        Schema::create('consolidate3s', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id')->nullable();
            $table->string('cycle_id')->comment('cycle_id')->nullable()->index();
            $table->string('teacher_id')->comment('teacher_id')->nullable()->index();
            $table->string('column_a')->comment('id')->nullable();
            $table->string('column_b')->comment('teacher_id')->nullable();
            $table->string('column_c')->comment('cycle_id')->nullable();
            $table->string('column_d')->comment('Student ID')->nullable();
            $table->string('column_e')->comment('Student Last Name')->nullable();
            $table->string('column_f')->comment('Student First Name')->nullable();
            $table->string('column_g')->comment('Grade')->nullable();
            $table->string('column_h')->comment('SIS')->nullable();
            $table->string('column_i')->comment('Qualifying Subject 1')->nullable();
            $table->string('column_j')->comment('Teacher Name')->nullable();
            $table->longText('column_k')->comment('Qualifying Subject 2')->nullable();
            $table->longText('column_l')->comment('INTERVENTION PROGRAM RECOMMENDED')->nullable();
            $table->longText('column_m')->comment('INTERVENTION PROGRAM RECOMMENDED')->nullable();
            $table->longText('column_n')->comment('INTERVENTION PROGRAM SELECTED')->nullable();
            $table->longText('column_o')->comment('INTERVENTION PROGRAM SELECTED')->nullable();
            $table->longText('column_p')->comment('IREADY POINTS MATH FALL')->nullable();
            $table->longText('column_q')->comment('IREADY RELATIVE PLACEMENT MATH FALL')->nullable();
            $table->longText('column_r')->comment('IREADY LEVEL MATH FALL')->nullable();
            $table->longText('column_s')->comment('IREADY POINTS READING FALL')->nullable();
            $table->longText('column_t')->comment('IREADY RELATIVE PLACEMENT READING FALL')->nullable();
            $table->longText('column_u')->comment('IREADY LEVEL READING FALL')->nullable();
            $table->longText('column_v')->comment('IREADY POINTS MATH MID YEAR')->nullable();
            $table->longText('column_w')->comment('IREADY RELATIVE PLACEMENT MATH MID YEAR')->nullable();
            $table->longText('column_x')->comment('IREADY LEVEL MATH MID YEAR')->nullable();
            $table->longText('column_y')->comment('IREADY POINTS READING MID YEAR')->nullable();
            $table->longText('column_z')->comment('IREADY RELATIVE PLACEMENT READING MID YEAR')->nullable();
            $table->longText('column_aa')->comment('IREADY LEVEL READING MID YEAR')->nullable();
            $table->longText('column_ab')->comment('IREADY POINTS MATH END OF YEAR')->nullable();
            $table->longText('column_ac')->comment('IREADY RELATIVE PLACEMENT MATH END OF YEAR')->nullable();
            $table->longText('column_ad')->comment('IREADY LEVEL MATH END OF YEAR')->nullable();
            $table->longText('column_ae')->comment('IREADY POINTS READING END OF YEAR')->nullable();
            $table->longText('column_af')->comment('IREADY RELATIVE PLACEMENT READING END OF YAER')->nullable();
            $table->longText('column_ag')->comment('IREADY LEVEL READING END OF YEAR')->nullable();
            $table->longText('column_ah')->comment('IREADY GROWTH POINTS MATH MID YEAR')->nullable();
            $table->longText('column_ai')->comment('IREADY LEVELS MATH GROWTH MID YEAR')->nullable();
            $table->longText('column_aj')->comment('IREADY GROWTH POINTS READING MID YEAR')->nullable();
            $table->longText('column_ak')->comment('IREADY LEVELS READING GROWTH MID YEAR')->nullable();
            $table->longText('column_al')->comment('IREADY GROWTH POINTS MATH END OF YEAR')->nullable();
            $table->longText('column_am')->comment('IREADY LEVELS MATH GROWTH END OF YEAR')->nullable();
            $table->longText('column_an')->comment('IREADY GROWTH POINTS READING END OF YEAR')->nullable();
            $table->longText('column_ao')->comment('IREADY LEVELS READING GROWTH END OF YEAR')->nullable();
            $table->longText('column_ap')->comment('FLUENCY Percentile')->nullable();
            $table->longText('column_aq')->comment('VOCAB Percentile')->nullable();
            $table->longText('column_ar')->comment('PROF Passage Reading')->nullable();
            $table->longText('column_as')->comment('letter name accuracy')->nullable();
            $table->longText('column_at')->comment('letter sound accuracy')->nullable();
            $table->longText('column_au')->comment('word accuracy')->nullable();
            $table->longText('column_av')->comment('phoneme accuracy')->nullable();
            $table->longText('column_aw')->comment('READING RISK')->nullable();
            $table->longText('column_ax')->comment('PROF MATH PERCENTILE')->nullable();
            $table->longText('column_ay')->comment('MATH RISK')->nullable();
            $table->longText('column_az')->comment('Progress Monitoring Test Given')->nullable();
            $table->longText('column_ba')->comment('Progress Monitoring Accuracy Percentile')->nullable();
            $table->longText('column_bb')->comment('STAR Assessment Math Fall')->nullable();
            $table->longText('column_bc')->comment('STAR Assessment Reading Fall')->nullable();
            $table->longText('column_bd')->comment('STAR Assessment Math Mid Year')->nullable();
            $table->longText('column_be')->comment('STAR Assessment Reading Mid Year')->nullable();
            $table->longText('column_bf')->comment('STAR Assessment Math End of Year')->nullable();
            $table->longText('column_bg')->comment('STAR Assessment Reading End of Year')->nullable();
            $table->longText('column_bh')->comment('STAR Assessment GROWTH Math Mid Year')->nullable();
            $table->longText('column_bi')->comment('STAR Assessment GROWTH Reading Mid Year')->nullable();
            $table->longText('column_bj')->comment('STAR Assessment GROWTH Math End of Year')->nullable();
            $table->longText('column_bk')->comment('STAR Assessment GROWTH Reading End of Year')->nullable();
            $table->longText('column_bl')->comment('Intervention class attendance')->nullable();
            $table->longText('column_bm')->comment('IREADY MINUTES MATH')->nullable();
            $table->longText('column_bn')->comment('IREADY MINUTES READING')->nullable();
            $table->longText('column_bo')->comment('FRECKLE MINUTES MATH')->nullable();
            $table->longText('column_bp')->comment('FRECKLE MINUTES READING')->nullable();
            $table->longText('column_bq')->comment('Read 180 Minutes')->nullable();
            $table->longText('column_br')->comment('Vmath Minutes')->nullable();
            $table->longText('column_bs')->comment('Math 180 Minutes')->nullable();
            $table->longText('column_bt')->comment('CLASS INFO')->nullable();
            $table->longText('column_bu')->comment('Notes')->nullable();
            $table->longText('column_bv')->comment('transmath minutes')->nullable();
            $table->longText('column_bw')->comment('SST')->nullable();
            $table->longText('column_bx')->comment('sped')->nullable();
            $table->longText('column_by')->comment('ELD')->nullable();
            $table->longText('column_bz')->comment('Options')->nullable();

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
        Schema::dropIfExists('consolidate3s');
    }
};
