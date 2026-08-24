<?php

namespace App\Models;

use App\Jobs\DeleteMultiTableFieldsRecords;
use App\Jobs\MarkBatchUploadAsCompleted;
use App\Jobs\ProcessUploadedFileInChunks;
use App\Jobs\UploadRecordsIntoMultiTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cycle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MultiTableFields extends Model
{
    use HasFactory;
    protected $table = 'multi_table_fields';
    protected $fillable = [
        'teacher_id',
        'student_id',
        'cycle_id',
        'table_id',
        'field_id',
        'row_number',
        'column',
        'field_value',
        'created_by',
        'action',
    ];

    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    protected function removeRecordsForTableThisCycle($cycleId, $tableId)
    {
        //dd($cycleId,$tableId);
        $loops = 1;
        do {
            $result = MultiTableFields::where("cycle_id", $cycleId)
                ->where("table_id", $tableId)
                ->limit(1000)
                ->delete();
            Log::info("Deleting Multitables Table Id: " . $tableId . " chunk of 1000 " . ($loops++ * $result));
        } while ($result > 0);
    }

    protected function loadDataIntoFile($request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $cycle = Cycle::getCurrentCycle();
        //dd($cycle);
        //dd($request->all());
        if ($cycle) {
            $tableName = $request->table_name;
            $tableId = (int)$request->table_id;
            //$this->removeRecordsForTableThisCycle($cycle->id, $tableId);
            // if (getenv("DISPATCH_JOBS") == 0) {
            //     $job = new DeleteMultiTableFieldsRecords($cycle->id, $tableId);
            //     $job->handle();
            // } else {
            //     DeleteMultiTableFieldsRecords::dispatch($cycle->id, $tableId);
            // }
            $table = MasterTables::getTableId('teacher_students');
            if ($tableId == $table->id) { // Table Teacher_Student
                TeacherStudent::removeRecordsOnCurrentCycle($cycle);
            }
            $table = MasterTables::getTableId('student_accounts');
            if ($tableId == $table->id) { // Table Student accounts
                StudentAccounts::removeRecordsOnCurrentCycle($cycle);
            }

            //$fieldsForThisTable = TablesMapping::getFieldsForTable($cycle->id,$tableId);

            $data = [];
            $file = $request->file('file_to_upload');
            $fileName = $file->getclientOriginalName();
            $tmpFileName = \Auth::id() . DIRECTORY_SEPARATOR . uniqid() . '-' . now()->timestamp;
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $folder = 'uploads' . DIRECTORY_SEPARATOR . 'data-files' . DIRECTORY_SEPARATOR . $tmpFileName;
            } else {
                $folder = 'uploads' . DIRECTORY_SEPARATOR . 'data-files' . DIRECTORY_SEPARATOR . $tmpFileName;
            }
            $file->storeAs($folder, $fileName);
            $path = storage_path('app/');
            $fileToProcess = $path . $folder . DIRECTORY_SEPARATOR . $fileName;
            $bulkData = [];
            $dataFinal = [];
            $fp = file($fileToProcess, FILE_SKIP_EMPTY_LINES);
            $csvTotLines = (count($fp));
            $rowNumber = 0;
            if (($handle = fopen($fileToProcess, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 20000, ",")) !== FALSE) {
                    //dd($data);
                    if ($rowNumber == 0) {
                        $dataFinal[] = array_map(function ($e) {
                            return rtrim($e, "\n\r");
                        }, $data);
                        $fieldsForThisTable = TablesMapping::buildFieldsFromFirstRow($cycle->id, $tableId, $data, $request);
                        $rowNumber++;
                        //dd($data,$fieldsForThisTable);
                        continue; // skip headers
                    }
                    $dataFinal[] = array_map(function ($e) {
                        return rtrim($e, "\n\r");
                    }, $data);

                    $rowNumber++;
                }

                for ($i = 0; $i <= ($csvTotLines / 20000); $i++) {
                    $from = $i * 20000;
                    if (($from + 19999) > $csvTotLines) {
                        $to = $csvTotLines;
                    } else {
                        $to = $from + 19999;
                        if ($to > $csvTotLines) {
                            $to = $csvTotLines;
                        }
                    }
                    // if (getenv("DISPATCH_JOBS") == 0) {
                    //     $job = new ProcessUploadedFileInChunks($dataFinal, $cycle->id, $tableId, $i, $from, $to, $fieldsForThisTable, \Auth::user()->id, $csvTotLines, $tableName);
                    //     $job->handle();
                    // } else {
                        $dataToSend = [
                            'dataFinal' => $dataFinal,
                            'cycle' => $cycle->id,
                            'tableId' => $tableId,
                            'i' => $i,
                            'from' => $from,
                            'to' => $to,
                            'fieldsForThisTable' => $fieldsForThisTable,
                            'user' => \Auth::user()->id,
                            'csvTotLines' => $csvTotLines,
                            'tableName' => $tableName
                        ];
                        $cycle = Cycle::getCurrentCycle();
                        $data = [
                            'cycle_id' => $cycle->id,
                            'section_id' => null,
                            'student_id' => null,
                            'report_id' => null,
                            'created_by' => \Auth::user()->id,
                            'status' => 1,
                            'started_at' => null,
                            'completed_at' => null,
                            'job_type' => 3,
                            'payload' => json_encode($dataToSend)
                        ];
                        BatchReports::create($data);
                        //ProcessUploadedFileInChunks::dispatch($dataFinal, $cycle->id, $tableId, $i, $from, $to, $fieldsForThisTable, \Auth::user()->id, $csvTotLines, $tableName);
                    //}
                }
                $dataLog = [
                    'cycle_id' => $cycle->id,
                    'table_id' => $tableId,
                    'total_records' => $csvTotLines,
                    'file_name' => $folder . $fileName,
                    'file_contents' => json_encode($bulkData),
                    'uploaded_by' => Auth::id(),
                ];
                //dd($dataLog);
                UploadFilesLog::create($dataLog);
            } else {
            }
            unlink($fileToProcess);
        }
    }

    protected function getAllTheRowsForRecordThatHasMultipleRecords($cycleId,$tableId,$studentId,$column) {
        //\DB::enableQueryLog();
        $rows = MultiTableFields::select('student_id','row_number' )
            ->where("cycle_id", $cycleId)
            ->where("table_id", $tableId)
            ->where("student_id", $studentId)
            ->where("column", $column)
            ->groupBy('student_id','row_number')
            ->get();
            //dd(\DB::getQueryLog());
            //dd($rows);
        return $rows;
    }

    protected function backupTableWithNewCycle($oldCycle=null, $newCycle=null) {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        if (!$oldCycle) {
            die("No old cycle provided");
        }
        if (!$newCycle) {
            die("No new cycle provided");
        }
        $backupTableName = "multi_table_fields_" . $oldCycle;
        if (!Schema::hasTable($backupTableName)) { // make sure table does not exists already
            // creates table structure
            $db = \DB::connection();
            $sql = 'CREATE TABLE ' . $backupTableName . ' LIKE ' . $this->table;
            $db->statement($sql);
            Log::info($sql . " executed ");
            echo $sql . "\r\n";
            //inserts table data
            $sql = 'INSERT IGNORE INTO ' . $backupTableName . ' SELECT * FROM ' . $this->table . ' where cycle_id = ' . $oldCycle;
            $db->statement($sql);
            Log::info($sql . " executed ");
            echo $sql . "\r\n";
            // Add addtional column if you watch
            $this->where('cycle_id', $oldCycle)->delete();
            die("Duplicate Table $backupTableName completed");
        } else {
            die("Table $backupTableName already exists");
        }

    }
}
