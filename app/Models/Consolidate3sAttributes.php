<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;


class Consolidate3sAttributes extends Model
{
    use HasFactory;
    protected $table = "consolidate3s_attributes";
    protected $fillable = [
        'cycle_id',
        'student_id',
        'consolidate_id',
        'column_name',
        'formula_id',
        'equivalence_id',
        'field_value',
        'attribute_1',
        'attribute_2',
        'attribute_3',
        'attribute_4',
        'attribute_5',
    ];

    protected function generateCycleTables($cycle) {
        $tempTableName2 = "consolidate3s_attributes_" . $cycle->id;
        Schema::dropIfExists($tempTableName2);

        $result = Schema::create($tempTableName2, function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->index();
            $table->string('student_id',55)->nullable()->index();
            $table->integer('consolidate_id')->index();
            $table->string('column_name',55)->index();
            $table->integer('formula_id')->index();
            $table->integer('equivalence_id')->index();
            $table->string('field_value')->nullable();
            $table->string('attribute_1')->nullable();
            $table->string('attribute_2')->nullable();
            $table->string('attribute_3')->nullable();
            $table->string('attribute_4')->nullable();
            $table->string('attribute_5')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
        });
    }
}
