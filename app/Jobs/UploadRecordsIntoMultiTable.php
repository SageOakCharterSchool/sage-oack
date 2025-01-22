<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UploadRecordsIntoMultiTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $bulkData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bulkData)
    {
        $this->bulkData = $bulkData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $insert_data = collect($this->bulkData);
        $chunks = $insert_data->chunk(500);
        foreach ($chunks as $chunk) {
            DB::table('multi_table_fields')->insert($chunk->toArray());
        }
    }
}
