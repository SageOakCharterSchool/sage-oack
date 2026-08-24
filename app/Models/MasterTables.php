<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Schema;
use App\Helpers\LogActivity;

class MasterTables extends Model
{
    use HasFactory;
    protected $table = 'master_tables';
    protected $fillable = [
        'cycle_id',
        'table_name',
        'table_alias',
        'created_by',
        'is_system',
        'allow_upload',
        'process_status', //0-created/1-Completed/2-In Process(1)/3-Uploading Records
    ];

    protected function cloneTablesIntoNewCycle($cycleFrom, $cycleTo)
    {
        $clonedTables = [];
        $this->where("cycle_id", $cycleTo)->delete(); // remove all tables for new cycle
        TablesMapping::where("cycle_id", $cycleTo)->delete(); // remove all fields for new cycle
        $tables = $this->where("cycle_id", $cycleFrom)
            ->get();
        foreach ($tables as $table) {
            $newTable = $table->replicate();
            $newTable->cycle_id = $cycleTo;
            $newTable->save();
            $clonedTables[$table->id] = $newTable->id;
            TablesMapping::cloneFieldsIntoClonedTable($cycleFrom, $cycleTo, $table->id, $newTable->id);
        }
        return $clonedTables;
    }

    protected function createMasterTables($cycleId)
    {
        foreach (config('constants.tablesAlias') as $tableToCreate => $tableAlias) {
            $table = $this->where("cycle_id", $cycleId)
                ->where('table_name', $tableToCreate)
                ->first();
            if (!$table) {
                $data = [
                    'cycle_id' => $cycleId,
                    'table_name' => $tableToCreate,
                    'table_alias' => $tableAlias,
                    'created_by' => \Auth::user()->id,
                    'is_system' => 0,
                    'allow_upload' => 1,
                ];
                $this->create($data);
            }
        }
    }

    protected function getTableId($tableName = null)
    {
        $cycle = Cycle::getCurrentCycle();
        if (!$tableName) {
            $tableName = 'student_accounts';
        }
        $tableInfo = MasterTables::where('table_name', $tableName)
            ->where("cycle_id", $cycle->id)
            ->first();
        return $tableInfo;
    }

    protected function resetTablesInfo()
    {
        LogActivity::addToLog('reset tables info');
        $cycle =  Cycle::getCurrentCycle();
        StudentAccounts::where("cycle_id", $cycle->id)->delete();
        TeacherStudent::where("cycle_id", $cycle->id)->delete();
        //------------------------
        $table = MasterTables::getTableId('teacher_students');
        MultiTableFields::where("cycle_id", $cycle->id)
            ->where('table_id', $table->id)
            ->delete();
        MasterTables::where("cycle_id", $cycle->id)
            ->where('id', $table->id)
            ->update(['process_status' => 0]);
        UploadFilesLog::where("cycle_id", $cycle->id)
            ->where('table_id', $table->id)
            ->update(['total_records' => 0]);
        //------------------------
        $table = MasterTables::getTableId('student_accounts');
        MultiTableFields::where("cycle_id", $cycle->id)
            ->where('table_id', $table->id)
            ->delete();
        MasterTables::where("cycle_id", $cycle->id)
            ->where('id', $table->id)
            ->update(['process_status' => 0]);
        UploadFilesLog::where("cycle_id", $cycle->id)
            ->where('table_id', $table->id)
            ->update(['total_records' => 0]);

        $tempTableName = "consolidated_cycle_" . $cycle->id;
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $tempTableModel->delete();
    }

    protected function buildTablesList(): array
    {
        $cycle = Cycle::getCurrentCycle();
        $tmpTablesList = MasterTables::where("cycle_id", $cycle->id)
                            ->orderBy('table_name')
                            ->get();
        $tablesList = [];
        foreach ($tmpTablesList as $row) {
            //$siteVariables[] = "[~" . $row->id . "|" . $row->table_id . "~]{" . $row->field_name . "}";
            $tablesList[$row->table_name] = $row->table_alias;
        }
        return $tablesList;
    }

    protected function calculateTotalRecords()
    {
        $cycle = Cycle::getCurrentCycle();
        // $sql = "select  table_id, total_records  from upload_files_logs where cycle_id = ? group by table_id order by table_id;";
        // $rows = \DB::select($sql, [$cycle->id]);
        $rows = UploadFilesLog::select('table_id', 'total_records')
                    ->where("cycle_id",$cycle->id)
                    ->orderBy('table_id')
                    ->groupBy('table_id','total_records')
                    ->get();
        $totalRowsUploaded = [];

        foreach ($rows as $row) {
            $totalRowsUploaded[$row->table_id] = $row->total_records;
        }

        $totalFields = [];

        $rows = TablesMapping::select('table_id',\DB::raw('count(*) as total_fields'))
                    ->where("cycle_id",$cycle->id)
                    ->orderBy('table_id')
                    ->groupBy('table_id')
                    ->get();

        foreach ($rows as $row) {
            $totalFields[$row->table_id] = $row->total_fields;
        }
        $totalRows = [];
        $totalRecods = 0;
        foreach ($totalFields as $k => $totalField) {
            if (isset($totalRowsUploaded[$k])) {
                $totalRecods += ($totalRowsUploaded[$k] * $totalField);
            }
        }
        dd($totalRecods,"Total Tables: " . count($totalRowsUploaded));
    }

}
