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
        Schema::create('consolidate3s_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->index();
            $table->integer('student_id')->index();
            $table->integer('consolidate_id')->index();
            $table->string('column_name',55)->index();
            $table->integer('formula_id')->index();
            $table->integer('equivalence_id')->index();
            $table->string('field_value')->nullable();
            $table->string('attribute_1')->nullable();
            $table->string('attribute_2')->nullable();
            $table->string('attribute_3')->nullable();
            $table->string('attribute_4')->nullable();
            $table->string('attribute_5')->nullable();
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
        Schema::dropIfExists('consolidate3s_attributes');
    }
};
