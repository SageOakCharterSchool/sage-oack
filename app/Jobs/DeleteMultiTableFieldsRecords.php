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

class DeleteMultiTableFieldsRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tableId;
    protected $cycleId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cycleId,$tableId)
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
        MasterTables::where('id',$this->tableId)
                ->where('cycle_id',$this->cycleId)
                ->update(['process_status'=>2]);
        MultiTableFields::removeRecordsForTableThisCycle($this->cycleId, $this->tableId);
    }
}
