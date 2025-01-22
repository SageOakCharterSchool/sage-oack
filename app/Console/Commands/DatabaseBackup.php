<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";

        $command = "mysqldump --user=" . env('DB_USERNAME2') ." --password=" . env('DB_PASSWORD2') . " --host=" . env('DB_HOST2') . " " . env('DB_DATABASE2') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;

        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);

        \Log::warning('Running backup '.  date("Y-m-d H:i:s") . " " . "<pre>".print_r($output, true)."</pre>");
    }
}
