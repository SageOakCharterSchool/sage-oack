<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cycle;
use App\Models\MasterTables;
use App\Models\MultiTableFields;
use App\Models\TablesMapping;
use App\Models\UploadFilesLog;

class CreateTablesList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = [
            "student_lists",
            "teacher_students",
            "math_lists",
            "consolidate3s",
            "attendances",
            "attendance_elas",
            "attendance_maths",
            "brainpops",
            "caaspps",
            "easy_cbm_falls",
            "easy_cbm_progmons",
            "elstudents",
            "freckle_minutes",
            "i_ready_math_boys",
            "i_ready_math_eoy_s",
            "i_ready_math_mid_years",
            "i_ready_math_minutes",
            "i_ready_reading_boy_s",
            "i_ready_reading_eoy_s",
            "i_ready_reading_mid_years",
            "i_ready_reading_minutes",
            "math180_minutes",

            "read180_minutes",
            "star_eoy_maths",
            "star_eoy_readings",
            "star_fall_maths",
            "star_fall_readings",
            "star_mid_year_maths",
            "star_mid_year_readings",
            "student_accounts",
            "trans_math_minutes",
            "v_math_minutes",
        ];
        $alias = [
            "consolidate3s" => "consolidated",
        ];
        $isSystem = [
            "math_lists" => "1",
            "student_lists" => "1",
            "teacher_students" => "1",
            "consolidate3s" => "1",
        ];
        $notUpload = [
            "consolidate3s" => "1",
        ];
        MasterTables::truncate();
        TablesMapping::truncate();
        MultiTableFields::truncate();
        UploadFilesLog::truncate();
        $cycle =  Cycle::getCurrentCycle();
        foreach ($tables as $table) {
            if (!isset($alias[$table])) {
                $name = str_replace("_"," ",$table);
                $name = ucwords($name);
            } else {
                $name = str_replace("_"," ",$alias[$table]);
                $name = ucwords($name);
            }
            $isSystemTable = 0;
            $isUpload = 1;
            if (isset($isSystem[$table])) {
                $isSystemTable = 1;
            }
            //dd($isSystem,$table,$)
            if (isset($notUpload[$table])) {
                $isUpload = 0;
            }
            $data = [
                'cycle_id' => $cycle->id,
                'table_name' => $name,
                'is_system' => $isSystemTable,
                'allow_upload' => $isUpload,
                'created_by' => 1,
            ];
            $row = MasterTables::create($data);
            //dd($row->id);
            $this->buildFields($row->id,$table,$cycle->id);
            $sql = ' update tables_mappings set is_student_id = 1 WHERE column_title = "SSID" or column_title = "Student ID" ';
            $results = \DB::select($sql);
        }
    }

    public function buildFields($tableId, $table, $cycleId ) {
        $fieldsToRemove = ["created_by","student_id","teacher_id","cycle_id","students_list","created_at","updated_at","id"];
        $fullColumns = \DB::select('SHOW FULL COLUMNS FROM '. $table);
        $fieldsInfo = $modelDetails = [];
        //dd($fullColumns);
        foreach ($fullColumns as $fullColumn) {
            $field = $fullColumn->Field;
            if (!in_array($field,$fieldsToRemove)) {
                $fieldsInfo[$field] = $fullColumn->Comment;
                $modelDetails[$table]['fields'][$field] = $fullColumn->Comment;
                $data = [
                    'cycle_id' => $cycleId,
                    'table_id' => $tableId,
                    'column' => $field,
                    'column_title' => $fullColumn->Comment,
                    'created_by' => 1,
                ];
                TablesMapping::create($data);
            }
        }
    }
}
