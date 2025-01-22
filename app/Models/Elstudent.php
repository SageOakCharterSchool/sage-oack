<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elstudent extends Model
{
    use HasFactory;
    protected $table = "elstudents";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'cycle_id', //cycle_id
        'teacher_id', //teacher_id
        'column_a', //
        'column_b', //
        'column_c', //ELD Program Assigned
        'column_d', //Long Term EL (LTEL)/        At Risk"
        'column_e', //Student Last Name
        'column_f', //Student First Name
        'column_g', //Grade
        'column_h', //SSID
        'column_i', //90 (incl. 2 ALT)SOCS =  75  (2 ALT)      SOCS-K = 7        SOCS-S = 8
        'column_j', //PLA        VLA        HS        TK- No ADA"
        'column_k', //Primary Language:
        'column_l', //Local ID
        'column_m', //DOB
        'column_n', //Gender
        'column_o', //Teacher               LAST NAME
        'column_p', //Teacher          FIRST NAME
        'column_q', //IEP: 13 (incl. 2 ALT) SOCS: 12 SOCS-S: 1 SOCS- K: 0
        'column_r', //504
        'column_s', //Parent Name
        'column_t', //Parent Email
        'column_u', //Date/Yr Enrolled US School
        'column_v', //AFTER    Apr 15 US             < 1 yr
        'column_w', //Add to LIP        (date)
        'column_x', //Scale Score Overall 21/22
        'column_y', //21/22  Overall        ELPAC Level
        'column_z', //2023        SA Date Tested
        'column_aa', //22/23        Overall
        'column_ab', //22/23 Oral
        'column_ac', //22/23 Written
        'column_ad', //22/23        ELPAC Level
        'column_ae', //Scale Score Overall
        'column_af', //Score Diff  Pos/Neg
        'column_ag', // Improved  ONE Level
        'column_ah', // New / Returning  Student
        'column_ai', //Enrollment Date
        'column_aj', //RFEP Review (LL)
        'column_ak', //At Risk (LL)
        'column_al', //Long Term EL (LTEL) (LL)
        'column_am', //Alert  Theresa for curriculum
        'column_an', //*1 Primary Language
        'column_ao', //2  First Language
        'column_ap', //*3 Home Language
        'column_aq', //4  Spoken by parent to student
        'column_ar', //5  Spoken by parent at home
        'column_as', //English fluency
        'column_at', //17/18
        'column_au', //18/19
        'column_av', //19/20
        'column_aw', //20/21
        'column_ax', //21/22
        'column_ay', //22/23
        'column_az', //23/24
        'column_ba', //Overall 16/17
        'column_bb', //Overall 17/18
        'column_bc', //Overall 18/19
        'column_bd', //Overall 19/20
        'column_be', //Overall 20/21
        'column_bf', //Overall 21/22
        'column_bg', //Overall 22/23
        'column_bh', //Overall 23/24
        'column_bi', //General
    ];
    public static function getTableName()
    {
        return (new self())->getTable();
    }

    protected function removeRecordsOnCurrentCycle($cycle) {
        $this->where('cycle_id',$cycle->id)->delete();
    }

    protected function getAllRecordsOnCycle($cycle) {
        $rows = $this->where('cycle_id',$cycle->id)->get();
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }

    protected function getAllRecordsByStudentIDOnCycle($cycle,$studentID) {
        $rows = $this->where('cycle_id',$cycle->id)
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

    public function teacherStudent(Cycle $cycle) {

        $tmp = $this->belongsTo(TeacherStudent::class,'teacher_id','teacher_id');
        return $tmp->where("cycle_id",$cycle->id)->first();
    }

    public function studentList(Cycle $cycle) {

        $tmp = $this->belongsTo(StudentList::class,'student_id','student_id');
        return $tmp->where("cycle_id",$cycle->id)->first();
    }
}
