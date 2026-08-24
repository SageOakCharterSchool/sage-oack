<?php

use App\Models\ConsolidateColor;
use App\Models\Equivalences;
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
        Schema::create('consolidate_colors', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_id')->index();
            $table->string('column_name')->index();
            $table->string('value', 155)->nullable()->index();
            $table->string('background_color', 15)->nullable();
            $table->string('color', 15)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
        // Iready relative
        $columns[]['column_Q'] = ['Mid or Above Grade Level',	'#0000FF'];
        $columns[]['column_Q'] = ['Early On Grade Level',	'#008000'];
        $columns[]['column_Q'] = ['1 Grade Level Below',	'#FFFF00'];
        $columns[]['column_Q'] = ['2 Grade Levels Below',	'#FF0000'];
        $columns[]['column_Q'] = ['3 or More Grade Levels Below',	'#FF0000'];
        // Iready relative
        $columns[]['column_T'] = ['Mid or Above Grade Level',	'#0000FF'];
        $columns[]['column_T'] = ['Early On Grade Level',	'#008000'];
        $columns[]['column_T'] = ['1 Grade Level Below',	'#FFFF00'];
        $columns[]['column_T'] = ['2 Grade Levels Below',	'#FF0000'];
        $columns[]['column_T'] = ['3 or More Grade Levels Below',	'#FF0000'];
        // Iready relative
        $columns[]['column_W'] = ['Mid or Above Grade Level',	'#0000FF'];
        $columns[]['column_W'] = ['Early On Grade Level',	'#008000'];
        $columns[]['column_W'] = ['1 Grade Level Below',	'#FFFF00'];
        $columns[]['column_W'] = ['2 Grade Levels Below',	'#FF0000'];
        $columns[]['column_W'] = ['3 or More Grade Levels Below',	'#FF0000'];
        // Iready relative
        $columns[]['column_Z'] = ['Mid or Above Grade Level',	'#0000FF'];
        $columns[]['column_Z'] = ['Early On Grade Level',	'#008000'];
        $columns[]['column_Z'] = ['1 Grade Level Below',	'#FFFF00'];
        $columns[]['column_Z'] = ['2 Grade Levels Below',	'#FF0000'];
        $columns[]['column_Z'] = ['3 or More Grade Levels Below',	'#FF0000'];
        // Iready relative
        $columns[]['column_AC'] = ['Mid or Above Grade Level',	'#0000FF'];
        $columns[]['column_AC'] = ['Early On Grade Level',	'#008000'];
        $columns[]['column_AC'] = ['1 Grade Level Below',	'#FFFF00'];
        $columns[]['column_AC'] = ['2 Grade Levels Below',	'#FF0000'];
        $columns[]['column_AC'] = ['3 or More Grade Levels Below',	'#FF0000'];
        // Iready relative
        $columns[]['column_AF'] = ['Mid or Above Grade Level',	'#0000FF'];
        $columns[]['column_AF'] = ['Early On Grade Level',	'#008000'];
        $columns[]['column_AF'] = ['1 Grade Level Below',	'#FFFF00'];
        $columns[]['column_AF'] = ['2 Grade Levels Below',	'#FF0000'];
        $columns[]['column_AF'] = ['3 or More Grade Levels Below',	'#FF0000'];
        // Caaspp
        $columns[]['column_N'] = ['4',	'#0000FF'];
        $columns[]['column_N'] = ['3',	'#008000'];
        $columns[]['column_N'] = ['2',	'#FFFF00'];
        $columns[]['column_N'] = ['1',	'#FF0000'];
        // Caaspp
        $columns[]['column_O'] = ['4',	'#0000FF'];
        $columns[]['column_O'] = ['3',	'#008000'];
        $columns[]['column_O'] = ['2',	'#FFFF00'];
        $columns[]['column_O'] = ['1',	'#FF0000'];
        // EasyCBM
        $columns[]['column_AP'] = ['Low',	'#008000'];
        $columns[]['column_AP'] = ['Some',	'#FFFF00'];
        $columns[]['column_AP'] = ['High',	'#FF0000'];
        // EasyCBM
        $columns[]['column_AQ'] = ['Low',	'#008000'];
        $columns[]['column_AQ'] = ['Some',	'#FFFF00'];
        $columns[]['column_AQ'] = ['High',	'#FF0000'];
        // Elpac
        $columns[]['column_BF'] = ['4- Well Developed',	'#0000FF'];
        $columns[]['column_BF'] = ['3- Moderately Developed',	'#008000'];
        $columns[]['column_BF'] = ['2- Somewhat Developed',	'#FFFF00'];
        $columns[]['column_BF'] = ['1- Beginning to Develop',	'#FF0000'];
        $columns[]['column_BF'] = ['3-ALT Sufficient',	'#008000'];
        $columns[]['column_BF'] = ['2-ALT Intermediate',	'#FFFF00'];



        foreach ($columns as $column) {
            foreach ($column as $k => $color)
            $data = [
                'cycle_id' => 10,
                'column_name' => $k,
                'value' => $color[0],
                'background_color' => $color[1],
                'color' => "#000000",
                'created_by' => 1,
            ];
            ConsolidateColor::create($data);
        }

        $equivalencesColumns = [
            'column_U',
            'column_X',
            'column_AA',
            'column_AD',
            'column_AJ',
        ];

        $equivalences = Equivalences::get();
        foreach ($equivalencesColumns as $column) {
            foreach ($equivalences as $equivalence) {
                $data = [
                    'cycle_id' => 10,
                    'column_name' => $column,
                    'value' => $equivalence->value,
                    'background_color' => "#000000",
                    'color' => "#ffffff",
                    'created_by' => 1,
                ];
                //ConsolidateColor::create($data);
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
        Schema::dropIfExists('consolidate_colors');
    }
};
