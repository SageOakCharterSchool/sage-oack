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
        Schema::dropIfExists('easy_cbm_falls');
        Schema::create('easy_cbm_falls', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id');
            $table->string('cycle_id')->index()->comment('cycle_id')->nullable();
            $table->string('teacher_id')->index()->comment('teacher_id')->nullable();
            $table->string('column_a')->comment('last')->nullable();
            $table->string('column_b')->comment('first')->nullable();
            $table->string('column_c')->comment('student_id')->nullable();
            $table->string('column_d')->comment('student_dob')->nullable();
            $table->string('column_e')->comment('student_easycbmid')->nullable();
            $table->string('column_f')->comment('student_gender')->nullable();
            $table->string('column_g')->comment('student_grade')->nullable();
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
            $table->string('column_s')->comment('proficient_reading_score')->nullable();
            $table->string('column_t')->comment('proficient_reading_percentile')->nullable();
            $table->string('column_u')->comment('proficient_reading_accuracy')->nullable();
            $table->string('column_v')->comment('Lexile Suggestion')->nullable();
            $table->string('column_w')->comment('passage_reading_fluency_score')->nullable();
            $table->string('column_x')->comment('passage_reading_fluency_percentile')->nullable();
            $table->string('column_y')->comment('passage_reading_fluency_accuracy')->nullable();
            $table->string('column_z')->comment('vocabulary_score')->nullable();
            $table->string('column_aa')->comment('vocabulary_percentile')->nullable();
            $table->string('column_ab')->comment('vocabulary_accuracy')->nullable();
            $table->string('column_ac')->comment('basic_math_score')->nullable();
            $table->string('column_ad')->comment('basic_math_percentile')->nullable();
            $table->string('column_ae')->comment('basic_math_accuracy')->nullable();
            $table->string('column_af')->comment('basic_math_sp_count')->nullable();
            $table->string('column_ag')->comment('proficient_math_benchmark_score')->nullable();
            $table->string('column_ah')->comment('proficient_math_benchmark_percentile')->nullable();
            $table->string('column_ai')->comment('proficient_math_benchmark_accuracy')->nullable();
            $table->string('column_aj')->comment('proficient_math_benchmark_sp_count')->nullable();
            $table->string('column_ak')->comment('reading_risk')->nullable();
            $table->string('column_al')->comment('math_risk')->nullable();
            $table->string('column_am')->comment('date_of_assessment')->nullable();
            $table->string('column_an')->comment('academic_year')->nullable();
            $table->string('column_ao')->comment('season')->nullable();
            $table->string('column_ap')->comment('rows_for_this_student')->nullable();
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
        Schema::table('easy_cbm_falls', function (Blueprint $table) {
            //
        });
    }
};
