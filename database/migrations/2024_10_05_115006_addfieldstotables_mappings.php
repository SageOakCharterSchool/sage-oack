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
        Schema::table('tables_mappings', function (Blueprint $table) {
            $table->tinyInteger("is_student_email")->default(0)->after('is_teacher_id');
            $table->tinyInteger("is_teacher_email")->default(0)->after('is_student_email');
            $table->tinyInteger("is_teacher_first_name")->default(0)->after('is_teacher_email');
            $table->tinyInteger("is_teacher_last_name")->default(0)->after('is_teacher_first_name');
            $table->tinyInteger("is_teacher_student_id")->default(0)->after('is_teacher_first_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables_mappings', function (Blueprint $table) {
            $table->dropColumn("is_student_email");
            $table->dropColumn("is_teacher_email");
            $table->dropColumn("is_teacher_name");
            $table->dropColumn("is_teacher_student_id");
        });
    }
};
