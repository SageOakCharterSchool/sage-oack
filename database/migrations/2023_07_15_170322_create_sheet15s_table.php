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
        Schema::create('sheet15s', function (Blueprint $table) {
            $table->id();
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('student last');
            $table->string('column_b')->comment('student first');
            $table->string('column_c')->comment('teacher');
            $table->string('column_d')->comment('report');
            $table->integer('created_by');
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
        Schema::dropIfExists('sheet15s');
    }
};
