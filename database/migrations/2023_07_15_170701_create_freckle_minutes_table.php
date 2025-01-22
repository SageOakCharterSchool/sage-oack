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
        Schema::create('freckle_minutes', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id',55)->index()->comment('student id');
            $table->string('column_a')->comment('STUDENT_NAME')->nullable();
            $table->string('column_b')->comment('SIS_ID')->nullable();
            $table->string('column_c')->comment('TOTAL_SESSIONS')->nullable();
            $table->string('column_d')->comment('TOTAL_MINUTES')->nullable();
            $table->string('column_e')->comment('MATH_SESSIONS')->nullable();
            $table->string('column_f')->comment('ELA_SESSIONS')->nullable();
            $table->string('column_g')->comment('SOCIAL_STUDIES_SESSIONS')->nullable();
            $table->string('column_h')->comment('SCIENCE_SESSIONS')->nullable();
            $table->string('column_i')->comment('MINS_SPENT_IN_MATH')->nullable();
            $table->string('column_j')->comment('MINS_SPENT_IN_ELA')->nullable();
            $table->string('column_k')->comment('MINS_SPENT_IN_SOCIAL_STUDIES')->nullable();
            $table->string('column_l')->comment('MINS_SPENT_IN_SCIENCE')->nullable();
            $table->string('column_m')->comment('TEACHERS')->nullable();
            $table->string('column_n')->comment('SCHOOLS')->nullable();

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
        Schema::dropIfExists('freckle_minutes');
    }
};
