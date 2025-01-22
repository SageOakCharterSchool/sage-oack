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
        Schema::create('easy_cbm_progmons', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('last')->nullable();
            $table->string('column_b')->comment('first')->nullable();
            $table->string('column_c')->comment('student_id')->nullable();
            $table->string('column_d')->comment('student_dob')->nullable();
            $table->string('column_e')->comment('student_easycbmid')->nullable();
            $table->string('column_f')->comment('student_grade')->nullable();
            $table->string('column_g')->comment('student_gender')->nullable();
            $table->string('column_h')->comment('student_sped')->nullable();
            $table->string('column_i')->comment('student_ethnicity')->nullable();
            $table->string('column_j')->comment('student_race')->nullable();
            $table->string('column_k')->comment('student_ell')->nullable();
            $table->string('column_l')->comment('student_active')->nullable();
            $table->string('column_m')->comment('building_name')->nullable();
            $table->string('column_n')->comment('district_data_1')->nullable();
            $table->string('column_o')->comment('district_data_2')->nullable();
            $table->string('column_p')->comment('district_data_3')->nullable();
            $table->string('column_q')->comment('district_data_4')->nullable();
            $table->string('column_r')->comment('district_data_5')->nullable();
            $table->string('column_s')->comment('measure_type')->nullable();
            $table->string('column_t')->comment('measure_grade')->nullable();
            $table->string('column_u')->comment('measure_form')->nullable();
            $table->string('column_v')->comment('score')->nullable();
            $table->string('column_w')->comment('accuracy')->nullable();
            $table->string('column_x')->comment('date_given')->nullable();
            $table->string('column_y')->comment('academic_year')->nullable();
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
        Schema::dropIfExists('easy_c_b_m_prog_mons');
    }
};
