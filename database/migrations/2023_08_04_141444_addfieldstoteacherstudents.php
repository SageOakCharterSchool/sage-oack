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
            $table->string('student_id',55)->index()->nullable()->after('students_list');
            $table->string('first_name',55)->nullable()->after('student_id')->comment('(Staff1) First Name');
            $table->string('last_name',55)->nullable()->after('first_name')->comment('(Staff1) Last Name');
            $table->string('column_d',55)->nullable()->after('last_name')->comment('(Staff1) Teacher Number (UNIQUE IDENTIFIER)');
            $table->string('column_e',55)->nullable()->after('column_d')->comment('(Staff1) Staff ID');
            $table->string('column_f',55)->nullable()->after('column_e')->comment('(Students1) Last Name');
            $table->string('column_g',55)->nullable()->after('column_f')->comment('(Students1) First Name');
            $table->string('column_h',55)->nullable()->after('column_g')->comment('(Students1) Local Student ID');
            $table->string('column_i',55)->index()->nullable()->after('column_h')->comment('(Students1) SSID (State Student ID Number)  (UNIQUE IDENTIFIER)');
            $table->string('column_j',55)->nullable()->after('column_i')->comment('(Students1) District ID  (UNIQUE IDENTIFIER)');

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
            $table->dropColumn('student_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('column_d');
            $table->dropColumn('column_e');
            $table->dropColumn('column_f');
            $table->dropColumn('column_g');
            $table->dropColumn('column_h');
            $table->dropColumn('column_i');
            $table->dropColumn('column_j');
        });
    }
};
