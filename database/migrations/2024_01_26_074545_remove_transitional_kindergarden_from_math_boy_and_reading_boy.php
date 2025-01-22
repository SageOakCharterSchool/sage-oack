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
    /*
    Please delete the last column from the file info. The last column BH in the file info is Transitional Kindergarten. The report from iReady does not include column bh transitional kindergarten.
        Currently, we have to add a column bh and title it manually
        Iready file example.
        Delete BH from file info for iReady math boys, iReady ready boy s
    */
    public function up()
    {
        Schema::table('i_ready_math_boys', function (Blueprint $table) {
            $table->dropColumn('column_bh'); // 	Transitional Kindergarten
        });
        Schema::table('i_ready_reading_boy_s', function (Blueprint $table) {
            $table->dropColumn('column_br'); // 	Transitional Kindergarten
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('math_boy_and_reading_boy', function (Blueprint $table) {
            //
        });
    }
};
