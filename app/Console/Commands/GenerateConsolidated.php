<?php

namespace App\Console\Commands;

use App\Jobs\AssignTeacherIdToRecordsUploaded;
use App\Models\BatchReports;
use App\Models\ConsolidateGeneration;
use App\Models\ConsolidateMapping;
use App\Models\Cycle;
use App\Models\TeacherStudent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateConsolidated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_consolidate:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $report = BatchReports::where("status",2)
            ->where('job_type',3)
            ->first();
        if ($report) {
            Log::info("Consolidate on wait because a upload running");
            return;  // upload in process
        }

        $report = BatchReports::where("status",1)
                    ->where('job_type',2)
                    ->first();
        if (!$report) {
            Log::info("NO Consolidate to run... see you");
            return;
        }
        Log::info("Started Consolidated Generation report: " . $report->id);
        $report->status = 2;
        $report->started_at = date("Y-m-d H:i:s");
        $report->save();

        ConsolidateGeneration::markGenerationAsInProcess(3);

        ConsolidateMapping::buildConsolidated();
        ConsolidateMapping::updateColumnA();
        TeacherStudent::reassignTeacherIds();
        ConsolidateGeneration::markGenerationAsInProcess(1);

        $report->status = 3;
        $report->completed_at = date("Y-m-d H:i:s");
        $report->result = "Completed normally";
        $report->save();
        Log::info("Completed normally => Consolidated Generation report: " . $report->id);

    }
}
