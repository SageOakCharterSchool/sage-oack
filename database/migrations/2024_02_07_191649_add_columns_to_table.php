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
            $table->longText('column_af')->nullable();
            $table->longText('column_ag')->nullable();
            $table->longText('column_ah')->nullable();
            $table->longText('column_ai')->nullable();
            $table->longText('column_aj')->nullable();
            $table->longText('column_ak')->nullable();
            $table->longText('column_al')->nullable();
            $table->longText('column_am')->nullable();
            $table->longText('column_an')->nullable();
            $table->longText('column_ao')->nullable();
            $table->longText('column_ap')->nullable();
            $table->longText('column_aq')->nullable();
            $table->longText('column_ar')->nullable();
            $table->longText('column_as')->nullable();
            $table->longText('column_at')->nullable();
            $table->longText('column_au')->nullable();
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
