<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TableAlias;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_aliases', function (Blueprint $table) {
            $table->id();
            $table->string('table_name',55)->nullable()->index();
            $table->string('table_alias',55)->nullable()->index();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
        foreach (config('constants.tablesAlias') as $tableToCreate => $tableAlias) {
            $table = TableAlias::where('table_alias', $tableAlias)
                            ->where('table_name', $tableToCreate)
                            ->first();
            if (!$table) {
                $data = [
                    'table_name' => $tableToCreate,
                    'table_alias' => $tableAlias,
                    'created_by' => 1
                ];
                TableAlias::create($data);
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
        Schema::dropIfExists('create_table_aliases');
    }
};
