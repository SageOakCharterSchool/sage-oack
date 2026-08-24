<?php

namespace App\Jobs;

use App\Mail\MailableReport;
use App\Models\ConsolidateColor;

use App\Models\GlobalActions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;


class GenerateExcelReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $cycleId;
    public $rows;
    public $consolidatedFields;
    public $consolidatedBasicFields;
    public $cycles;
    public $sections;
    public $sectionId;
    public $consolidateColors;
    public function __construct(
        $cycleId,
        $rows,
        $consolidatedFields,
        $consolidatedBasicFields,
        $cycles,
        $sections,
        $sectionId,
        $consolidateColors
    )
    {
        $this->cycleId = $cycleId;
        $this->rows = $rows;
        $this->consolidatedFields = $consolidatedFields;
        $this->consolidatedBasicFields = $consolidatedBasicFields;
        $this->cycles = $cycles;
        $this->sections = $sections;
        $this->sectionId = $sectionId;
        $this->consolidateColors = $consolidateColors;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $consolidateColors = ConsolidateColor::getAllColumnColors($this->cycleId);
        $spreadsheet = $csvRows = GlobalActions::generateExcel(
            $this->rows,
            $this->consolidatedFields,
            $this->consolidatedBasicFields,
            $this->cycles,
            $this->sections,
            $this->sectionId,
            $this->consolidateColors
        );

        $fileName = '/app/excel/' . uniqid(). '_consolidated_report_'. date("Y-m-d") . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(storage_path($fileName));

        Mail::to(\Auth::user()->email)->send(
             new MailableReport(\Auth::user(),storage_path($fileName))
        );

        unlink(storage_path($fileName));



    }
}
