<?php

namespace App\Jobs;

use App\Models\TeacherStudent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class UploadRecordsTeacherStudents implements ShouldQueue
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
            //Log::info([$data,$this->fieldsForThisTable]);
            //dd($data,$dataFinal,$this->fieldsForThisTable);

            foreach ($this->fieldsForThisTable['fields'] as $fieldKey => $field) {
                $tmpData = [];
                //dd($field,$fieldKey,$this->fieldsForThisTable);

                if ($this->fieldsForThisTable['isStudent']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isStudent']]['colNumber']])) {
                        $tmpData['student_id'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isStudent']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isTeacher']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacher']]['colNumber']])) {
                        $tmpData['teacher_id'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacher']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isTeacherEmail']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacherEmail']]['colNumber']])) {
                        $tmpData['isTeacherEmail'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacherEmail']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isTeacherFirstName']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacherFirstName']]['colNumber']])) {
                        $tmpData['isTeacherFirstName'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacherFirstName']]['colNumber']];
                    }
                }
                if ($this->fieldsForThisTable['isTeacherLastName']) {
                    if (isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacherLastName']]['colNumber']])) {
                        $tmpData['isTeacherLastName'] = $data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isTeacherLastName']]['colNumber']];
                    }
                }
            }
            $tmpData = [
                'cycle_id' => $this->cycleId,
                'student_id' => $tmpData['student_id'],
                'teacher_id' => $tmpData['teacher_id'],
                'email' => $tmpData['isTeacherEmail'],
                'name' => $tmpData['isTeacherFirstName'] . " " . $tmpData['isTeacherLastName'],
                'first_name' => $tmpData['isTeacherFirstName'],
                'last_name' => $tmpData['isTeacherLastName'],
                'students_list' => null,
                'created_by' => $this->userId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $bulkData[] = $tmpData;


            if (count($bulkData) >= 300) {
                foreach (array_chunk($bulkData, 100) as $t) {
                    TeacherStudent::insert($t);
                };
                $bulkData = [];
            }
            //dd($dataFinal,$fieldsForThisTable['fields'],$bulkData);
            //$this->create($bulkData);
            //dd($bulkData);
            //var_dump($data);
        }
    }
}
