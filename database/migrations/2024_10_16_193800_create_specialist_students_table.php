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
        Schema::create('specialist_students', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->index();
            $table->integer('created_by')->index();
            $table->string('student_id',55)->index()->nullable();
            $table->integer('specialist_id')->index();
            $table->string('first_name',55)->nullable();
            $table->string('last_name',55)->nullable();
            $table->string('email')->index()->nullable();
            $table->string('name')->nullable();
            $table->longText('students_list')->nullable();
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
        Schema::dropIfExists('specialist_students');
    }
};
