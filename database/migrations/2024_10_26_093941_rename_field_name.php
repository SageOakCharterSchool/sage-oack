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
        Schema::table('multi_table_fields', function (Blueprint $table) {
            //$table->renameColumn('row_number', 'csv_row_number');
            $table->index(['cycle_id', 'table_id', 'column', 'student_id','row_number'],'multi_index_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multi_table_fields', function (Blueprint $table) {
            //
        });
    }
};
