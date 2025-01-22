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
            $table->tinyInteger("is_teacher_id")->default(0);
            $table->tinyInteger("is_first_name")->default(0);
            $table->tinyInteger("is_last_name")->default(0);
            $table->tinyInteger("is_dob")->default(0);
            $table->tinyInteger("is_password")->default(0);
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
            $table->dropColumn("is_teacher_id");
            $table->dropColumn("is_first_name");
            $table->dropColumn("is_last_name");
            $table->dropColumn("is_dob");
        });
    }
};
