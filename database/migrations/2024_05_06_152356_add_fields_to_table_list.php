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
        Schema::table('master_tables', function (Blueprint $table) {
            $table->tinyInteger("is_system")->default("0");
            $table->tinyInteger("allow_upload")->default("1");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_tables', function (Blueprint $table) {
            //
        });
    }
};
