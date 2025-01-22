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
        Schema::create('math180_minutes', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('Student Last Name')->nullable();
            $table->string('column_b')->comment('Student First Name')->nullable();
            $table->string('column_c')->comment('SSID')->nullable();
            $table->string('column_d')->comment('Grade')->nullable();
            $table->string('column_e')->comment('SIS')->nullable();
            $table->string('column_f')->comment('Qualifying Subject')->nullable();
            $table->string('column_g')->comment('Teacher Name')->nullable();
            $table->string('column_h')->comment('Read 180 Minutes')->nullable();

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
        Schema::dropIfExists('math180_minutes');
    }
};
