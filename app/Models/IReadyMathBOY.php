<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IReadyMathBOY extends Model
{
    use HasFactory;

    protected $table = "i_ready_math_boys";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'cycle_id', //cycle_id
        'teacher_id', //teacher_id
        'column_a', //Last Name
        'column_b', //First Name
        'column_c', //Student ID
        'column_d', //Student Grade
        'column_e', //Academic Year
        'column_f', //School
        'column_g', //Enrolled
        'column_h', //District State ID
        'column_i', //Account State ID
        'column_j', //School State ID
        'column_k', //Student State ID
        'column_l', //User Name
        'column_m', //Sex
        'column_n', //Hispanic or Latino
        'column_o', //Race
        'column_p', //English Language Learner
        'column_q', //Special Education
        'column_r', //Economically Disadvantaged
        'column_s', //Migrant
        'column_t', //Class(es)
        'column_u', //Class Teacher(s)
        'column_v', //Report Group(s)
        'column_w', //Start Date
        'column_x', //Completion Date
        'column_y', //Baseline Diagnostic (Y/N)
        'column_z', //Most Recent Diagnostic YTD (Y/N)
        'column_aa', //Duration (min)
        'column_ab', //Rush Flag
        'column_ac', //Overall Scale Score
        'column_ad', //Overall Placement
        'column_ae', //Overall Relative Placement
        'column_af', //Percentile
        'column_ag', //Grouping
        'column_ah', //Quantile Measure
        'column_ai', //Quantile Range
        'column_aj', //Number and Operations Scale Score
        'column_ak', //Number and Operations Placement
        'column_al', //Number and Operations Relative Placement
        'column_am', //Algebra and Algebraic Thinking Scale Score
        'column_an', //Algebra and Algebraic Thinking Placement
        'column_ao', //Algebra and Algebraic Thinking Relative Placement
        'column_ap', //Measurement and Data Scale Score
        'column_aq', //Measurement and Data Placement
        'column_ar', //Measurement and Data Relative Placement
        'column_as', //Geometry Scale Score
        'column_at', //Geometry Placement
        'column_au', //Geometry Relative Placement
        'column_av', //Diagnostic Gain
        'column_aw', //Annual Typical Growth Measure
        'column_ax', //Annual Stretch Growth Measure
        'column_ay', //Percent Progress to Annual Typical Growth (%)
        'column_az', //Percent Progress to Annual Stretch Growth (%)
        'column_ba', //Mid On Grade Level Scale Score
        'column_bb', //504 Plan
        'column_bc', //English Language Acquisition
        'column_bd', //Foster Youth
        'column_be', //Gifted and Talented (GATE)
        'column_bf', //Homeless Youth
        'column_bg', //Student with Disabilities
        // 'column_bh', //Transitional Kindergarten

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
