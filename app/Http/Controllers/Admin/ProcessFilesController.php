<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\FileUploads;
use App\Models\StudentList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Validator;
use App\Models\GlobalActions;
use App\Models\StudentAccounts;
use App\Models\TeacherStudent;

class ProcessFilesController extends Controller
{
    public function index() {

        if (!Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        LogActivity::addToLog('Show screen to upload Files');
        $cycle = Cycle::getCurrentCycle();
        //dd($cycle);
        if ($cycle) {
            //$haveRecords = StudentList::checkIfStudentListHasRecordsOnCycle($cycle);
            $studentListhasRecords = StudentAccounts::checkIfStudentListHasRecordsOnCycle($cycle);
            $modelInfo = GlobalActions::getModelsExpectedFields();
            return view('admin/files/upload-files');
        } else {
            return view('admin/files/no-cycle');
        }
        //dd(config('constants.tables'));
    }

    public function uploadfile(Request $request) {
        ini_set('post_max_size', '128M');
        ini_set('upload_max_filesize', '128M');
        if (!Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        LogActivity::addToLog('Upload Files');
        $size = $request->file('data_file')->getSize();
        $fileType = $request->file('data_file')->getClientMimeType();


        if (!($fileType == 'text/csv' || $fileType == 'application/octet-stream')) {
            abort(
                response()->json(['message' => 'Invalid file type'], 405)
            );
        }
        $post_max_size = (ini_get('upload_max_filesize'));
        //dd($post_max_size);
        if ($size) {
            if ($size > 40000000 ) { // 2 MB
                abort(
                    response()->json(['message' => 'Invalid file size'], 403)
                );
            }
        }
        //dd($_FILES);
        //dd($request->all());
        //dd($request->hasFile('data_file'));
        //dd($size,$fileType);
        if ($request->hasFile('data_file')) {
            $cycle = Cycle::getCurrentCycle();
            //dd($cycle);
            if ($cycle) {

                $tableName = $request->header('tablename');
                FileUploads::markAnyUnusedUpload($tableName);
                $checkIfCreateorUpdate = FileUploads::checkIfCreateOrReplace($tableName);
                $file = $request->file('data_file');
                $fileName = $file->getclientOriginalName();
                $folder = 'uploads/data-files/' . Auth::id() . "/". uniqid() . '-' . now()->timestamp;
                $file->storeAs( $folder , $fileName);

                $data = [
                    'cycle_id' => $cycle->id,
                    'uploaded_on' => date("Y-m-d"),
                    'created_by' => Auth::id(),
                    'table_name' => $tableName,
                    'file_name' => $fileName,
                    'file_path' => $folder
                ];
                if ($checkIfCreateorUpdate) {
                    FileUploads::where('id',$checkIfCreateorUpdate->id)->update($data);
                } else {
                    FileUploads::create($data);
                }
                return $folder;
            } else {
                abort(
                    response()->json(['message' => 'Invalid cycle'], 402)
                );
            }
        }
        return '';
    }


    public function processFiles() {
        if (!Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        LogActivity::addToLog('Process Files');
        $cycle = Cycle::getCurrentCycle();
        if ($cycle) {
            //$studentListhasRecords = StudentList::checkIfStudentListHasRecordsOnCycle($cycle);
            $studentListhasRecords = StudentAccounts::checkIfStudentListHasRecordsOnCycle($cycle);
            return view('admin/files/process-files',compact('studentListhasRecords'));
        } else {
            return view('admin/files/no-cycle');
        }


    }
    public function startProcessAllFile(Request $request) {
        if ($request->confirmProcessAllFiles != 1) {
            return redirect('admin/process-files')->with('error_message','Please confirm to process all the files');
        }
        $cycle = Cycle::getCurrentCycle();
        if ($cycle) {
            //$studentListhasRecords = StudentList::checkIfStudentListHasRecordsOnCycle($cycle);
            $studentListhasRecords = StudentAccounts::checkIfStudentListHasRecordsOnCycle($cycle);
        }
        $uploadingErrorResults = [];
        $uploadingSuccessResults = [];
        $hasError = 0;
        $uploaded = 0;
        $errorMessage = "";
        $successMessage = "";
        foreach (config('constants.tables') as $table) {
            if (($table == 'student_lists' && !$studentListhasRecords) || ($studentListhasRecords)) {
                LogActivity::addToLog('Start process Upload Files Batch -> ' . $table);
                $request->request->add(['my_table_name' => $table]); //add request
                $result = FileUploads::processUpload($request);
                $uploaded++;
                if ($result['hasError'] == 1) {
                    $hasError = 1;
                    $uploadingErrorResults[$table] = $result['fileErrors'];
                    $errorMessage .= "Error processing table -> " . $table . "<br>";
                } else if ($result['hasError'] == '') {
                    // nothing to do here
                } else {
                    $uploadingSuccessResults[$table] = $result['fileErrors'];
                    $successMessage .= "Success processing table -> " . $table . "<br>";
                }
            }
        }
        if ($uploaded > 0) {
            if ($hasError == 1) {
                return redirect('admin/process-files')->with('error_message',$errorMessage);
            } else {
                return redirect('admin/process-files')->with('no_error_message',$successMessage);
            }
        }


    }
    public function startProcessFile(Request $request) {
        LogActivity::addToLog('Start process Upload Files');
        $result = FileUploads::processUpload($request);
        if ($result['hasError'] != 0) {
            return redirect('admin/process-files')->with('errorMessage_'.$result['table'],$result['fileErrors']);
        } else {
            return redirect('admin/process-files')->with('successMessage_'.$result['table'],$result['fileErrors']);
        }

    }

    public function consolidate() {
        LogActivity::addToLog('Consolidate Files');
        $cycle = Cycle::getCurrentCycle();
        if ($cycle) {
            //$studentListhasRecords = StudentList::checkIfStudentListHasRecordsOnCycle($cycle);
            $studentListhasRecords = StudentAccounts::checkIfStudentListHasRecordsOnCycle($cycle);
            return view('admin/files/consolidate',compact('studentListhasRecords'));
        } else {
            return view('admin/files/no-cycle');
        }

    }

    public function consolidateAllFiles(Request $request) {
        LogActivity::addToLog('Consolidate Processing ');
        $validator = Validator::make($request->all(), [
            'confirmProcess' => 'required',
        ]);
        if ($validator->fails()) {
            \Session::flash('error-message', 'Please confirm you want to run the process');
            return redirect('/admin/consolidate');
        }
        // check if teacher_student has been uploaded for the current cycle
        if (!TeacherStudent::checkIfTeacherStudentHasRecordsOnCycle()) {
            \Session::flash('error-message', 'Consolidation process FAILED due no data on Teacher Student for this cycle ');
        } else {
            $result = FileUploads::consolidateFiles();
            if ($result) {
                \Session::flash('success-message', 'Consolidation process finished successfully, records generated ' . $result);
            } else {
                \Session::flash('error-message', 'Consolidation process FAILED due wrong Cycle, records generated ' . $result);
            }
        }
        return redirect('/admin/consolidate');

    }

    public function exportFileInfoToCSV($table) {
        $modelInfo = GlobalActions::fullMapping($table);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$table.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        $callback = function() use ($table, $modelInfo)
        {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array_keys($modelInfo));
            fputcsv($handle, $modelInfo );
            fclose(($handle));
        };
        //return Response::download($table, 'tweets.csv', $headers);
        return response()->stream($callback, 200, $headers)->send();
    }

}
