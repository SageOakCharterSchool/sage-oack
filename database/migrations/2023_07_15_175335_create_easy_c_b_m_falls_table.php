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
        Schema::create('easy_cbm_falls', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
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
            $table->string('column_s')->comment('letter_names_score')->nullable();
            $table->string('column_t')->comment('letter_names_percentile')->nullable();
            $table->string('column_u')->comment('letter_names_accuracy')->nullable();
            $table->string('column_v')->comment('letter_sounds_score')->nullable();
            $table->string('column_w')->comment('letter_sounds_percentile')->nullable();
            $table->string('column_x')->comment('letter_sounds_accuracy')->nullable();
            $table->string('column_y')->comment('proficient_reading_score')->nullable();
            $table->string('column_z')->comment('proficient_reading_percentile')->nullable();
            $table->string('column_aa')->comment('proficient_reading_accuracy')->nullable();
            $table->string('column_ab')->comment('Lexile Suggestion')->nullable();
            $table->string('column_ac')->comment('passage_reading_fluency_score')->nullable();
            $table->string('column_ad')->comment('passage_reading_fluency_percentile')->nullable();
            $table->string('column_ae')->comment('passage_reading_fluency_accuracy')->nullable();
            $table->string('column_af')->comment('phoneme_segmenting_score')->nullable();
            $table->string('column_ag')->comment('phoneme_segmenting_percentile')->nullable();
            $table->string('column_ah')->comment('phoneme_segmenting_accuracy')->nullable();
            $table->string('column_ai')->comment('vocabulary_score')->nullable();
            $table->string('column_aj')->comment('vocabulary_percentile')->nullable();
            $table->string('column_ak')->comment('vocabulary_accuracy')->nullable();
            $table->string('column_al')->comment('word_reading_fluency_score')->nullable();
            $table->string('column_am')->comment('word_reading_fluency_percentile')->nullable();
            $table->string('column_an')->comment('word_reading_fluency_accuracy')->nullable();
            $table->string('column_ao')->comment('proficient_math_benchmark_score')->nullable();
            $table->string('column_ap')->comment('proficient_math_benchmark_percentile')->nullable();
            $table->string('column_aq')->comment('proficient_math_benchmark_accuracy')->nullable();
            $table->string('column_ar')->comment('proficient_math_benchmark_sp_count')->nullable();
            $table->string('column_as')->comment('reading_risk')->nullable();
            $table->string('column_at')->comment('math_risk')->nullable();
            $table->string('column_au')->comment('date_of_assessment')->nullable();
            $table->string('column_av')->comment('academic_year')->nullable();
            $table->string('column_aw')->comment('season')->nullable();
            $table->string('column_ax')->comment('rows_for_this_student')->nullable();

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
        Schema::dropIfExists('easy_c_b_m_falls');
    }
};
