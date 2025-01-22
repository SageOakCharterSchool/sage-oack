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
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->index();
            $table->integer('cycle_id');
            $table->string('uploaded_on',10)->comment('date_uploaded')->nullable()->index();
            $table->string('table_name')->comment('table_name')->nullable();
            $table->string('file_name')->comment('file_name')->nullable();
            $table->string('file_path')->comment('file_path')->nullable();
            $table->tinyInteger('status')->comment('was_processe')->default(0);
            $table->timestamp('processed_on')->comment('dete when processed')->nullable();
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
        Schema::dropIfExists('file_uploads');
    }
};
