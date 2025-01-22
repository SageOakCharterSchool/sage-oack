<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IReadyMathMinutes extends Model
{
    use HasFactory;

    protected $table = "i_ready_math_minutes";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student id
        'teacher_id', //student id
        'cycle_id', //student id
        'column_a', //Last Name
        'column_b', //First Name
        'column_c', //Student ID
        'column_d', //Student Grade
        'column_e', //Academic Year
        'column_f', //School
        'column_g', //Subject
        'column_h', //Enrolled
        'column_i', //User Name
        'column_j', //Sex
        'column_k', //Hispanic or Latino
        'column_l', //Race
        'column_m', //English Language Learner
        'column_n', //Special Education
        'column_o', //Economically Disadvantaged
        'column_p', //Migrant
        'column_q', //Class(es)
        'column_r', //Class Teacher(s)
        'column_s', //Report Group(s)
        'column_t', //First Lesson Completion Date
        'column_u', //Most Recent Lesson Completion Date
        'column_v', //Year-to-Date Overall Time on Task (min)
        'column_w', //Year-to-Date Overall Lessons Passed
        'column_x', //Year-to-Date Overall Lessons Completed
        'column_y', //Year-to-Date Overall % Lessons Passed
        'column_z', //Year-to-Date Number and Operations Time on Task (min)
        'column_aa', //Year-to-Date Number and Operations Lessons Passed
        'column_ab', //Year-to-Date Number and Operations Lessons Completed
        'column_ac', //Year-to-Date Number and Operations % Lessons Passed
        'column_ad', //Year-to-Date Algebra and Algebraic Thinking Time on Task (min)
        'column_ae', //Year-to-Date Algebra and Algebraic Thinking Lessons Passed
        'column_af', //Year-to-Date Algebra and Algebraic Thinking Lessons Completed
        'column_ag', //Year-to-Date Algebra and Algebraic Thinking % Lessons Passed
        'column_ah', //Year-to-Date Measurement and Data Time on Task (min)
        'column_ai', //Year-to-Date Measurement and Data Lessons Passed
        'column_aj', //Year-to-Date Measurement and Data Lessons Completed
        'column_ak', //Year-to-Date Measurement and Data % Lessons Passed
        'column_al', //Year-to-Date Geometry Time on Task (min)
        'column_am', //Year-to-Date Geometry Lessons Passed
        'column_an', //Year-to-Date Geometry Lessons Completed
        'column_ao', //Year-to-Date Geometry % Lessons Passed
        'column_ap',
        'column_aq',
        'column_ar',
        'column_as',
        'column_at',
        'column_au',
        'column_av',
        'column_aw',
        'column_ax',
        'column_ay',
        'column_az',
        'column_ba',
        'column_bb',
        'column_bc',
        'column_bd',
        'column_be',
        'column_bf',
        'column_bg',
        'column_bh',
        'column_bi',
        'column_bj',
        'column_bk',
        'column_bl',
        'column_bm',
        'column_bn',
        'column_bo',
        'column_bp',
        'column_bq',
        'column_br',
        'column_bs',
        'column_bt',
        'column_bu',
        'column_bv',
        'column_bw',
        'column_bx',
        'column_by',
        'column_bz',
        'column_ca',
        'column_cb',
        'column_cc',
        'column_cd',
        'column_ce',
        'column_cf',
        'column_cg',
        'column_ch',
        'column_ci',
        'column_cj',
        'column_ck',
        'column_cl',
        'column_cm',
        'column_cn',
        'column_co',
        'column_cp',
        'column_cq',
        'column_cr',
        'column_cs',
        'column_ct',
        'column_cu',
        'column_cv',
        'column_cw',
        'column_cx',
        'column_cy',
        'column_cz',
        'column_ca',
        'column_db',
        'column_dc',
        'column_dd',
        'column_de',
        'column_df',
        'column_dg',
        'column_dh',
        'column_di',
        'column_dj',
        'column_dk',
        'column_dl',
        'column_dm',
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
