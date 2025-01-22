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
        Schema::table('easy_cbm_falls', function (Blueprint $table) {

            $table->longText('column_ay')->nullable()->comment('proficient_math_benchmark_sp_count');
            $table->longText('column_az')->nullable()->comment('reading_risk');
            $table->longText('column_ba')->nullable()->comment('math_risk');
            $table->longText('column_bb')->nullable()->comment('date_of_assessment');
            $table->longText('column_bx')->nullable()->comment('academic_year');
            $table->longText('column_bd')->nullable()->comment('season');
            $table->longText('column_be')->nullable()->comment('rows_for_this_student');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('easy_cbm_falls', function (Blueprint $table) {
            //
        });
    }
};
