<?php

namespace App\Console\Commands;

use App\Jobs\AssignTeacherIdToRecordsUploaded;
use App\Models\BatchReports;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Jobs\MarkBatchUploadAsCompleted;
use App\Jobs\UploadRecordsStudentAccounts;
use App\Jobs\UploadRecordsTeacherStudents;
use App\Models\MasterTables;
use App\Models\MultiTableFields;
use App\Models\Cycle;
use App\Models\StudentAccounts;
use App\Models\TeacherStudent;

class UploadRecordsIntoMultiTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload-records-into-multi-table:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public $fieldsForThisTable;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $report = BatchReports::where("status",2)
            ->where('job_type',2)
            ->first();
        if ($report) {
            Log::info("Uploads on wait because a consolidate running");
            return;  // consolidate in process
        }
        $report = BatchReports::where("status",2)
            ->where('job_type',3)
            ->first();
        if ($report) {
            Log::info("Uploads running");
            return;  // one upload in process
        }
        $report = BatchReports::where("status",1)
            ->where('job_type',3)
            ->first();
        if (!$report) {
            Log::info("No Uploads to run... good bye");
            return;
        }
        Log::info("File Uploads report: " . $report->id);
        $report->status = 2;
        $report->started_at = date("Y-m-d H:i:s");
        $report->save();

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $data = json_decode($report->payload,true);

        $i = $data['i'];
        $from = $data['from'];
        $to = $data['to'];
        $cycleId = $data['cycle'];
        $tableId = $data['tableId'];
        $report->table_id = $tableId;
        $report->save();
        $dataCSV = $data['dataFinal'];
        $this->fieldsForThisTable = $data['fieldsForThisTable'];
        $userId = $data['user'];
        $csvTotLines = $data['csvTotLines'];
        $tableName = $data['tableName'];
        Log::info("Parameters to insert uploaded multi-table-fileds for " . $tableName . " from -> " . $from . " to " . $to);
        MasterTables::where('id',$tableId)
                ->where('cycle_id',$cycleId)
                ->update(['process_status'=>2]);
        MultiTableFields::removeRecordsForTableThisCycle($cycleId, $tableId);
        Log::info("Remove records on multi-table-fileds for " . $tableName );
        $table = MasterTables::getTableId('teacher_students');
        if ($tableId == $table->id) { // Table Teacher_Student
            TeacherStudent::where('cycle_id',$cycleId)->delete();
        }
        $table = MasterTables::getTableId('student_accounts');
        if ($tableId == $table->id) { // Table Student accounts
            StudentAccounts::where('cycle_id',$cycleId)
                ->where('password_changed',0)
                ->delete();
        }
        //Log::info($this->fieldsForThisTable);
        //Log::info("From " . $from);
        //Log::info("To " . $to);
        //dd($this->fieldsForThisTable);
        $bulkData = [];
        $bulkDataStudents = [];
        $bulkDataTeachers = [];
        $rowNumber = $from + 1;
        Log::info($tableName);
        $totalRowsToInsert = count($dataCSV);
        $totalFieldsToInsert = count($this->fieldsForThisTable);
        for ($j = $from; $j <= $to; $j++) {
            if ($j == 0) {
                continue;
            }
            $data = $dataCSV[$j] ?? [];
            //Log::info([$j,$from,$to,$data,$tableName]);
            foreach ($data as $k => $field) {
                $field = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $field);
                $field = preg_replace('/\s+/S', " ", $field);
                $field = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $field);
                $field = trim(preg_replace("/(\s*[\r\n]+\s*|\s+)/", ' ', $field));
                $data[$k] = $field;
            }
            $dataFinal[] = $data;

            //Log::info([$j,$from,$to,$data,$this->fieldsForThisTable]);
            //dd($data,$dataFinal,$this->fieldsForThisTable);
            foreach ($this->fieldsForThisTable['fields'] as $fieldKey => $field) {
                //dd($field,$fieldKey,$this->fieldsForThisTable);
                $tmpData = [
                    'student_id' => 0,
                    'teacher_id' => 0,
                    'cycle_id' => $cycleId,
                    'table_id' => $tableId,
                    'field_id' => $field['fieldId'],
                    'row_number' => $rowNumber,
                    'column' => $fieldKey,
                    'field_value' => $data[$field['colNumber']] ?? 0,
                    'created_by' => $userId,
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


                if ($tableName == "Student Accounts") {
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
                if (count($bulkData) % 100 == 0) {
                    //Log::info("Inserting records on Multi Tables table: " . $tableName . " rows " . count($bulkData));
                }
                if (count($bulkData) >= 3000) {
                    Log::info("Inserting 3000 rows on Multi Tables table: " . $tableName . " Rows => " . $totalRowsToInsert . " Fields => " . $totalFieldsToInsert . " Total expected in Multitablefields => " . ($totalFieldsToInsert  * $totalRowsToInsert ));
                    //Log::info("Inserting records on Multi Tables table: " . $tableName);
                    $insert_data = collect($bulkData);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk) {
                        \DB::table('multi_table_fields')->insert($chunk->toArray());
                    }
                    //UploadRecordsIntoMultiTable::dispatch($bulkData);

                    $bulkData = [];
                }
            }

            //dd($dataFinal,$this->fieldsForThisTable['fields'],$bulkData);
            //$create($bulkData);
            //dd($bulkData);
            $rowNumber++;
            //var_dump($data);
        }


        $insert_data = collect($bulkData);
        $chunks = $insert_data->chunk(500);
        foreach ($chunks as $chunk) {
            Log::info("Final Inserting records on Multi Tables table: " . $tableName);

            \DB::table('multi_table_fields')->insert($chunk->toArray());
        }

            //UploadRecordsIntoMultiTable::dispatch($bulkData);
        $bulkData = [];
        if ($to >= $csvTotLines) {
            if ($tableName == "Teacher Student") {
                $job = new UploadRecordsTeacherStudents($dataCSV, $cycleId, $tableId, $this->fieldsForThisTable, $userId, $csvTotLines, $tableName);
                $job->handle();
            }
            if ($tableName == "Student Accounts") {
                $job = new UploadRecordsStudentAccounts($dataCSV, $cycleId, $tableId, $this->fieldsForThisTable, $userId, $csvTotLines, $tableName);
                $job->handle();
            }
            // Log::info("Assign teachers to records uploaded " . $tableName);
            // $job = new AssignTeacherIdToRecordsUploaded($cycleId, $tableId);
            // $job->handle();
            Log::info("Mark batch as completed " . $tableName);
            $job = new MarkBatchUploadAsCompleted($cycleId, $tableId);
            $job->handle();

        }

        $report->status = 3;
        $report->completed_at = date("Y-m-d H:i:s");
        $report->result = "Completed normally";
        $report->save();
        Log::info("Completed normally => Upload Table: " . $tableName . " Report Id: " . $report->id);
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
