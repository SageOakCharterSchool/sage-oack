<?php

namespace App\Jobs;

use App\Models\MasterTables;
use App\Models\MultiTableFields;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignTeacherIdToRecordsUploaded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tableId;
    protected $cycleId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cycleId, $tableId)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->cycleId = $cycleId;
        $this->tableId = $tableId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $table = MasterTables::getTableId('teacher_students');
        $id1 = $table->id;
        $table = MasterTables::getTableId('tutor');
        $id2 = $table->id;
        $tablesToSkip = [$id1, $id2];

        // if (in_array($this->tableId, $tablesToSkip)) {
        //     return;
        // }

        //dd($table);
        $teacherRows = MultiTableFields::select('teacher_id', 'student_id')
            ->where('cycle_id', $this->cycleId)
            ->where("table_id", $id1)
            ->groupBy('teacher_id','student_id')
            ->get();
        foreach ($teacherRows as $teacherRow) {
            MultiTableFields::where('cycle_id', $this->cycleId)
                ->whereNotIn("table_id", $tablesToSkip)
                ->where("student_id", $teacherRow->student_id)
                ->where("teacher_id", 0)
                ->update([
                    'teacher_id' => $teacherRow->teacher_id
                ]);
        }
    }
}
