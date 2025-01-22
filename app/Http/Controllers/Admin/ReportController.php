<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consolidate3;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use App\Models\ConsolidateMapping;
use App\Models\Cycle;
use App\Models\FileUploads;
use App\Models\Formula;
use App\Models\GlobalActions;
use App\Models\Report;
use App\Models\SpecialistStudent;
use App\Models\StudentList;
use App\Models\TeacherStudent;
use PDF;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;


class ReportController extends Controller
{

    public function index()
    {
        $cycles = Cycle::getAllCycles();
        return view('reports.reports-list', compact('cycles'));
    }


    public function showErrors()
    {

        return view('reports.no-report-generated');
    }

    public function generateAnalysisReport($id)
    {
        $allTablesPerStudent = [];
        $cycle = Cycle::getCurrentCycle();
        $tableColumnInfo = \DB::select('SHOW FULL COLUMNS FROM consolidateds');
        if ($cycle) {
            $row = Consolidate3::where('cycle_id', $cycle->id)
                ->where('student_id', '<>', '')
                ->where('id', $id)
                ->first();
            if ($row) {
                $result = FileUploads::generateReport($row->id);
                if ($result) {
                    extract($result[0]);
                    $tableValues = $result[0];
                    $consolidate = $result[1];
                    //dd($tableColumnInfo);
                    return view('reports/analysis-report', compact('allTablesPerStudent', 'tableColumnInfo', 'tableValues', 'consolidate'));
                }
            }
        }
    }

    public function generateConsolidatedReport($overrideCycle = null)
    {
        if (!\Auth::user()->isAdmin()) {
            $overrideCycle = null;
        }
        LogActivity::addToLog('Generate Consolidated Report');
        $allTablesPerStudent = [];
        if (!$overrideCycle) {
            $cycle = Cycle::getCurrentCycle();
        } else {
            $cycle = Cycle::where('id', $overrideCycle)->first();
        }
        //dd($cycle,$overrideCycle);
        if ($cycle) {
            if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
                $rows = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('student_id', '<>', '')
                    ->orderBy('column_b', 'DESC')->get();
            } else {
                $rows = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('student_id', '<>', '')
                    ->where('teacher_id', \Auth::user()->getTeacherId())
                    ->orderBy('column_b', 'DESC')->get();
            }

            $url = url()->full() . '?to-pdf=Y';
            $isPDF = 'N';

            $html = "";
            foreach ($rows as $row) {
                $result = FileUploads::generateReport($row->id, $overrideCycle);
                if (!$result) {
                    continue;
                }
                //dd($result);
                extract($result[0]);
                $consolidate = $result[1];
                $isConsolidated = "Y";
                $html .= view(
                    //'reports/_individual-report',
                    'reports/_individual-report-v2',
                    compact(
                        'isConsolidated',
                        'isPDF',
                        'url',
                        'student',
                        'student_list',
                        'consolidate',
                        'math_lists',
                        'attendance_elas',
                        'attendance_maths',
                        'easy_cbm_falls',
                        'easy_cbm_progmons',
                        'freckle_minutes',
                        'i_ready_math_boys',
                        'i_ready_math_eoy_s',
                        'i_ready_math_mid_years',
                        'i_ready_reading_boy_s',
                        'i_ready_reading_eoy_s',
                        'i_ready_reading_mid_years',
                        'math180_minutes',
                        'read180_minutes',
                        'star_eoy_maths',
                        'star_eoy_readings',
                        'star_fall_maths',
                        'star_fall_readings',
                        'star_mid_year_maths',
                        'star_mid_year_readings',
                        'trans_math_minutes',
                        'i_ready_math_minutes',
                        'i_ready_reading_minutes',
                        'v_math_minutes',
                        'caaspps',
                        'caasppsMath',
                        'caasppsReading',
                        'elstudents',
                        'sst_reports',
                        'brainpops',
                        'tutor',
                        'student_accounts',
                    )
                )->render();
            }
            $url = url()->full() . '?to-pdf=Y';
            $isPDF = 'N';
            $isConsolidated = "Y";
            if (request()->has('to-pdf')) {
                $isPDF = 'Y';
            }

            if ($isPDF == 'Y') {
                // view()->share('reports/consolidated-report');
                // $pdf = PDF::loadView('reports/consolidated-report',compact('html'));
                // return $pdf->download('pdf_file_' . time() . '.pdf');
            }
            return view('reports/individual-report', compact('allTablesPerStudent', 'isConsolidated', 'url', 'isPDF', 'html'));
        }
    }
    public function generateConsolidatedReportCSV()
    {
        LogActivity::addToLog('Generate Consolidated Report CSV');
        $allTablesPerStudent = [];
        $cycle = Cycle::getCurrentCycle();
        if ($cycle) {
            if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
                $rows = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('student_id', '<>', '')
                    ->orderBy('column_b', 'DESC')->get();
            } else {
                $rows = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('student_id', '<>', '')
                    ->where('teacher_id', \Auth::user()->getTeacherId())
                    ->orderBy('column_b', 'DESC')->get();
            }
            $table = "Consolidate3";
            $fields = GlobalActions::getModelExpectedFields($table);
            $fields['consolidate3s']['fields'] = Consolidate3::getHeaders();
            //dd($fields['consolidate3s']['fields']);
            // generate header
            $header = [];
            $fieldsToPrint = [];
            $csvLine = [];
            $i = 1;

            foreach ($fields['consolidate3s']['fields'] as $key => $row) {
                $csvLine[$i][] = $row;
                $fieldsToPrint[] = $key;
            }
            $i++;
            //dd($csvLine,$fieldsToPrint);
            foreach ($rows as $row) {
                foreach ($fieldsToPrint as $k => $field) {
                     if ($fieldsToPrint[$k] =='Program') {
                        $csvLine[$i][] = \App\Models\Consolidate3::getProgramName($cycle,$row->student_id);
                     } elseif ($fieldsToPrint[$k]=='CAASPP Math') {
                        $csvLine[$i][] = \App\Models\Consolidate3::getCaasppMath($cycle,$row->student_id);
                     } elseif ($fieldsToPrint[$k]=='CAASPP Reading') {
                        $csvLine[$i][] = \App\Models\Consolidate3::getCaasppReading($cycle,$row->student_id);
                     } elseif ($fieldsToPrint[$k]=='Tutor.com Sessions') {
                        $csvLine[$i][] = \App\Models\Consolidate3::getTutorSessions($cycle,$row->student_id);
                     } else {
                          $csvLine[$i][] = trim($row->{$csvLine[1][$k]});
                     }
                }
                $i++;
                //dd($row,$csvLine);
            }
            $csvLine[1] = $fieldsToPrint;
            //dd($csvLine);
            // Set PHP headers for CSV output.
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=consolidated_report_' . date("Y_m_d") . '.csv');
            $output = fopen('php://output', 'w');
            // Write headers to CSV file.
            foreach ($csvLine as $data_item) {
                fputcsv($output, $data_item);
            }
            fclose($output);
        }
    }
    public function generateIndividualReport($id)
    {


        LogActivity::addToLog('Generate Individual Report');
        $allTablesPerStudent = [];
        $cycle = Cycle::getCurrentCycle();

        if ($cycle) {
            if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
                $consolidated = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('id', $id)
                    ->first();
            } else {
                $consolidated = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('id', $id)
                    ->where('teacher_id', \Auth::user()->getTeacherId())
                    ->first();
            }

            if (!$consolidated) {
                return view('reports.reports-list');
            }
            if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
                $rows = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('student_id', $consolidated->student_id)
                    ->orderBy('column_b', 'DESC')->get();
            } else {
                $rows = Consolidate3::where('cycle_id', $cycle->id)
                    ->where('student_id', $consolidated->student_id)
                    ->where('teacher_id', \Auth::user()->getTeacherId())
                    ->orderBy('column_b', 'DESC')->get();
            }

            $url = url()->full() . '?to-pdf=Y';
            $isPDF = 'N';

            $html = "";
            if (request()->has('to-pdf')) {
                $isPDF = 'Y';
            }

            foreach ($rows as $row) {
                $result = FileUploads::generateReport($row->id);

                if (!$result) {
                    continue;
                }
                extract($result[0]);

                $consolidate = $result[1];
                //dd($result);
                //dd($student);
                //dd($student_accounts);
                //dd($sst_reports);
                //dd($caaspps);
                //dd($consolidate);
                //dd($student_list[0]);
                //dd($easy_cbm_falls[0]);
                //dd($consolidate,$star_mid_year_maths[0],$star_fall_maths[0]);
                //dd($math_lists);
                //dd($i_ready_reading_eoy_s);
                //dd($easy_cbm_progmons);
                //dd($i_ready_math_boys[0],$i_ready_math_mid_years[0],$i_ready_math_eoy_s[0]);
                $isConsolidated = "N";
                //dd($caaspps,$caasppsMath,$caasppsReading);
                //dd($i_ready_math_eoy_s);
                $html .= view(
                    //'reports/_individual-report',
                    'reports/_individual-report-v2',
                    compact(
                        'isConsolidated',
                        'isPDF',
                        'url',
                        'student',
                        'student_list',
                        'consolidate',
                        'math_lists',
                        'attendance_elas',
                        'attendance_maths',
                        'easy_cbm_falls',
                        'easy_cbm_progmons',
                        'freckle_minutes',
                        'i_ready_math_boys',
                        'i_ready_math_eoy_s',
                        'i_ready_math_mid_years',
                        'i_ready_reading_boy_s',
                        'i_ready_reading_eoy_s',
                        'i_ready_reading_mid_years',
                        'math180_minutes',
                        'read180_minutes',
                        'star_eoy_maths',
                        'star_eoy_readings',
                        'star_fall_maths',
                        'star_fall_readings',
                        'star_mid_year_maths',
                        'star_mid_year_readings',
                        'trans_math_minutes',
                        'i_ready_math_minutes',
                        'i_ready_reading_minutes',
                        'v_math_minutes',
                        'caaspps',
                        'caasppsMath',
                        'caasppsReading',
                        'elstudents',
                        'sst_reports',
                        'brainpops',
                        'tutor',
                        'student_accounts',
                    )
                )->render();
                //dd($consolidate);
            }

            $url = url()->full() . '?to-pdf=Y';
            $isPDF = 'N';
            $isConsolidated = "N";
            if (request()->has('to-pdf')) {
                $isPDF = 'Y';
            }
            if ($isPDF == 'Y') {
                view()->share('reports/consolidated-report');
                $pdf = PDF::loadView('reports/consolidated-report', compact('html'));
                return $pdf->download('pdf_file_' . time() . '.pdf');
            }
            return view('reports/individual-report', compact('allTablesPerStudent', 'isConsolidated', 'url', 'isPDF', 'html'));
        }
    }

    public function ListStudents(Request $request)
    {
        $cycle =  Cycle::getCurrentCycle();
        $teacherId = TeacherStudent::getIdFromEmail();
        $myStudents = TeacherStudent::getAllTeacherStudents($teacherId, $request);
        $mySpecialistStudents = [];
        if (\Auth::user()->isSpecialist()) {
            $specialistId = SpecialistStudent::getIdFromEmail();
            $mySpecialistStudents = SpecialistStudent::getAllSpecialistStudents($specialistId, $request);

        }
        $teachersAvailable = TeacherStudent::select('teacher_id', 'first_name', 'last_name')
            ->where('cycle_id', $cycle->id)
            ->groupBy('teacher_id', 'first_name', 'last_name')
            ->orderBy('last_name')
            ->get();
        $specialistAvailable = SpecialistStudent::select('specialist_id', 'first_name', 'last_name')
            ->where('cycle_id', $cycle->id)
            ->groupBy('specialist_id', 'first_name', 'last_name')
            ->orderBy('last_name')
            ->get();
        return view('reports/list-my-students', compact('myStudents', 'mySpecialistStudents', 'teachersAvailable','specialistAvailable','cycle'));
    }

    public function ViewReport($studentId,$overrideCycle)
    {
        $cycles = Cycle::getAllCycles();
        if (!\Auth::user()->isAdmin()) {
            $overrideCycle = null;
        }
        if (!$overrideCycle) {
            $cycle = Cycle::getCurrentCycle();
        } else {
            $cycle = Cycle::where('id', $overrideCycle)->first();
        }
        $tempTableName = "consolidated_cycle_" . $cycle->id;
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $consolidatedRow = $tempTableModel->where("student_id", $studentId)->first();
        $report = Report::where('cycle_id', $cycle->id)->first();
        $tmpVariables = ConsolidateMapping::getOnlyConsolidatedTableFields();
        $siteVariables = [];
        foreach ($tmpVariables as $row) {
            $consolidatedVariables[] = "{" . $row->field_name . "}";
        }
        $html = "";
        if ($report) {
            if ($consolidatedRow) {
                $html = $report->report;
            }
            foreach ($consolidatedVariables as $varToParse) {
                $tmpVar = explode("->", trim($varToParse));
                $varToReplace = trim($tmpVar[1] ?? '');
                $varToParse = str_replace('>', '&gt;', $varToParse);
                //dd($varToParse,$tmpHtml);
                $html = str_replace($varToParse, $consolidatedRow->{$varToReplace}, $html);
            }
            $html = str_replace("{caasppmathcolor}", Formula::getConsolidatedValues("Color CAASPP Math", $studentId,$cycle), $html);
            $html = str_replace("{caasppreadingcolor}", Formula::getConsolidatedValues("Color CAASPP Reading", $studentId,$cycle), $html);
        }
        //dd($consolidatedVariables,$report->report,$tmpHtml);
        $url = url()->full() . '?to-pdf=Y';
        $isPDF = 'N';
        $isConsolidated = "N";
        if (request()->has('to-pdf')) {
            $isPDF = 'Y';
        }

        if ($isPDF == 'Y') {
            view()->share('reports/consolidated-report');
            $pdf = PDF::loadView('reports/consolidated-report',compact('html'));
            return $pdf->download('pdf_file_' . time() . '.pdf');
        }
        return view('reports/individual-report', compact('html','isConsolidated', 'url', 'isPDF','cycles'));
    }
}
