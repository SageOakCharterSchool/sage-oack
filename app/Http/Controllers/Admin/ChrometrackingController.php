<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherStudent;
use Illuminate\Http\Request;
use App\Models\Cycle;
use App\Models\StudentAccounts;

class ChrometrackingController extends Controller
{
    public function index()
    {
        return view('admin.chrome-tracking');
    }

    public function uploadTracking()
    {
        return view('admin.upload-chrome-tracking');
    }

    public function processUploadTracking(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|mimes:csv,txt|max:8096', // Example validation rules
        ]);

        set_time_limit(0);
        ini_set('memory_limit','-1');
        $cycle = Cycle::getCurrentCycle();
        $cycleId = $cycle->id;

        $file = $request->file('file_upload');


        //$fileName = $file->getclientOriginalName();
        $fileName = "tracking_". uniqid() . ".csv";
        $folder = 'uploads/data-files/' . \Auth::id() . "/". uniqid() . '-' . now()->timestamp;
        $file->storeAs( $folder , $fileName);
        $filePath = $folder . "/". $fileName;
        //$contents = \Storage::get($filePath);

        $myFilePath = \Storage::path($filePath);
        $data = array_map('str_getcsv', file($myFilePath));
        unset($data[0]);
        $read = 0;
        $updated = 0;
        foreach ($data as $row) {
            $read++;
            $record = StudentAccounts::where("cycle_id",$cycleId)
                            ->where("student_id",$row[2])
                            ->first();
            if ($record) {
                //$record->column_k = $row[0];
                $record->column_k = filter_var($row[0], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
                $record->save();
                $updated++;
            }
        }

        return back()->with('message', 'File uploaded successfully! Records:' . $read . " Records Updated: " . $updated);
    }
}
