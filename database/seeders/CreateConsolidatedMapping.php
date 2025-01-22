<?php

namespace Database\Seeders;

use App\Models\ConsolidateMapping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cycle;

class CreateConsolidatedMapping extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            'id',
            'teacher_id',
            'cycle_id',
            'Student ID',
            'Student Last Name',
            'Student First Name',
            'Grade',
            'SIS',
            'Program',
            'Teacher Name',
            'Qualifying Subject 1',
            'Qualifying Subject 2',
            'ELA Intervention Recommendation',
            'ELA Intervention Selected',
            'Math Intervention Recommendation',
            'MATH Intervention Selected',
            'CAASPP Math',
            'CAASPP Reading',
            'iReady Math Points Fall',
            'iReady Relative Placement Math Fall',
            'iReady Level Math Fall',
            'iReady Reading Points Fall',
            'iReady Relative Placement Reading Fall',
            'iReady Level Reading Fall',
            'iReady Math Points Mid Year',
            'iReady Relative Placement Math Mid Year',
            'iReady Level Math Mid Year',
            'iReady Reading Points Mid Year',
            'iReady Relative Placement Reading Mid Year',
            'iReady Level Reading Mid Year',
            'iReady Math Points End of Year',
            'iReady Relative Placement Math End of Year',
            'iReady Level Math End of Year',
            'iReady Reading Points End of Year',
            'iReady Relative Placement Reading End of Year',
            'iReady Level Reading End of Year',
            'iReady Growth Points Math Mid Year',
            'iReady Levels Math Growth Mid Year',
            'iReady Growth Points Reading Mid Year',
            'iReady Levels Reading Growth Mid Year',
            'iReady Growth Points Math End of Year',
            'IReady Levels Math Growth End of Year',
            'iReady Growth Points Reading End of Year',
            'IReady Levels Reading Growth End of Year',
            'easyCBM Reading Risk',
            'easyCBM Math Risk',
            'Intervention Math Class Attendance',
            'Intervention Reading Class Attendance',
            'iReady Minutes Math',
            'iReady Minutes Reading',
            'Reading Class Minutes',
            'Math Class Minutes',
            'Tutor.com Sessions',
            'Class Info',
            'Notes',
            'SST',
            'sped',
            'ELD',
        ];
        $columns = [
            'column_A',
            'column_B',
            'column_C',
            'column_D',
            'column_E',
            'column_F',
            'column_G',
            'column_H',
            'column_I',
            'column_J',
            'column_K',
            'column_L',
            'column_M',
            'column_N',
            'column_O',
            'column_P',
            'column_Q',
            'column_R',
            'column_S',
            'column_T',
            'column_U',
            'column_V',
            'column_W',
            'column_X',
            'column_Y',
            'column_Z',
            'column_AA',
            'column_AB',
            'column_AC',
            'column_AD',
            'column_AE',
            'column_AF',
            'column_AG',
            'column_AH',
            'column_AI',
            'column_AJ',
            'column_AK',
            'column_AL',
            'column_AM',
            'column_AN',
            'column_AO',
            'column_AP',
            'column_AQ',
            'column_AR',
            'column_AS',
            'column_AT',
            'column_AU',
            'column_AV',
            'column_AW',
            'column_AX',
            'column_AY',
            'column_AZ',
            'column_BA',
            'column_BB',
            'column_BC',
            'column_BD',
            'column_BE',
            'column_BF',

        ];
        $cycle = Cycle::getCurrentCycle();
        //dd($columns);
        $sort = 10;
        foreach ($fields as $k => $field) {
            //dd($k,$field,$columns[$k]);
            $data = [
                'screen_sort' => $sort,
                'cycle_id' => $cycle->id,
                'column_name' => $columns[$k] ,
                'column_description' => $field,
                'table_source' => 0,
                'field_source' => 0,
                'is_formulated' => 0,
                'formula_id' => null,
                'created_by' => 1,
            ];
            ConsolidateMapping::insert($data);
            $sort += 10;
        }
    }
}
