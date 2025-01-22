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
        Schema::create('star_fall_maths', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('Grade')->nullable();
            $table->string('column_b')->comment('Student')->nullable();
            $table->string('column_c')->comment('Assignment Type')->nullable();
            $table->string('column_d')->comment('Growth Proficiency Category')->nullable();
            $table->string('column_e')->comment('SGP (Expectation=50)')->nullable();
            $table->string('column_f')->comment('Test 1 Test Type')->nullable();
            $table->string('column_g')->comment('Test 1 Test Date')->nullable();
            $table->string('column_h')->comment('Test 1 Test Duration')->nullable();
            $table->string('column_i')->comment('Test 1 SS')->nullable();
            $table->string('column_j')->comment('Test 1 Benchmark Category')->nullable();
            $table->string('column_k')->comment('Test 1 PR')->nullable();
            $table->string('column_l')->comment('Test 1 NCE')->nullable();
            $table->string('column_m')->comment('Test 2 Test Type')->nullable();
            $table->string('column_n')->comment('Test 2 Test Date')->nullable();
            $table->string('column_o')->comment('Test 2 Test Duration')->nullable();
            $table->string('column_p')->comment('Test 2 SS')->nullable();
            $table->string('column_q')->comment('Test 2 Benchmark Category')->nullable();
            $table->string('column_r')->comment('Test 2 PR')->nullable();
            $table->string('column_s')->comment('Test 2 NCE')->nullable();
            $table->string('column_t')->comment('Test 3 Test Type')->nullable();
            $table->string('column_u')->comment('Test 3 Test Date')->nullable();
            $table->string('column_v')->comment('Test 3 Test Duration')->nullable();
            $table->string('column_w')->comment('Test 3 SS')->nullable();
            $table->string('column_x')->comment('Test 3 Benchmark Category')->nullable();
            $table->string('column_y')->comment('Test 3 PR')->nullable();
            $table->string('column_z')->comment('Test 3 NCE')->nullable();
            $table->string('column_aa')->comment('Latest Change in Score')->nullable();
            $table->string('column_ab')->comment('Latest Change in PR')->nullable();
            $table->string('column_ac')->comment('Latest Change in NCE')->nullable();

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
        Schema::dropIfExists('star_fall_maths');
    }
};
