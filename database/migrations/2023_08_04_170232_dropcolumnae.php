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
        Schema::table('star_eoy_maths', function (Blueprint $table) {
            $table->dropColumn('column_ad');
            $table->dropColumn('column_ae');
            //$table->string('column_ad')->nullable()->after('column_ac');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('star_eoy_maths', function (Blueprint $table) {
            //
        });
    }
};
