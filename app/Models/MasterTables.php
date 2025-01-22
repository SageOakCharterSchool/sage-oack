<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected function cloneTablesIntoNewCycle($cycleFrom, $cycleTo) {
        $clonedTables = [];
        $this->where("cycle_id",$cycleTo)->delete(); // remove all tables for new cycle
        TablesMapping::where("cycle_id",$cycleTo)->delete(); // remove all fields for new cycle
        $tables = $this->where("cycle_id",$cycleFrom)
                        ->get();
        foreach ($tables as $table) {
            $newTable = $table->replicate();
            $newTable->cycle_id = $cycleTo;
            $newTable->save();
            $clonedTables[$table->id] = $newTable->id;
            TablesMapping::cloneFieldsIntoClonedTable($cycleFrom,$cycleTo,$table->id,$newTable->id);
        }
        return $clonedTables;
    }

    protected function createMasterTables($cycleId) {
        foreach (config('constants.tablesAlias') as $tableToCreate => $tableAlias) {
            $table = $this->where("cycle_id",$cycleId)
                        ->where('table_name',$tableToCreate)
                        ->first();
            if (!$table) {
                $data = [
                    'cycle_id' =>$cycleId,
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

    protected function getTableId($tableName=null) {
        $cycle = Cycle::getCurrentCycle();
        if (!$tableName) {
            $tableName = 'student_accounts';
        }
        $tableInfo = MasterTables::where('table_name',$tableName)
            ->where("cycle_id", $cycle->id)
            ->first();
        return $tableInfo;
    }

}
