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
    public function up() {
        foreach (config('constants.tables') as $tableName) {
            try {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->index(['cycle_id']);
                    $table->index(['cycle_id','student_id']);
                });
            } catch (Exception $e) {
                echo 'Message: ' .$e->getMessage();
            }
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
