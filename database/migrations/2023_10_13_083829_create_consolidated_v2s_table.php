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
        Schema::create('consolidated_v2', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id')->nullable();
            $table->string('cycle_id')->index()->comment('cycle_id')->nullable();
            $table->string('teacher_id')->index()->comment('teacher_id')->nullable();
            $table->text('column_a')->comment('id')->nullable();
            $table->text('column_b')->comment('teacher_id')->nullable();
            $table->text('column_c')->comment('cycle_id')->nullable();
            $table->text('column_d')->comment('Student ID')->nullable();
            $table->text('column_e')->comment('Student Last Name')->nullable();
            $table->text('column_f')->comment('Student First Name')->nullable();
            $table->text('column_g')->comment('Grade')->nullable();
            $table->text('column_h')->comment('SIS')->nullable();
            $table->text('column_i')->comment('Program')->nullable();
            $table->text('column_j')->comment('Qualifying Subject 1')->nullable();
            $table->text('column_k')->comment('Teacher Name')->nullable();
            $table->text('column_l')->comment('Qualifying Subject 2')->nullable();
            $table->text('column_m')->comment('INTERVENTION PROGRAM RECOMMENDED')->nullable();
            $table->text('column_n')->comment('INTERVENTION PROGRAM RECOMMENDED')->nullable();
            $table->text('column_o')->comment('INTERVENTION PROGRAM SELECTED')->nullable();
            $table->text('column_p')->comment('INTERVENTION PROGRAM SELECTED')->nullable();
            $table->text('column_q')->comment('IREADY POINTS MATH FALL')->nullable();
            $table->text('column_r')->comment('IREADY RELATIVE PLACEMENT MATH FALL')->nullable();
            $table->text('column_s')->comment('IREADY LEVEL MATH FALL')->nullable();
            $table->text('column_t')->comment('IREADY POINTS READING FALL')->nullable();
            $table->text('column_u')->comment('IREADY RELATIVE PLACEMENT READING FALL')->nullable();
            $table->text('column_v')->comment('IREADY LEVEL READING FALL')->nullable();
            $table->text('column_w')->comment('IREADY POINTS MATH MID YEAR')->nullable();
            $table->text('column_x')->comment('IREADY RELATIVE PLACEMENT MATH MID YEAR')->nullable();
            $table->text('column_y')->comment('IREADY LEVEL MATH MID YEAR')->nullable();
            $table->text('column_z')->comment('IREADY POINTS READING MID YEAR')->nullable();
            $table->text('column_aa')->comment('IREADY RELATIVE PLACEMENT READING MID YEAR')->nullable();
            $table->text('column_ab')->comment('IREADY LEVEL READING MID YEAR')->nullable();
            $table->text('column_ac')->comment('IREADY POINTS MATH END OF YEAR')->nullable();
            $table->text('column_ad')->comment('IREADY RELATIVE PLACEMENT MATH END OF YEAR')->nullable();
            $table->text('column_ae')->comment('IREADY LEVEL MATH END OF YEAR')->nullable();
            $table->text('column_af')->comment('IREADY POINTS READING END OF YEAR')->nullable();
            $table->text('column_ag')->comment('IREADY RELATIVE PLACEMENT READING END OF YAER')->nullable();
            $table->text('column_ah')->comment('IREADY LEVEL READING END OF YEAR')->nullable();
            $table->text('column_ai')->comment('IREADY GROWTH POINTS MATH MID YEAR')->nullable();
            $table->text('column_aj')->comment('IREADY LEVELS MATH GROWTH MID YEAR')->nullable();
            $table->text('column_ak')->comment('IREADY GROWTH POINTS READING MID YEAR')->nullable();
            $table->text('column_al')->comment('IREADY LEVELS READING GROWTH MID YEAR')->nullable();
            $table->text('column_am')->comment('IREADY GROWTH POINTS MATH END OF YEAR')->nullable();
            $table->text('column_an')->comment('IREADY LEVELS MATH GROWTH END OF YEAR')->nullable();
            $table->text('column_ao')->comment('IREADY GROWTH POINTS READING END OF YEAR')->nullable();
            $table->text('column_ap')->comment('IREADY LEVELS READING GROWTH END OF YEAR')->nullable();
            $table->text('column_aq')->comment('FLUENCY Percentile')->nullable();
            $table->text('column_ar')->comment('VOCAB Percentile')->nullable();
            $table->text('column_as')->comment('PROF Passage Reading')->nullable();
            $table->text('column_at')->comment('letter name accuracy')->nullable();
            $table->text('column_au')->comment('letter sound accuracy')->nullable();
            $table->text('column_av')->comment('word accuracy')->nullable();
            $table->text('column_aw')->comment('phoneme accuracy')->nullable();
            $table->text('column_ax')->comment('READING RISK')->nullable();
            $table->text('column_ay')->comment('PROF MATH PERCENTILE')->nullable();
            $table->text('column_az')->comment('MATH RISK')->nullable();
            $table->text('column_ba')->comment('Progress Monitoring Test Given')->nullable();
            $table->text('column_bb')->comment('Progress Monitoring Accuracy Percentile')->nullable();
            $table->text('column_bc')->comment('STAR Assessment Math Fall')->nullable();
            $table->text('column_bd')->comment('STAR Assessment Reading Fall')->nullable();
            $table->text('column_be')->comment('STAR Assessment Math Mid Year')->nullable();
            $table->text('column_bf')->comment('STAR Assessment Reading Mid Year')->nullable();
            $table->text('column_bg')->comment('STAR Assessment Math End of Year')->nullable();
            $table->text('column_bh')->comment('STAR Assessment Reading End of Year')->nullable();
            $table->text('column_bi')->comment('STAR Assessment GROWTH Math Mid Year')->nullable();
            $table->text('column_bj')->comment('STAR Assessment GROWTH Reading Mid Year')->nullable();
            $table->text('column_bk')->comment('STAR Assessment GROWTH Math End of Year')->nullable();
            $table->text('column_bl')->comment('STAR Assessment GROWTH Reading End of Year')->nullable();
            $table->text('column_bm')->comment('Intervention class attendance')->nullable();
            $table->text('column_bn')->comment('Intervention class attendance')->nullable();
            $table->text('column_bo')->comment('IREADY MINUTES MATH')->nullable();
            $table->text('column_bp')->comment('IREADY MINUTES READING')->nullable();
            $table->text('column_bq')->comment('FRECKLE MINUTES MATH')->nullable();
            $table->text('column_br')->comment('FRECKLE MINUTES READING')->nullable();
            $table->text('column_bs')->comment('Read 180 Minutes')->nullable();
            $table->text('column_bt')->comment('Vmath Minutes')->nullable();
            $table->text('column_bu')->comment('Math 180 Minutes')->nullable();
            $table->text('column_bv')->comment('CLASS INFO')->nullable();
            $table->text('column_bw')->comment('CLASS INFO')->nullable();
            $table->text('column_bx')->comment('Notes')->nullable();
            $table->text('column_by')->comment('Notes')->nullable();

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
        Schema::dropIfExists('consolidated_v2s');
    }
};
