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
        Schema::create('tutor', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id')->nullable();
            $table->string('cycle_id')->comment('cycle_id')->nullable();
            $table->string('teacher_id')->comment('teacher_id')->nullable();
            $table->string('column_a')->comment('user id')->nullable();
            $table->string('column_b')->comment('First Name')->nullable();
            $table->string('column_c')->comment('Last Name')->nullable();
            $table->string('column_d')->comment('Email')->nullable();
            $table->string('column_e')->comment('Username')->nullable();
            $table->string('column_f')->comment('Access Point')->nullable();
            $table->string('column_g')->comment('Start Date')->nullable();
            $table->string('column_h')->comment('Total Minutes Used')->nullable();
            $table->string('column_i')->comment('Minutes Used this period')->nullable();
            $table->string('column_j')->comment('Total Sessions')->nullable();
            $table->string('column_k')->comment('Sessions this period')->nullable();
            $table->string('column_l')->comment('Total Early Alerts')->nullable();
            $table->string('column_m')->comment('Early Alerts this period')->nullable();
            $table->string('column_n')->comment('Subjects')->nullable();
            $table->string('column_o')->comment('Total Minutes Used')->nullable();
            $table->string('column_p')->comment('Minutes Used this period')->nullable();
            $table->string('column_q')->comment('Total Sessions')->nullable();
            $table->string('column_r')->comment('Sessions this period')->nullable();
            $table->string('column_s')->comment('Total Early Alerts')->nullable();
            $table->string('column_t')->comment('Early Alerts this period')->nullable();
            $table->string('column_u')->comment('SSID')->nullable();

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
        Schema::dropIfExists('tutors');
    }
};
