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
        Schema::table('consolidate3s', function(Blueprint $table)
        {
            $table->index(['cycle_id','student_id']);
        });
        Schema::table('consolidated_cycle_10', function(Blueprint $table)
        {
            $table->index(['cycle_id','student_id']);
        });
        Schema::table('multi_table_fields', function(Blueprint $table)
        {
            $table->index(['cycle_id','student_id','table_id','column']);
        });
        Schema::table('tables_mappings', function(Blueprint $table)
        {
            $table->index(['cycle_id','table_id','column']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
