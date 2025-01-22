<?php

namespace App\Http\Controllers;

use App\Helpers\JMHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\HelloEmail;
use App\Models\Attendance;
use App\Models\AttendanceEla;
use App\Models\GlobalActions;
use App\Models\TeacherStudent;
use App\Models\Cycle;
use App\Models\StudentList;
use Illuminate\Http\Request;
use App\Models\Consolidate3;
use App\Models\ConsolidateMapping;
use App\Models\FileUploads;
use App\Models\Formula;
use App\Models\MultiTableFields;
use App\Models\StudentAccounts;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;


class TestController extends Controller
{
    public function __construct()
    {
        if (getenv("APP_ENV") != "local") {
            die("No soup for you...");
        }
    }

    public function testMe(Request $request) {
        //exit();
        // get consolidated values based on formula
        $cycle = Cycle::getCurrentCycle();
        $studentId = 1005195734;
        $formulaName = "Color CAASPP Math";
        $result = Formula::getConsolidatedValues($formulaName, $studentId,$cycle);
        dd($result);


        // verify formula
        $cycle = Cycle::getCurrentCycle();
        $formulaId = 122;
        $studentId = 1005195734;
        //$studentId = 3828430956;
        //$studentId = 1005195734;
        //$studentId = 8470039263;

        $formulaInfo = Formula::where("id", $formulaId)->first();
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)->orderBy('screen_sort')->get();
        $studentAccountRecord = MultiTableFields::select('teacher_id', 'student_id')
            ->where("cycle_id", $cycle->id)
            ->where('table_id', 6)
            ->where('student_id', $studentId)
            ->groupBy('teacher_id', 'student_id')
            // ->take(50)
            ->first();
        if (!$studentAccountRecord)  {
            die("No records");
        }
        $data = [];
        $data['cycle_id'] = $cycle->id;
        $data['teacher_id'] = $studentAccountRecord->teacher_id;
        $data['student_id'] = $studentAccountRecord->student_id;
        //dd($data);
        //dd($formulaInfo);
        $result = Formula::formulaParsing($formulaId, $formulaInfo, $studentAccountRecord, $cycle, $data);
        dd($result);
        exit;
        $id = 3;
        $formulasUsed = [];
        $cycle = Cycle::getCurrentCycle();
        $tempTableName = "consolidated_cycle_" . $cycle->id;
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $consolidatedRow = $tempTableModel->where("id", $id)->first();
        //dd($consolidatedRow);
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)->orderBy('screen_sort')->get();
        foreach ($fields as $field) {
            if ($field->formula_id) {
                $formulasUsed[$field->formula_id] = $field->formula_id;
            }
        }
        $formulasInfo = [];
        foreach ($formulasUsed as $formulaId) {
            $formulasInfo[$formulaId] = Formula::where("id", $formulaId)->first();
        }
        $formula = $formulasInfo[8];
        //dd($formulasUsed,$formula);

        $result = Formula::formulaParsing($formula->id, $formula, $consolidatedRow, $cycle,[]);
        dd($result);
        //ConsolidateMapping::updateColumnA();
        exit;
        ConsolidateMapping::buildConsolidated();
        exit;
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }
        //$student = StudentList::where("student_id",9924616686)->first();
        $student = StudentAccounts::where("student_id",9924616686)->first();

        $teacherStudent = TeacherStudent::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);

        $value = JMHelper::JMGetMultipleValues($teacherStudent,'first_name','last_name','teacher_student');
        dd($value,$student,$teacherStudent);


        //dd($student);
        $result = FileUploads::generateReport(11259); //5228209271
        //$result = FileUploads::generateReport(7766); //6654952517
        extract($result[0]);
        $data = [
            'student_id' => $student->student_id, //student id
            'cycle_id' => $cycle->id,
            'column_a' => $student->student_id, //Student ID
            'column_b' => JMHelper::JMEncrypt($student->column_a), //Student Last Name
            'column_c' => JMHelper::JMEncrypt($student->column_b), //Student First Name
            'column_d' => $student->column_c,
            'column_e' => $student->column_a,
            'column_f' => $student->column_b,
            'column_g' => $student->column_d,
            'column_h' => $student->column_e,
            'column_h' => $student->column_e,
            'column_i' => $student->column_f,
            'column_j' => $student->column_g,
            'column_k' => JMHelper::JMGetValues($math_lists,'column_f','math_lists'),
            'column_l' => $student->column_j,
            'column_m' => JMHelper::JMGetValues($math_lists,'column_j','math_lists'),
            'column_n' => $student->column_o,
            'column_o' => JMHelper::JMGetValues($math_lists,'column_o','math_lists'),
            'column_p' => JMHelper::JMGetValues($i_ready_math_boys,'column_ac','i_ready_math_boys'), //IREADY POINTS MATH FALL
            'column_q' => JMHelper::JMGetValues($i_ready_math_boys,'column_ae','i_ready_math_boys'), //IREADY RELATIVE PLACEMENT MATH FALL
            'column_r' => JMHelper::JMGetValues($i_ready_math_boys,'column_ad','i_ready_math_boys'), //IREADY LEVEL MATH FALL
            'column_s' => JMHelper::JMGetValues($i_ready_reading_boy_s,'column_ac','i_ready_reading_boy_s'), //IREADY POINTS READING FALL
            'column_t' => JMHelper::JMGetValues($i_ready_reading_boy_s,'column_ae','i_ready_reading_boy_s'), //IREADY RELATIVE PLACEMENT READING FALL
            'column_u' => JMHelper::JMGetValues($i_ready_reading_boy_s,'column_ad','i_ready_reading_boy_s'), //IREADY LEVEL READING FALL
            'column_v' => JMHelper::JMGetValues($i_ready_math_mid_years,'column_ac','i_ready_math_mid_years'), //IREADY POINTS MATH MID YEAR
            'column_w' => JMHelper::JMGetValues($i_ready_math_mid_years,'column_ae','i_ready_math_mid_years'), //IREADY RELATIVE PLACEMENT MATH MID YEAR
            'column_x' => JMHelper::JMGetValues($i_ready_math_mid_years,'column_ad','i_ready_math_mid_years'), //IREADY LEVEL MATH MID YEAR
            'column_y' => JMHelper::JMGetValues($i_ready_reading_mid_years,'column_ac','i_ready_reading_mid_years'), //IREADY POINTS READING MID YEAR
            'column_z' => JMHelper::JMGetValues($i_ready_reading_mid_years,'column_ae','i_ready_reading_mid_years'), //IREADY RELATIVE PLACEMENT READING MID YEAR
            'column_aa' => JMHelper::JMGetValues($i_ready_reading_mid_years,'column_ad','i_ready_reading_mid_years'), //IREADY LEVEL READING MID YEAR
            'column_ab' => JMHelper::JMGetValues($i_ready_math_eoy_s,'column_ac','i_ready_math_eoy_s'), //IREADY POINTS MATH END OF YEAR
            'column_ac' => JMHelper::JMGetValues($i_ready_math_eoy_s,'column_ae','i_ready_math_eoy_s'), //IREADY RELATIVE PLACEMENT MATH END OF YEAR
            'column_ad' => JMHelper::JMGetValues($i_ready_math_eoy_s,'column_ad','i_ready_math_eoy_s'), //IREADY LEVEL MATH END OF YEAR
            'column_ae' => JMHelper::JMGetValues($i_ready_reading_eoy_s,'column_ac','i_ready_reading_eoy_s'), //IREADY POINTS READING END OF YEAR
            'column_af' => JMHelper::JMGetValues($i_ready_reading_eoy_s,'column_ae','i_ready_reading_eoy_s'), //IREADY RELATIVE PLACEMENT READING END OF YAER
            'column_ag' => JMHelper::JMGetValues($i_ready_reading_eoy_s,'column_ad','i_ready_reading_eoy_s'), //IREADY LEVEL READING END OF YEAR
            'column_ah' => "", //IREADY GROWTH POINTS MATH MID YEAR
            'column_ai' => "", //IREADY LEVELS MATH GROWTH MID YEAR
            'column_aj' => "", //IREADY GROWTH POINTS READING MID YEAR
            'column_ak' => "", //IREADY LEVELS READING GROWTH MID YEAR
            'column_al' => "", //IREADY GROWTH POINTS MATH END OF YEAR
            'column_am' => "", //IREADY LEVELS MATH GROWTH END OF YEAR
            'column_an' => "", //IREADY GROWTH POINTS READING END OF YEAR
            'column_ao' => "", //IREADY LEVELS READING GROWTH END OF YEAR
            'column_ap' => JMHelper::JMGetValues($easy_cbm_falls,'column_ag','easy_cbm_falls'), //FLUENCY Percentile
            'column_aq' => JMHelper::JMGetValues($easy_cbm_falls,'column_am','easy_cbm_falls'), //VOCAB Percentile
            'column_ar' => JMHelper::JMGetValues($easy_cbm_falls,'column_ab','easy_cbm_falls'), //PROF Passage Reading
            'column_as' => JMHelper::JMGetValues($easy_cbm_falls,'column_w','easy_cbm_falls'), //letter name accuracy
            'column_at' => JMHelper::JMGetValues($easy_cbm_falls,'column_z','easy_cbm_falls'), //letter sound accuracy
            'column_au' => JMHelper::JMGetValues($easy_cbm_falls,'column_ap','easy_cbm_falls'), //word accuracy
            'column_av' => JMHelper::JMGetValues($easy_cbm_falls,'column_aj','easy_cbm_falls'), //phoneme accuracy
            'column_aw' => JMHelper::JMGetValues($easy_cbm_falls,'column_az','easy_cbm_falls'), //READING RISK
            'column_ax' => JMHelper::JMGetValues($easy_cbm_falls,'column_aw','easy_cbm_falls'), //PROF MATH PERCENTILE
            'column_ay' => JMHelper::JMGetValues($easy_cbm_falls,'column_ba','easy_cbm_falls'), //MATH RISK
            'column_az' => JMHelper::JMGetValues($easy_cbm_progmons,'column_s','easy_cbm_progmons'), //Progress Monitoring Test Given
            'column_ba' => JMHelper::JMGetValues($easy_cbm_progmons,'column_w','easy_cbm_progmons'), //Progress Monitoring Accuracy Percentile
            'column_bb' => "", //STAR Assessment Math Fall
            'column_bc' => "", //STAR Assessment Reading Fall
            'column_bd' => "", //STAR Assessment Math Mid Year
            'column_be' => "", //STAR Assessment Reading Mid Year
            'column_bf' => "", //STAR Assessment Math End of Year
            'column_bg' => "", //STAR Assessment Reading End of Year
            'column_bh' => "", //STAR Assessment GROWTH Math Mid Year
            'column_bi' => "", //STAR Assessment GROWTH Reading Mid Year
            'column_bj' => "", //STAR Assessment GROWTH Math End of Year
            'column_bk' => "", //STAR Assessment GROWTH Reading End of Year
            'column_bl', //Intervention class attendance
            'column_bm' => JMHelper::JMGetValues($i_ready_math_minutes,'column_h','i_ready_math_minutes'), //IREADY MINUTES MATH
            'column_bn' => JMHelper::JMGetValues($i_ready_reading_minutes,'column_h','i_ready_reading_minutes'), //IREADY MINUTES READING
            'column_bo' => JMHelper::JMGetValues($freckle_minutes,'column_h','freckle_minutes'), //FRECKLE MINUTES MATH
            'column_bp' => JMHelper::JMGetValues($freckle_minutes,'column_h','freckle_minutes'), //FRECKLE MINUTES READING
            'column_bq' => JMHelper::JMGetValues($read180_minutes,'column_h','read180_minutes'), //Read 180 Minutes
            'column_br' => "", //Vmath Minutes
            'column_bs' => JMHelper::JMGetValues($math180_minutes,'column_h','math180_minutes'), //Math 180 Minutes
            'column_bt' => (float)$student->column_z + (float)JMHelper::JMGetValues($math_lists,'column_z','math_lists'), //CLASS INFO
            'column_bu' => (float)$student->column_aa + (float)JMHelper::JMGetValues($math_lists,'column_aa','math_lists'), //Notes
            'column_bv' => JMHelper::JMGetValues($trans_math_minutes,'column_h','trans_math_minutes'), //transmath minutes
            'column_bw' => JMHelper::JMGetValues($sst_reports,'column_c','sst_reports'), //SST
            'column_bx' => JMHelper::JMGetValues($student_list,'column_l','student_list') . " - " . JMHelper::JMGetValues($math_lists,'column_l','math_lists'), //sped
            'column_by', //ELD
            'column_bz', //Options
        ];
        var_dump("column_v ",$data['column_v']);
        var_dump('column_p ',$data['column_p']);
        var_dump('column_v - column_p' ,JMHelper::JMCalculate($data,'column_v','column_p','-',$i_ready_math_mid_years,$i_ready_math_boys));
        var_dump('column_x ', $data['column_x']);
        var_dump('column_r ', $data['column_r']);
        var_dump('column_x - column_r',JMHelper::JMCalculate($data,'column_x','column_r','-',$i_ready_math_mid_years,$i_ready_math_boys));
        dd($result);
        //$consolidate = $result[1];

        exit;
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }
        if ($request->input('processMe') =="Y") {
            TeacherStudent::reprocessTeacherStudentForAllTables2();
        }
        return view("welcome-test");
        return false;

        $table = "student_lists";
        $fileToUploadInfo = FileUploads::returnLastUploadedFileInfo($table);
        dd($fileToUploadInfo);



        ///////////
        $modelInfo = (GlobalActions::getModelsExpectedFields());
        dd($modelInfo);
        dd(GlobalActions::getModelNames());
        set_time_limit(0);
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }
        Consolidate3::removeRecordsOnCurrentCycle($cycle); // remove all the records for that cycle
        TeacherStudent::reprocessTeacherStudentForAllTables();
        echo "done";
        return;
        exit;
        ////////////////////////////
        //dd(GlobalActions::getModelNames());
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }
        $id = 1353;
        $consolidated = Consolidate3::where('cycle_id',$cycle->id)
                        ->where('id',$id)
                        ->first();
        $rows = Consolidate3::where('cycle_id',$cycle->id)
                            //->where('student_id',$consolidated->student_id)
                            ->orderBy('column_b','DESC')->get();
        foreach ($rows as $row) {
            //$result = FileUploads::generateReport($row->id);
            $tmps = StudentList::where("student_id",$consolidated->student_id)->with([
                "Attendance" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "AttendanceEla" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "AttendanceMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "Consolidated" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "EasyCBMFall" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "EasyCBMProgMon" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "FreckleMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyMathBOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyMathEOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyMathMidYear" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyMathMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyReadingBOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyReadingEOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyReadingMidYear" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "IReadyReadingMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "Math180Minutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "MathList" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "Read180Minutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "StarEOYMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "StarEOYReading" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "StarFallMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "StarFallReading" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "StarMidYearMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "StarMidYearReading" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "TransMathMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
                "VMathMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            ])->get();
        }
        echo "done";
        return;

        $studentId = "4577965187";
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }

        $tmps = StudentList::where("student_id",$studentId)->with([
            "Attendance" => function ($query) use($cycle) {
                $query->where("cycle_id",$cycle->id);
            },
            "AttendanceEla" => function ($query) use($cycle) {
                $query->where("cycle_id",$cycle->id);
            },
            "AttendanceMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "Consolidated" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "EasyCBMFall" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "EasyCBMProgMon" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "FreckleMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyMathBOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyMathEOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyMathMidYear" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyMathMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyReadingBOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyReadingEOY" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyReadingMidYear" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "IReadyReadingMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "Math180Minutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "MathList" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "Read180Minutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "StarEOYMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "StarEOYReading" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "StarFallMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "StarFallReading" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "StarMidYearMath" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "StarMidYearReading" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "TransMathMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
            "VMathMinutes" => function ($query) use($cycle) {
                    $query->where("cycle_id",$cycle->id);
                },
        ])->get();
        dd($tmps);
        foreach($tmps as $tmp) {

            //dd($tmp->easyCBMFall);
        }
        echo "done";
        return;
        ///////
        $studentId = "4577965187";
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }
        $tmps = StudentList::where("student_id",$studentId)->first()->easyCBMFall($cycle)->first();
        foreach($tmps as $tmp) {
            echo "done";
            //var_dump($tmp);
        }
        return;

        /////////////////////////////
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }
        $teacherStudent = TeacherStudent::find(6);
        $tmps = ($teacherStudent->attendanceEla($cycle));
        foreach($tmps as $tmp) {
            var_dump($tmp->id);
        }
        exit;
        $elas = AttendanceEla::find(16);
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            die("No cycle");
        }
        dd($elas->teacherStudent($cycle));
        exit;
        dd(GlobalActions::getModelNames());
        exit;
        $teacherStudent = TeacherStudent::find(6);
        $elas = ($teacherStudent->attendanceEla);
        $maths = ($teacherStudent->attendanceMath);
        $tmps = ($teacherStudent->iReadyMathBOY);
        foreach($tmps as $tmp) {
            var_dump($tmp);
        }
        exit;


        TeacherStudent::clearTeacherIdFromAllTables();
        TeacherStudent::reprocessTeacherStudentForAllTables();

        exit;
        dd(\Auth::user()->getTeacherId);
        exit;
        $data = [
            'cycle_id' =>6,
            'teacher_id'=>15,
            'email' => 'jmancera@gmail.com',
            'name' => 'Javier Mancera',
            'students_list'=>'1234567890,1234567891',
            'created_by'=> '1',
        ];
        TeacherStudent::create($data);

    }
    public function sendEmail()
    {
        /**
         * Store a receiver email address to a variable.
         */


        $reveiverEmailAddress = "jmancera@gmail.com";

        Mail::to($reveiverEmailAddress)->send(new HelloEmail);


    }


}
