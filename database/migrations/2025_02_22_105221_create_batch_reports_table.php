<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('report_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->tinyInteger('status')->nullable(); // 1 = in queue / 2 = in process / 3 = completed
            $table->string('started_at')->nullable();
            $table->string('completed_at')->nullable();
            $table->longText('result')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_reports');
    }
};
