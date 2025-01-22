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
        Schema::create('consolidate_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->index();
            $table->string('column_name',55)->nullable();
            $table->string('column_description',255)->nullable();
            $table->integer('table_source')->index()->nullable();
            $table->integer('field_source')->index()->nullable();
            $table->tinyInteger('is_formulated')->default(0);
            $table->integer('formula_id')->index()->nullable();
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('consolidate_mappings');
    }
};
