<?php

namespace App\Console\Commands;

use App\Models\FileUploads;
use Illuminate\Console\Command;

class PurgeUnusedUploadedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploade:purge-unused-files';

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
        FileUploads::purgeUploadedFiles();
        return Command::SUCCESS;
    }
}
