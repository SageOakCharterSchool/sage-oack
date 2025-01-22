<?php

namespace App\Jobs;

use App\Models\TablesMapping;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\UploadRecordsIntoMultiTable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\MarkBatchUploadAsCompleted;

class ProcessUploadedFileInChunks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $dataCSV, $cycleId, $tableId, $i, $from, $to;
    public $fieldsForThisTable, $userId, $csvTotLines, $tableName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dataCSV, $cycleId, $tableId, $i, $from, $to, $fieldsForThisTable, $userId, $csvTotLines, $tableName)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->i = $i;
        $this->from = $from;
        $this->to = $to;
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
        //Log::info($this->fieldsForThisTable);
        //Log::info("From " . $this->from);
        //Log::info("To " . $this->to);
        //dd($this->fieldsForThisTable);
        $bulkData = [];
        $bulkDataStudents = [];
        $bulkDataTeachers = [];
        $rowNumber = $this->from + 1;
        Log::info($this->tableName);
        for ($j = $this->from; $j <= $this->to; $j++) {
            if ($j == 0) {
                continue;
            }
            $data = $this->dataCSV[$j] ?? [];
            //Log::info([$j,$this->from,$this->to,$data,$this->tableName]);
            foreach ($data as $k => $field) {
                $field = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $field);
                $field = preg_replace('/\s+/S', " ", $field);
                $field = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $field);
                $field = trim(preg_replace("/(\s*[\r\n]+\s*|\s+)/", ' ', $field));
                $data[$k] = $field;
            }
            $dataFinal[] = $data;

            //Log::info([$j,$this->from,$this->to,$data,$this->fieldsForThisTable]);
            //dd($data,$dataFinal,$this->fieldsForThisTable);
            foreach ($this->fieldsForThisTable['fields'] as $fieldKey => $field) {
                //dd($field,$fieldKey,$this->fieldsForThisTable);
                $tmpData = [
                    'student_id' => 0,
                    'teacher_id' => 0,
                    'cycle_id' => $this->cycleId,
                    'table_id' => $this->tableId,
                    'field_id' => $field['fieldId'],
                    'row_number' => $rowNumber,
                    'column' => $fieldKey,
                    'field_value' => $data[$field['colNumber']] ?? 0,
                    'created_by' => $this->userId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'action' => 'uploaded',
                ];
                //dd($this->fieldsForThisTable);

                //$data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isFirstName']]['colNumber']])
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


                if ($this->tableName == "Student Accounts") {
                    if ($fieldKey == $this->fieldsForThisTable['isPassword']) {
                        $calculatedValue = $this->calculateSpecialValues($data);
                        if ($calculatedValue) {
                            $tmpData['field_value'] = $calculatedValue;
                        }
                    }
                }
                $bulkData[] = $tmpData;
                if ($tmpData['student_id'] == 0) {
                    continue;
                }
                if (count($bulkData) >= 3000) {
                    if (getenv("DISPATCH_JOBS") == 0) {
                        //dd($bulkData);
                        //Log::info($bulkData);
                        $job = new UploadRecordsIntoMultiTable($bulkData);
                        $job->handle();
                    } else {
                        UploadRecordsIntoMultiTable::dispatch($bulkData);
                    }

                    $bulkData = [];
                }
            }

            //dd($dataFinal,$fieldsForThisTable['fields'],$bulkData);
            //$this->create($bulkData);
            //dd($bulkData);
            $rowNumber++;
            //var_dump($data);
        }
        if (getenv("DISPATCH_JOBS") == 0) {
            //dd($bulkData);
            //Log::info($bulkData);
            $job = new UploadRecordsIntoMultiTable($bulkData);
            $job->handle();
        } else {
            UploadRecordsIntoMultiTable::dispatch($bulkData);
        }
        $bulkData = [];
        if ($this->to >= $this->csvTotLines) {
            if (getenv("DISPATCH_JOBS") == 0) {
                if ($this->tableName == "Teacher Student") {
                    $job = new UploadRecordsTeacherStudents($this->dataCSV, $this->cycleId, $this->tableId, $this->fieldsForThisTable, $this->userId, $this->csvTotLines, $this->tableName);
                    $job->handle();
                }
                if ($this->tableName == "Student Accounts") {
                    $job = new UploadRecordsStudentAccounts($this->dataCSV, $this->cycleId, $this->tableId, $this->fieldsForThisTable, $this->userId, $this->csvTotLines, $this->tableName);
                    $job->handle();
                }
                $job = new AssignTeacherIdToRecordsUploaded($this->cycleId, $this->tableId);
                $job->handle();
                $job = new MarkBatchUploadAsCompleted($this->cycleId, $this->tableId);
                $job->handle();
            } else {

                if ($this->tableName == "Teacher Student") {
                    UploadRecordsTeacherStudents::dispatch($this->dataCSV, $this->cycleId, $this->tableId, $this->fieldsForThisTable, $this->userId, $this->csvTotLines, $this->tableName);
                }
                if ($this->tableName == "Student Accounts") {
                    UploadRecordsStudentAccounts::dispatch($this->dataCSV, $this->cycleId, $this->tableId, $this->fieldsForThisTable, $this->userId, $this->csvTotLines, $this->tableName);
                }
                AssignTeacherIdToRecordsUploaded::dispatch($this->cycleId, $this->tableId);
                MarkBatchUploadAsCompleted::dispatch($this->cycleId, $this->tableId);
            }
        }
    }

    /*
        Used to calculate special values on fields like
        student_accounts->password
        // special formula for password
        // Jun 08 2024
        // Here is the password format.
        // BB060824 FirstNameInital+LastNameInitial+DD+MM+YR
        // (Six digit date of birth)
        $tmp1 = substr(trim(strtoupper($row[0])),0,1); // FirstNameInital
            $tmp2 = substr(trim(strtoupper($row[1])),0,1); // LastNameInitial
            $tmp3 = date("d", strtotime($row[6]));
            $tmp4 = date("m", strtotime($row[6]));
            $tmp5 = date("y", strtotime($row[6]));
            $pass = $tmp1 . $tmp2 . $tmp4 . $tmp3 . $tmp5;
    */

    private function calculateSpecialValues($data)
    {

        if (
            $this->fieldsForThisTable['isFirstName'] &&
            $this->fieldsForThisTable['isLastName'] &&
            $this->fieldsForThisTable['isDOB'] &&
            $this->fieldsForThisTable['isPassword']
        ) {
            if (
                isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isFirstName']]['colNumber']]) &&
                isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isLastName']]['colNumber']]) &&
                isset($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isDOB']]['colNumber']])
            ) {

                $tmp1 = substr(trim(strtoupper($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isFirstName']]['colNumber']])), 0, 1); // FirstNameInital
                $tmp2 = substr(trim(strtoupper($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isLastName']]['colNumber']])), 0, 1); // LastNameInitial
                $tmp3 = date("d", strtotime($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isDOB']]['colNumber']]));
                $tmp4 = date("m", strtotime($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isDOB']]['colNumber']]));
                $tmp5 = date("y", strtotime($data[$this->fieldsForThisTable['fields'][$this->fieldsForThisTable['isDOB']]['colNumber']]));
                $pass = $tmp1 . $tmp2 . $tmp4 . $tmp3 . $tmp5;
                return $pass;
            }
        }
        return false;
    }
}
