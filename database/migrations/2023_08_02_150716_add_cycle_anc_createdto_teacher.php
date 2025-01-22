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
            $table->integer('cycle_id')->index()->after('id');
            $table->integer('created_by')->index()->after('teacher_id');

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
