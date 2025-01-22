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
        Schema::create('upload_files_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->nullable()->index();
            $table->integer('table_id')->nullable()->index();
            $table->integer('total_records')->default(0);
            $table->string('file_name')->nullable();
            $table->longText('file_contents')->nullable();
            $table->integer('uploaded_by')->nullable();
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
        Schema::dropIfExists('upload_files_logs');
    }
};
