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
        Schema::create('brainpops', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id')->nullable();
            $table->string('cycle_id')->comment('cycle_id')->nullable();
            $table->string('teacher_id')->comment('teacher_id')->nullable();
            $table->string('column_a')->comment('Student_First_Name')->nullable();
            $table->string('column_b')->comment('student_id')->nullable();
            $table->string('column_c')->comment('Username')->nullable();
            $table->longText('column_d')->comment('Date_of_activity_unix')->nullable();
            $table->longText('column_e')->comment('Date_of_activity')->nullable();
            $table->longText('column_f')->comment('Topic_Name_or_Name_of_Game/Quiz')->nullable();
            $table->longText('column_g')->comment('Type_of_Activity')->nullable();
            $table->longText('column_h')->comment('Website')->nullable();
            $table->longText('column_i')->comment('Score_(if_any)')->nullable();
            $table->longText('column_j')->comment('How many lessons')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brainpops');
    }
};
