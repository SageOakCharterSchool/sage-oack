<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Mail;

use App\Mail\NotifySFTPPasswordUpdate;
use App\Models\Cycle;
use App\Models\StudentAccounts;

class UpdatePasswordsFromSFTP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:updatepasswords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update passwords from SFTP file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * This is the structure expected
         *  [0] => clever_id
         *  [1] => sis_id
         *  [2] => student_number
         *  [3] => email
         *  [4] => google_id
         *  [5] => provisioned_password
         */
        set_time_limit(0);
        ini_set('memory_limit','-1');
        $cycle = Cycle::getCurrentCycle();
        $contents = Storage::disk('sftp')->allFiles(env('SFTP_ROOT'));
        $response = new StreamedResponse;
        $appPath = storage_path('app');
        $totalRows = 0;
        $totalUpdates = 0;
        foreach ($contents as $content) {
            if ($content == 'idm-sensitive-exports/google-students-passwords.csv') {
                Storage::disk('local')->put($content, Storage::disk('sftp')->get($content));
                $file = fopen($appPath . "/" . $content,"r");
                $totalRows = 1;
                $totalUpdates = 0;
                while(! feof($file))
                {
                    $row = fgetcsv($file);
                    if (!$row) {
                        continue;
                    }
                    $data = [
                        'column_f' => $row[5]
                    ];
                    $result = StudentAccounts::where('cycle_id',$cycle->id)
                        ->where('student_id',$row[1])
                        ->update($data);
                    $totalRows++;
                    if ($result) {
                        $totalUpdates++;
                    }
                }
                Storage::delete($content);
            }
        }
        //dd($files);
        $mailData['notes'] = "Update Password SFTP Process completed";
        $mailData['totalRows'] = $totalRows;
        $mailData['totalUpdates'] = $totalUpdates;
        Mail::to(env('jmendoza@sageoak.education'))
            ->bcc(['jmancera@gmail.com'])
            ->send(new NotifySFTPPasswordUpdate ($mailData));

        return Command::SUCCESS;
    }
}
