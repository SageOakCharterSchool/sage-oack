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
    public $tables = [
        'attendances',
        'attendance_elas',
        'attendance_maths',
        'consolidateds',
        'easy_cbm_falls',
        'easy_cbm_progmons',
        'freckle_minutes',
        'i_ready_math_boys',
        'i_ready_math_eoy_s',
        'i_ready_math_mid_years',
        'i_ready_math_minutes',
        'i_ready_reading_boy_s',
        'i_ready_reading_eoy_s',
        'i_ready_reading_mid_years',
        'i_ready_reading_minutes',
        'math180_minutes',
        'math_lists',
        'read180_minutes',
        'sheet15s',
        'star_eoy_maths',
        'star_eoy_readings',
        'star_fall_maths',
        'star_fall_readings',
        'star_mid_year_maths',
        'star_mid_year_readings',
        'student_lists',
        'trans_math_minutes',
        'v_math_minutes',
    ];
    public function up()
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->integer('teacher_id')->index()->after('id')->nullable();

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('teacher_id');
            });
        }
    }
};
