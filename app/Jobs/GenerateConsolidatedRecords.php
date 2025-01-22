<?php

namespace App\Jobs;

use App\Models\ConsolidateGeneration;
use App\Models\ConsolidateMapping;
use App\Models\TeacherStudent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateConsolidatedRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ConsolidateGeneration::markGenerationAsInProcess(3);
        ConsolidateMapping::buildConsolidated();
        ConsolidateMapping::updateColumnA();
        TeacherStudent::reassignTeacherIds();
        ConsolidateGeneration::markGenerationAsInProcess(1);
    }
}
