<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\StudentAccounts;
use App\Models\TeacherStudent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UploadRecordsStudentAccounts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $dataCSV, $cycleId, $tableId, $i, $from, $to;
    public $fieldsForThisTable, $userId, $csvTotLines, $tableName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dataCSV, $cycleId, $tableId, $fieldsForThisTable, $userId, $csvTotLines, $tableName)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->cycleId = $cycleId;
        $this->tableId = $tableId;
        $this->dataCSV = $dataCSV;
        $this->fieldsForThisTable = $fieldsForThisTable;
        $this->userId = $userId;
        $this->csvTotLines = $csvTotLines;
        $this->tableName = $tableName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bulkData = [];
        $bulkDataTeachers = [];

        Log::info("In URSA " . $this->tableName);
        $rows = TeacherStudent::orderBy('teacher_id')
            ->where('cycle_id', $this->cycleId)
            ->orderBy('student_id')
            ->get(['student_id', 'teacher_id']);
        foreach ($rows as $row) {
            $teachersStudents[$row->student_id] = $row->teacher_id;
        }
        $row = 0;
        foreach ($this->dataCSV as $data) {
            if ($row == 0) {
                $row++;
                continue;
            }
            //Log::info([$data,$this->tableName]);
            foreach ($data as $k => $field) {
                $field = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $field);
                $field = preg_replace('/\s+/S', " ", $field);
                $field = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $field);
                $field = trim(preg_replace("/(\s*[\r\n]+\s*|\s+)/", ' ', $field));
                $data[$k] = $field;
            }
            $dataFinal[] = $data;
            $row++;
            //Log::info([$data, $this->fieldsForThisTable]);
            //dd($data,$dataFinal,$this->fieldsForThisTable);
            foreach ($this->fieldsForThisTable['fields'] as $fieldKey => $field) {
                $tmpData = [];
                //dd($field,$fieldKey,$this->fieldsForThisTable);

                if ($this->fieldsForThisTable['isStudent']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isStudent']]['colNumber']])) {
                        $tmpData['student_id'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isStudent']]['colNumber']];
                    }
                }

                if ($this->fieldsForThisTable['isStudentEmail']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isStudentEmail']]['colNumber']])) {
                        $tmpData['isStudentEmail'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isStudentEmail']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isFirstName']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isFirstName']]['colNumber']])) {
                        $tmpData['isFirstName'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isFirstName']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isLastName']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isLastName']]['colNumber']])) {
                        $tmpData['isLastName'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isLastName']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isDOB']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isDOB']]['colNumber']])) {
                        $tmpData['isDOB'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isDOB']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isPassword']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isPassword']]['colNumber']])) {
                        $tmpData['isPassword'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isPassword']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isGrade']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isGrade']]['colNumber']])) {
                        $tmpData['isGrade'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isGrade']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isProgram']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isProgram']]['colNumber']])) {
                        $tmpData['isProgram'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isProgram']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isSIS']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isSIS']]['colNumber']])) {
                        $tmpData['isSIS'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isSIS']]['colNumber']];
                    }
                }
                $tmp1 = substr(trim(strtoupper($tmpData['isFirstName'])), 0, 1); // FirstNameInital
                $tmp2 = substr(trim(strtoupper($tmpData['isLastName'])), 0, 1); // LastNameInitial
                $tmp3 = date("d", strtotime($tmpData['isDOB']));
                $tmp4 = date("m", strtotime($tmpData['isDOB']));
                $tmp5 = date("y", strtotime($tmpData['isDOB']));
                $pass = $tmp1 . $tmp2 . $tmp4 . $tmp3 . $tmp5;
                $teacherId = 0;
                if (isset($teachersStudents[$tmpData['student_id']])) {
                    $teacherId = $teachersStudents[$tmpData['student_id']];
                }
            }
            if (!StudentAccounts::checkIfStudentHasPasswordChangedOnCurrentCycle($this->cycleId, $tmpData['student_id'])) {
                $tmpData = [
                    'cycle_id' => $this->cycleId,
                    'student_id' => $tmpData['student_id'],
                    'teacher_id' => $teacherId,
                    'column_a' => $tmpData['isFirstName'] ?? null, // first name
                    'column_b' => $tmpData['isLastName'] ?? null, // last name
                    'column_c' => $tmpData['student_id'] ?? null, // student id
                    'column_d' => $tmpData['isGrade'] ?? null, // grade
                    'column_e' => $tmpData['isStudentEmail'] ?? null, // email
                    'column_f' => $pass, // Password
                    'column_g' => $tmpData['isDOB'] ?? null, // Date of Birth
                    'column_h' => $tmpData['isProgram'] ?? null, // Program
                    'column_i' => $tmpData['isSIS'] ?? null, // SIS
                    'created_by' => $this->userId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $bulkData[] = $tmpData;
                Log::info($tmpData);
            }


            if (count($bulkData) >= 300) {
                foreach (array_chunk($bulkData, 100) as $t) {
                    Log::info($t);
                    StudentAccounts::insert($t);
                };
                $bulkData = [];
            }
            //dd($dataFinal,$fieldsForThisTable['fields'],$bulkData);
            //$this->create($bulkData);
            //dd($bulkData);
            //var_dump($data);
        }

        //$teachersStudents = TeacherStudent::select('student_id')->get()->keyBy('teacher_id')->toArray();



    }
}
