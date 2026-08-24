<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_accounts', function (Blueprint $table) {
            $table->string('column_k')->comment('Tracking number')->nullable();
            $table->string('column_l')->comment('Tracking notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_accounts', function (Blueprint $table) {
            //
        });
    }
};
