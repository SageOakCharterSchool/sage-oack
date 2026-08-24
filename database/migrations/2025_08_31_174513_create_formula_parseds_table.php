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
        Schema::create('formula_parse', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->nullable()->index();
            $table->integer('formula_id')->nullable()->index();
            $table->string('student_id',55)->nullable()->index();
            $table->string('formula_result', 155)->nullable()->index();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formula_parse');
    }
};
