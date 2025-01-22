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
        Schema::create('multi_table_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')->index()->nullable();
            $table->string('student_id',55)->index()->comment('student id');
            $table->integer('cycle_id')->index();
            $table->integer('table_id')->index();
            $table->integer('field_id')->index();
            $table->integer('row_number');
            $table->longText('field_value')->nullable();
            $table->integer('created_by');
            $table->longText('action')->comment('action')->nullable();
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
        Schema::dropIfExists('multi_table_fields');
    }
};
