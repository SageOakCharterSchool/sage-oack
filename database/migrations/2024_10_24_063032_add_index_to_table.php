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
        Schema::table('multi_table_fields', function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound  = $schemaManager->listTableIndexes('multi_table_fields');

            if (array_key_exists('multi_index_1', $indexesFound)) {
                $table->dropIndex('multi_index_1');
            }
            if (array_key_exists('multi_index_2', $indexesFound)) {
                $table->dropIndex('multi_index_2');
            }
            $table->index(['cycle_id', 'table_id', 'student_id', 'teacher_id'],'multi_index_1');
            $table->index(['cycle_id', 'table_id', 'column', 'student_id','teacher_id'],'multi_index_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multi_table_index', function (Blueprint $table) {
            //
        });
    }
};
