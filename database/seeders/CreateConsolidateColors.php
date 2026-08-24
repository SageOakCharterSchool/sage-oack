<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ConsolidateColor;
use App\Models\Equivalences;

class CreateConsolidateColors extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConsolidateColor::truncate();
        $toProcess = [
            'iReady',
            'caaspp',
            'easyCBM',
            'elpac'
        ];
        $iReady['Cols'] = [
            "Q",
            "T",
            "W",
            "Z",
            "AC",
            "AF",
            "BG",
            "BH",
            "BI",
            "BJ",
            "BK",
            "BL",
            "BN",
            "BM",
            "BO",
        ];
        $iReady['Colors'] = [
            ["Mid or Above Grade Level", 	"#0000FF"],
            ["Early On Grade Level", 	"#008000"],
            ["1 Grade Level Below", 	"#FFFF00"],
            ["2 Grade Levels Below", 	"#FF0000"],
            ["3 or More Grade Levels Below", 	"#FF0000"],
            ["On Level", 	"#008000"],
            ["1 Level Below", 	"#FFFF00"],
            ["2 or More Levels Below", 	"#FF0000"],
            ["Above Level", 	"#0000FF"],
        ];
        $caaspp['Cols'] = [
            // "N",
            // "O",
            "Q",
            "R",
            "BP",
            "BQ",
            "BR",
            "BS",
            "BU",
            "BV",
        ];
        $caaspp['Colors'] = [
            ['4',	'#0000FF'],
            ['3',	'#008000'],
            ['2',	'#FFFF00'],
            ['1',	'#FF0000'],
        ];
        $easyCBM['Cols'] = [
            'AP',
            'AQ',
            'AS',
            'AT',
        ];
        $easyCBM['Colors'] = [
            ['Low',	'#008000'],
            ['Some',	'#FFFF00'],
            ['High',	'#FF0000'],
        ];
        $elpac['Cols'] = [
            "BF"
        ];
        $elpac['Colors'] = [
            ['4- Well Developed',	'#0000FF'],
            ['3- Moderately Developed',	'#008000'],
            ['2- Somewhat Developed',	'#FFFF00'],
            ['1- Beginning to Develop',	'#FF0000'],
            ['3-ALT Sufficient',	'#008000'],
            ['2-ALT Intermediate',	'#FFFF00'],
        ];

        foreach($toProcess as $toProc) {
            $cols = ${$toProc}['Cols'];
            foreach(${$toProc}['Cols'] as $col) {
                foreach(${$toProc}['Colors'] as $color) {
                    $data = [
                        'cycle_id' => 10,
                        'column_name' => "column_" . $col,
                        'value' => $color[0],
                        'background_color' => $color[1],
                        'color' => "#000000",
                        'created_by' => 1,
                    ];
                    ConsolidateColor::create($data);
                }
            }
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
}
