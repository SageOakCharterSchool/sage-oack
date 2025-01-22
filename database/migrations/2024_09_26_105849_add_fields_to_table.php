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
        Schema::table('i_ready_math_minutes', function (Blueprint $table) {
            $table->longText('column_ap')->nullable()->comment('September Lessons Passed');
            $table->longText('column_aq')->nullable()->comment('September Lessons Completed');
            $table->longText('column_ar')->nullable()->comment('September % Lessons Passed');
            $table->longText('column_as')->nullable()->comment('October Total Time on Task (min)');
            $table->longText('column_at')->nullable()->comment('October Weekly Average Time on Task (min)');
            $table->longText('column_au')->nullable()->comment('October Lessons Passed');
            $table->longText('column_av')->nullable()->comment('October Lessons Completed');
            $table->longText('column_aw')->nullable()->comment('October % Lessons Passed');
            $table->longText('column_ax')->nullable()->comment('November Total Time on Task (min)');
            $table->longText('column_ay')->nullable()->comment('November Weekly Average Time on Task (min)');
            $table->longText('column_az')->nullable()->comment('November Lessons Passed');
            $table->longText('column_ba')->nullable()->comment('November Lessons Completed');
            $table->longText('column_bb')->nullable()->comment('November % Lessons Passed');
            $table->longText('column_bc')->nullable()->comment('December Total Time on Task (min)');
            $table->longText('column_bd')->nullable()->comment('December Weekly Average Time on Task (min)');
            $table->longText('column_be')->nullable()->comment('December Lessons Passed');
            $table->longText('column_bf')->nullable()->comment('December Lessons Completed');
            $table->longText('column_bg')->nullable()->comment('December % Lessons Passed');
            $table->longText('column_bh')->nullable()->comment('January Total Time on Task (min)');
            $table->longText('column_bi')->nullable()->comment('January Weekly Average Time on Task (min)');
            $table->longText('column_bj')->nullable()->comment('January Lessons Passed');
            $table->longText('column_bk')->nullable()->comment('January Lessons Completed');
            $table->longText('column_bl')->nullable()->comment('January % Lessons Passed');
            $table->longText('column_bm')->nullable()->comment('February Total Time on Task (min)');
            $table->longText('column_bn')->nullable()->comment('February Weekly Average Time on Task (min)');
            $table->longText('column_bo')->nullable()->comment('February Lessons Passed');
            $table->longText('column_bp')->nullable()->comment('February Lessons Completed');
            $table->longText('column_bq')->nullable()->comment('February % Lessons Passed');
            $table->longText('column_br')->nullable()->comment('March Total Time on Task (min)');
            $table->longText('column_bs')->nullable()->comment('March Weekly Average Time on Task (min)');
            $table->longText('column_bt')->nullable()->comment('March Lessons Passed');
            $table->longText('column_bu')->nullable()->comment('March Lessons Completed');
            $table->longText('column_bv')->nullable()->comment('March % Lessons Passed');
            $table->longText('column_bw')->nullable()->comment('April Total Time on Task (min)');
            $table->longText('column_bx')->nullable()->comment('April Weekly Average Time on Task (min)');
            $table->longText('column_by')->nullable()->comment('April Lessons Passed');
            $table->longText('column_bz')->nullable()->comment('April Lessons Completed');
            $table->longText('column_ca')->nullable()->comment('April % Lessons Passed');
            $table->longText('column_cb')->nullable()->comment('May Total Time on Task (min)');
            $table->longText('column_cc')->nullable()->comment('May Weekly Average Time on Task (min)');
            $table->longText('column_cd')->nullable()->comment('May Lessons Passed');
            $table->longText('column_ce')->nullable()->comment('May Lessons Completed');
            $table->longText('column_cf')->nullable()->comment('May % Lessons Passed');
            $table->longText('column_cg')->nullable()->comment('June Total Time on Task (min)');
            $table->longText('column_ch')->nullable()->comment('June Weekly Average Time on Task (min)');
            $table->longText('column_ci')->nullable()->comment('June Lessons Passed');
            $table->longText('column_cj')->nullable()->comment('June Lessons Completed');
            $table->longText('column_ck')->nullable()->comment('June % Lessons Passed');
            $table->longText('column_cl')->nullable()->comment('First Lesson Completion Date');
            $table->longText('column_cm')->nullable()->comment('Most Recent Lesson Completion Date');
            $table->longText('column_cn')->nullable()->comment('Year-to-Date Overall Time on Task (min)');
            $table->longText('column_co')->nullable()->comment('Year-to-Date Overall Lessons Passed');
            $table->longText('column_cp')->nullable()->comment('Year-to-Date Overall Lessons Completed');
            $table->longText('column_cq')->nullable()->comment('Year-to-Date Overall % Lessons Passed');
            $table->longText('column_cr')->nullable()->comment('Year-to-Date Number and Operations Time on Task (min)');
            $table->longText('column_cs')->nullable()->comment('Year-to-Date Number and Operations Lessons Passed');
            $table->longText('column_ct')->nullable()->comment('Year-to-Date Number and Operations Lessons Completed');
            $table->longText('column_cu')->nullable()->comment('Year-to-Date Number and Operations % Lessons Passed');
            $table->longText('column_cv')->nullable()->comment('Year-to-Date Algebra and Algebraic Thinking Time on Task (min)');
            $table->longText('column_cw')->nullable()->comment('Year-to-Date Algebra and Algebraic Thinking Lessons Passed');
            $table->longText('column_cx')->nullable()->comment('Year-to-Date Algebra and Algebraic Thinking Lessons Completed');
            $table->longText('column_cy')->nullable()->comment('Year-to-Date Algebra and Algebraic Thinking % Lessons Passed');
            $table->longText('column_cz')->nullable()->comment('Year-to-Date Measurement and Data Time on Task (min)');
            $table->longText('column_da')->nullable()->comment('Year-to-Date Measurement and Data Lessons Passed');
            $table->longText('column_db')->nullable()->comment('Year-to-Date Measurement and Data Lessons Completed');
            $table->longText('column_dc')->nullable()->comment('Year-to-Date Measurement and Data % Lessons Passed');
            $table->longText('column_dd')->nullable()->comment('Year-to-Date Geometry Time on Task (min)');
            $table->longText('column_de')->nullable()->comment('Year-to-Date Geometry Lessons Passed');
            $table->longText('column_df')->nullable()->comment('Year-to-Date Geometry Lessons Completed');
            $table->longText('column_dg')->nullable()->comment('Year-to-Date Geometry % Lessons Passed');
            $table->longText('column_dh')->nullable()->comment('504 Plan');
            $table->longText('column_di')->nullable()->comment('English Language Classification');
            $table->longText('column_dj')->nullable()->comment('Foster Youth');
            $table->longText('column_dk')->nullable()->comment('Gifted and Talented (GATE)');
            $table->longText('column_dl')->nullable()->comment('Homeless Youth');
            $table->longText('column_dm')->nullable()->comment('Student with Disabilities');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('i_ready_math_minutes', function (Blueprint $table) {
            //
        });
    }
};
