<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchReports extends Model
{
    use HasFactory;

    protected $table = "batch_reports";
    protected $fillable = [
        'cycle_id',
        'section_id',
        'student_id',
        'report_id',
        'table_id',
        'created_by',
        'status', // 1 = in queue / 2 = in process / 3 = completed
        'started_at',
        'completed_at',
        'result',
        'payload',
        'job_type', // 1 = Excel reports / 2 = Consolidated / 3 = File Uploads
    ];

    protected function isErroredTable()
    {
        $nowMinus24Hours = now()->subHours(24)->format('Y-m-d H:i:s');
        $result = BatchReports::where('started_at', ">=", $nowMinus24Hours)
            ->where('job_type', 3)
            ->whereIn('status', [1, 2])
            ->get();
        if ($result->isEmpty()) {
            return false;
        }
        return true;
    }

    protected function checkIfTableIsInError($tableId)
    {
        $nowMinus24Hours = now()->subHours(24)->format('Y-m-d H:i:s');
        $result = BatchReports::where('started_at', ">=", $nowMinus24Hours)
            ->where('job_type', 3)
            ->where('table_id', $tableId)
            ->whereIn('status', [1, 2])
            ->first();
        if ($result) {
            return true;
        }
        return false;
    }
    protected function removeTableErrored($tableId, $cycleId)
    {
        $nowMinus24Hours = now()->subHours(24)->format('Y-m-d H:i:s');
        BatchReports::where('started_at', ">=", $nowMinus24Hours)
            ->where('job_type', 3)
            ->where('table_id', $tableId)
            ->whereIn('status', [1, 2])
            ->delete();
        MasterTables::where('cycle_id', $cycleId)
            ->where('id', $tableId)
            ->where('process_status', 2)
            ->update(
                [
                    'process_status' => 1
                ]
            );
    }
    protected function resetTableErrored($tableId, $cycleId)
    {
        $nowMinus24Hours = now()->subHours(24)->format('Y-m-d H:i:s');
        BatchReports::where('started_at', ">=", $nowMinus24Hours)
            ->where('job_type', 3)
            ->where('table_id', $tableId)
            ->whereIn('status', [1, 2])
            ->update([
                'status' => 3,
                'started_at' => null
            ]);
        MasterTables::where('cycle_id', $cycleId)
            ->where('id', $tableId)
            ->where('process_status', 2)
            ->update(
                [
                    'process_status' => 1
                ]
            );
    }
}
