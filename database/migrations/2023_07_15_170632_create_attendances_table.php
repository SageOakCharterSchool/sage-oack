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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('SSID')->nullable();
            $table->string('column_b')->comment('Student Last Name')->nullable();
            $table->string('column_c')->comment('Student First Name')->nullable();
            $table->string('column_d')->comment('44993')->nullable();
            $table->string('column_e')->comment('44994')->nullable();
            $table->string('column_f')->comment('44995')->nullable();
            $table->string('column_g')->comment('44996')->nullable();
            $table->string('column_h')->comment('Percentage of attendance')->nullable();
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
        Schema::dropIfExists('attendances');
    }
};
