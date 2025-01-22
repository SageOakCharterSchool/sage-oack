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
        Schema::table('teacher_students', function (Blueprint $table) {
            $table->longText('first_name')->change();
            $table->longText('last_name')->change();
            $table->longText('column_f')->change();
            $table->longText('column_g')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_students', function (Blueprint $table) {
            //
        });
    }
};
