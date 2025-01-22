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
        Schema::table('star_fall_maths', function (Blueprint $table) {
            $table->dropColumn('column_a'); //Grade
            $table->dropColumn('column_b'); //Student
            $table->dropColumn('column_c'); //Assignment Type
            $table->dropColumn('column_d'); //Growth Proficiency Category
            $table->dropColumn('column_e'); //SGP (Expectation=50)
            $table->dropColumn('column_f'); //Test 1 Test Type
            $table->dropColumn('column_g'); //Test 1 Test Date
            $table->dropColumn('column_h'); //Test 1 Test Duration
            $table->dropColumn('column_i'); //Test 1 SS
            $table->dropColumn('column_j'); //Test 1 Benchmark Category
            $table->dropColumn('column_k'); //Test 1 PR
            $table->dropColumn('column_l'); //Test 1 NCE
            $table->dropColumn('column_m'); //Test 2 Test Type
            $table->dropColumn('column_n'); //Test 2 Test Date
            $table->dropColumn('column_o'); //Test 2 Test Duration
            $table->dropColumn('column_p'); //Test 2 SS
            $table->dropColumn('column_q'); //Test 2 Benchmark Category
            $table->dropColumn('column_r'); //Test 2 PR
            $table->dropColumn('column_s'); //Test 2 NCE
            $table->dropColumn('column_t'); //Test 3 Test Type
            $table->dropColumn('column_u'); //Test 3 Test Date
            $table->dropColumn('column_v'); //Test 3 Test Duration
            $table->dropColumn('column_w'); //Test 3 SS
            $table->dropColumn('column_x'); //Test 3 Benchmark Category
            $table->dropColumn('column_y'); //Test 3 PR
            $table->dropColumn('column_z'); //Test 3 NCE
            $table->dropColumn('column_aa'); //Latest Change in Score
            $table->dropColumn('column_ab'); //Latest Change in PR
            $table->dropColumn('column_ac'); //Latest Change in NCE
            $table->dropColumn('column_ad'); //Student Id
        });

        Schema::table('star_fall_maths', function (Blueprint $table) {
            $table->string('column_a')->comment('Student')->nullable();
            $table->string('column_b')->comment('COLUMN_B')->nullable();
            $table->string('column_c')->comment('COLUMN_C')->nullable();
            $table->string('column_d')->comment('COLUMN_D')->nullable();
            $table->string('column_e')->comment('COLUMN_E')->nullable();
            $table->string('column_f')->comment('COLUMN_F')->nullable();
            $table->string('column_g')->comment('COLUMN_G')->nullable();
            $table->string('column_h')->comment('COLUMN_H')->nullable();
            $table->string('column_i')->comment('COLUMN_I')->nullable();
            $table->string('column_j')->comment('Benchmark Category')->nullable();
            $table->string('column_k')->comment('COLUMN_K')->nullable();
            $table->string('column_l')->comment('COLUMN_L')->nullable();
            $table->string('column_m')->comment('COLUMN_M')->nullable();
            $table->string('column_n')->comment('COLUMN_N')->nullable();
            $table->string('column_o')->comment('COLUMN_O')->nullable();
            $table->string('column_p')->comment('COLUMN_P')->nullable();
            $table->string('column_q')->comment('COLUMN_Q')->nullable();
            $table->string('column_r')->comment('COLUMN_R')->nullable();
            $table->string('column_s')->comment('COLUMN_S')->nullable();
            $table->string('column_t')->comment('COLUMN_T')->nullable();
            $table->string('column_u')->comment('COLUMN_U')->nullable();
            $table->string('column_v')->comment('COLUMN_V')->nullable();
            $table->string('column_w')->comment('COLUMN_W')->nullable();
            $table->string('column_x')->comment('COLUMN_X')->nullable();
            $table->string('column_y')->comment('COLUMN_Y')->nullable();
            $table->string('column_z')->comment('COLUMN_Z')->nullable();
            $table->string('column_aa')->comment('COLUMN_AA')->nullable();
            $table->string('column_ab')->comment('COLUMN_AB')->nullable();
            $table->string('column_ac')->comment('COLUMN_AC')->nullable();
            $table->string('column_ad')->comment('COLUMN_AD')->nullable();
            $table->string('column_ae')->comment('Student ID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('star_fall_maths', function (Blueprint $table) {
            //
        });
    }
};
