<?php

namespace App\Console\Commands;

use App\Jobs\GenerateExcelReport;
use App\Models\BatchReports;
use Illuminate\Console\Command;
use App\Models\Cycle;
use App\Models\ConsolidateMapping;
use App\Models\ConsolidateColor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GenerateExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_excel:process';

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
        $report = BatchReports::where("status",1)
                    ->where('job_type',1)
                    ->first();
        if (!$report) {
            return;
        }
        Log::info("Started Excel Generation report: " . $report->id);
        $report->status = 2;
        $report->started_at = date("Y-m-d H:i:s");
        $report->save();

        $user = User::where('id',$report->created_by)->first();
        if (!$user) {
            Log::info("Aborted: Wrong user => Excel Generation report: " . $report->id);
            $report->status = 3;
            $report->completed_at = date("Y-m-d H:i:s");
            $report->result = "Aborted: Wrong user";
            $report->save();
            return;
        }

        \Auth::loginUsingId($user->id, TRUE);
        $request = new \Illuminate\Http\Request();

        set_time_limit(0);
        ini_set('memory_limit','-1');
        $cycle = Cycle::getCurrentCycle();
        $cycleId = $report->cycle_id;
        $sectionId = $report->section_id;
        $overrideCycle = null;
        $exportFormat = "EXCEL";
        $return = ConsolidateMapping::generateReport($request,$sectionId, $cycleId,  $overrideCycle, $exportFormat);
        //dd($return);
        if (!isset($return['rows'])) {
            session()->flash('error-message', $return['status']);
            return redirect("/admin/consolidate-mappings");
        }
        $rows = $return['rows'];
        $consolidatedBasicFields = $return['consolidatedBasicFields'];
        $consolidatedFields = $return['consolidatedFields'];
        $cycles = $return['cycles'];
        $sections = $return['sections'];
        $reportsList = $return['reportsList'];
        $consolidateColors = ConsolidateColor::getAllColumnColors($cycleId);

        $job = new GenerateExcelReport(
            $cycleId,
            $rows,
            $consolidatedFields,
            $consolidatedBasicFields,
            $cycles,
            $sections,
            $sectionId,
            $consolidateColors
        );
        // //$job->dispatch();
        $job->handle();
        $report->status = 3;
        $report->completed_at = date("Y-m-d H:i:s");
        $report->result = "Completed normally";
        $report->save();
        Log::info("Completed normally => Excel Generation report: " . $report->id);

    }
}
