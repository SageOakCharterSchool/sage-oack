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
        Schema::create('student_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id')->nullable();
            $table->string('cycle_id')->index()->comment('cycle_id')->nullable();
            $table->string('teacher_id')->index()->comment('teacher_id')->nullable();
            $table->text('column_a')->comment('firt_name')->nullable();
            $table->text('column_b')->comment('last_name')->nullable();
            $table->text('column_c')->comment('Student ID')->nullable();
            $table->text('column_d')->comment('Grade')->nullable();
            $table->text('column_e')->comment('Student Email')->nullable();
            $table->text('column_f')->comment('Student Password')->nullable();
            $table->text('column_g')->comment('Student DOB')->nullable();
            $table->text('column_h')->comment('Tracking')->nullable();
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
        Schema::dropIfExists('student_accounts');
    }
};
