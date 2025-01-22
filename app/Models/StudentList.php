<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentList extends Model
{
    use HasFactory;
    protected $table = "student_lists";
    protected $fillable = [
        'student_id',
        'teacher_id',
        'created_by',
        'cycle_id', //student_id
        'column_a', //Student Last Name
        'column_b', //Student First Name
        'column_c', //SSID
        'column_d', //Grade
        'column_e', //SIS
        'column_f', //Qualifying Subject
        'column_g', //Teacher Name
        'column_h', //Diagnostic Placement
        'column_i', //Qualified for Intervention
        'column_j', //Recommended Program
        'column_k', //Student School Email
        'column_l', //SPED Y/N
        'column_m', //SAI Teacher
        'column_n', //Easycbm Fall Assessment Score
        'column_o', //Intervention selection
        'column_p', //6-8th Grade Only                   PAPER REQUEST
        'column_q', //iReady mid year Relative Placement
        'column_r', //Growth iReady
        'column_s', //Easycbm Spring Assessment Score (add as comment)
        'column_t', //iReady Post Test Relative Placement
        'column_u', //Growth iReady
        'column_v', //Easycbm Fall Assessment Point/Percent
        'column_w', //Easycbm Winter Assessment Point/Percent
        'column_x', //Easycbm Spring Assessment Point/Percent
        'column_y', //Growth Easycbm points/percent
        'column_z', //Class info link
        'column_aa', //Notes


    ];

    public static function getTableName()
    {
        return (new self())->getTable();
    }


    protected function removeRecordsOnCurrentCycle($cycle) {
        $this->where('cycle_id',$cycle->id)->delete();
    }

    protected function checkIfStudentListHasRecordsOnCycle($cycle) {
        $rows = $this->where('cycle_id',$cycle->id)->get();
        if ($rows->isNotEmpty()) {
            return true;
        }
        return false;

    }

    protected function getAllRecordsOnCycle($cycle) {
        $rows = $this->where('cycle_id',$cycle->id)->get();
        // $rows = $this->where('cycle_id',$cycle->id)
        //                 ->where('student_id','7965082585')->get();
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }

    protected function getAllRecordsByStudentIDOnCycle($cycle,$studentID) {
        if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
            $rows = $this->where('cycle_id',$cycle->id)
                        ->where('student_id',$studentID)->get();
        } else {
            $rows = $this->where('cycle_id',$cycle->id)
                        ->where('teacher_id',\Auth::user()->getTeacherId())
                        ->where('student_id',$studentID)->get();
        }
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }
    protected function getAllTeacherStudentsByStudentIDOnCycle($cycle,$studentID) {
        $rows = $this->where('cycle_id',$cycle->id)
                    ->where('teacher_id',\Auth::user()->getTeacherId())
                    ->where('student_id',$studentID)->get();
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }
    protected function UpdateTeacherIdToItsStudents($teacherId,$studentID,$cycle) {
        $this->where('cycle_id',$cycle->id)
                ->where('student_id',$studentID)
                ->whereNull('teacher_id')
                ->update([
                    'teacher_id' => $teacherId
                ]);
    }
    public function teacherStudent(Cycle $cycle=null) {

        $tmp = $this->belongsTo(TeacherStudent::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->first();
    }

    ////////////////////////////////// Student /////////////////////////////////
    public function attendance(Cycle $cycle=null) {
        $tmp = $this->hasMany(Attendance::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function attendanceEla(Cycle $cycle=null) {
        $tmp = $this->hasMany(AttendanceEla::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function attendanceMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(AttendanceMath::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function consolidated(Cycle $cycle=null) {

        $tmp = $this->hasMany(Consolidate3::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function easyCBMFall(Cycle $cycle=null) {
        $tmp = $this->hasMany(EasyCBMFall::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function easyCBMProgMon(Cycle $cycle=null) {

        $tmp = $this->hasMany(EasyCBMProgMon::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function freckleMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(FreckleMinutes::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathBOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathBOY::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathEOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathEOY::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathMidYear(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathMidYear::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathMinutes::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingBOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingBOY::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingEOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingEOY::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingMidYear(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingMidYear::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingMinutes::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function math180Minutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(Math180Minutes::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function mathList(Cycle $cycle=null) {

        $tmp = $this->hasMany(MathList::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function read180Minutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(Read180Minutes::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starEOYMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarEOYMath::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starEOYReading(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarEOYReading::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starFallMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarFallMath::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starFallReading(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarFallReading::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starMidYearMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarMidYearMath::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starMidYearReading(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarMidYearReading::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function transMathMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(TransMathMinutes::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }
    public function vMathMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(VMathMinutes::class,'student_id','student_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }




}
