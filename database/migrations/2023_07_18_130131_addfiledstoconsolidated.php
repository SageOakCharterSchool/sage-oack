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
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('Student ID')->nullable();
            $table->string('column_b')->comment('Student Last Name')->nullable();
            $table->string('column_c')->comment('Student First Name')->nullable();
            $table->string('column_d')->comment('Grade')->nullable();
            $table->string('column_e')->comment('SIS')->nullable();
            $table->text('column_f')->comment('Qualifying Subject 1')->nullable();
            $table->text('column_g')->comment('Teacher Name')->nullable();
            $table->text('column_h')->comment('Qualifying Subject 2')->nullable();
            $table->text('column_i')->comment('INTERVENTION PROGRAM RECOMMENDED ')->nullable();
            $table->text('column_j')->comment('INTERVENTION PROGRAM SELECTED')->nullable();
            $table->text('column_k')->comment('IREADY POINTS MATH FALL')->nullable();
            $table->text('column_l')->comment('IREADY RELATIVE PLACEMENT MATH FALL')->nullable();
            $table->text('column_m')->comment('IREADY LEVEL MATH FALL')->nullable();
            $table->text('column_n')->comment('IREADY POINTS READING FALL')->nullable();
            $table->text('column_o')->comment('IREADY RELATIVE PLACEMENT READING FALL')->nullable();
            $table->text('column_p')->comment('IREADY LEVEL READING FALL')->nullable();
            $table->text('column_q')->comment('IREADY POINTS MATH MID YEAR')->nullable();
            $table->text('column_r')->comment('IREADY RELATIVE PLACEMENT MATH MID YEAR')->nullable();
            $table->text('column_s')->comment('IREADY LEVEL MATH MID YEAR')->nullable();
            $table->text('column_t')->comment('IREADY POINTS READING MID YEAR')->nullable();
            $table->text('column_u')->comment('IREADY RELATIVE PLACEMENT READING MID YEAR')->nullable();
            $table->text('column_v')->comment('IREADY LEVEL READING MID YEAR')->nullable();
            $table->text('column_w')->comment('IREADY POINTS MATH END OF YEAR')->nullable();
            $table->text('column_x')->comment('IREADY RELATIVE PLACEMENT MATH END OF YEAR')->nullable();
            $table->text('column_y')->comment('IREADY LEVEL MATH END OF YEAR')->nullable();
            $table->text('column_z')->comment('IREADY POINTS READING END OF YEAR')->nullable();
            $table->text('column_aa')->comment('IREADY RELATIVE PLACEMENT READING END OF YAER')->nullable();
            $table->text('column_ab')->comment('IREADY LEVEL READING END OF YEAR')->nullable();
            $table->text('column_ac')->comment('IREADY GROWTH POINTS MATH MID YEAR')->nullable();
            $table->text('column_ad')->comment('IREADY LEVELS MATH GROWTH MID YEAR')->nullable();
            $table->text('column_ae')->comment('IREADY GROWTH POINTS READING MID YEAR')->nullable();
            $table->text('column_af')->comment('IREADY LEVELS READING GROWTH MID YEAR')->nullable();
            $table->text('column_ag')->comment('IREADY GROWTH POINTS MATH END OF YEAR')->nullable();
            $table->text('column_ah')->comment('IREADY LEVELS MATH GROWTH END OF YEAR')->nullable();
            $table->text('column_ai')->comment('IREADY GROWTH POINTS READING END OF YEAR')->nullable();
            $table->text('column_aj')->comment('IREADY LEVELS READING GROWTH END OF YEAR')->nullable();
            $table->text('column_ak')->comment('FLUENCY Percentile')->nullable();
            $table->text('column_al')->comment('VOCAB Percentile')->nullable();
            $table->text('column_am')->comment('PROF Passage Reading')->nullable();
            $table->text('column_an')->comment('letter name accuracy')->nullable();
            $table->text('column_ao')->comment('letter sound accuracy')->nullable();
            $table->text('column_ap')->comment('word accuracy')->nullable();
            $table->text('column_aq')->comment('phoneme accuracy')->nullable();
            $table->text('column_ar')->comment('READING RISK')->nullable();
            $table->text('column_as')->comment('PROF MATH PERCENTILE')->nullable();
            $table->text('column_at')->comment('MATH RISK')->nullable();
            $table->text('column_au')->comment('Progress Monitoring Test Given')->nullable();
            $table->text('column_av')->comment('Progress Monitoring Accuracy Percentile')->nullable();
            $table->text('column_aw')->comment('STAR Assessment Math Fall ')->nullable();
            $table->text('column_ax')->comment('STAR Assessment Reading Fall')->nullable();
            $table->text('column_ay')->comment('STAR Assessment Math Mid Year')->nullable();
            $table->text('column_az')->comment('STAR Assessment Reading Mid Year')->nullable();
            $table->text('column_ba')->comment('STAR Assessment Math End of Year')->nullable();
            $table->text('column_bb')->comment('STAR Assessment Reading End of Year')->nullable();
            $table->text('column_bc')->comment('STAR Assessment GROWTH Math Mid Year')->nullable();
            $table->text('column_bd')->comment('STAR Assessment GROWTH Reading Mid Year')->nullable();
            $table->text('column_be')->comment('STAR Assessment GROWTH Math End of Year')->nullable();
            $table->text('column_bf')->comment('STAR Assessment GROWTH Reading End of Year')->nullable();
            $table->text('column_bg')->comment('Intervention class attendance')->nullable();
            $table->text('column_bh')->comment('IREADY MINUTES MATH')->nullable();
            $table->text('column_bi')->comment('IREADY MINUTES READING')->nullable();
            $table->text('column_bj')->comment('FRECKLE MINUTES MATH')->nullable();
            $table->text('column_bk')->comment('FRECKLE MINUTES READING')->nullable();
            $table->text('column_bl')->comment('Read 180 Minutes')->nullable();
            $table->text('column_bm')->comment('Vmath Minutes')->nullable();
            $table->text('column_bn')->comment('Math 180 Minutes')->nullable();
            $table->text('column_bo')->comment('CLASS INFO')->nullable();
            $table->text('column_bp')->comment('Notes')->nullable();
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
