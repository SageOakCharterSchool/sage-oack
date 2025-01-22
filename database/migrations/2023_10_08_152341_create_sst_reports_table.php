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
        Schema::create('sst_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id')->nullable();
            $table->string('cycle_id')->comment('cycle_id')->nullable()->index();
            $table->string('teacher_id')->comment('teacher_id')->nullable()->index();
            $table->string('column_a')->comment('student_name')->nullable();
            $table->string('column_b')->comment('SSId')->nullable();
            $table->string('column_c')->comment('Type of SST')->nullable();
            $table->string('column_d')->comment('Date of SST')->nullable();
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
        Schema::dropIfExists('sst_reports');
    }
};
