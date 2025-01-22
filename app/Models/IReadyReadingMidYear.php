<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IReadyReadingMidYear extends Model
{
    use HasFactory;

    protected $table = "i_ready_reading_mid_years";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //student_id
        'column_a', //Last Name
        'column_b', //First Name
        'column_c', //student_id
        'column_d', //Enrolled
        'column_e', //Student Grade
        'column_f', //Academic Year
        'column_g', //School
        'column_h', //Subject
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
        'column_t', //Number of Completed Diagnostics during the time frame
        'column_u', //Annual Typical Growth Measure
        'column_v', //Annual Stretch Growth Measure
        'column_w', //Diagnostic Gain (Note: negative gains=zero)
        'column_x', //Diagnostic: Start Date (Most Recent)
        'column_y', //Diagnostic: Completion Date (Most Recent)
        'column_z', //Diagnostic: Time on Task (min) (Most Recent)
        'column_aa', //Diagnostic: Rush Flag (Most Recent)
        'column_ab', //Diagnostic: Overall Scale Score (Most Recent)
        'column_ac', //Diagnostic: Overall Placement (Most Recent)
        'column_ad', //Diagnostic: Percentile (Most Recent)
        'column_ae', //Diagnostic: Overall Relative Placement (Most Recent)
        'column_af', //Diagnostic: Tier (Most Recent)
        'column_ag', //Diagnostic: Lexile Measure (Most Recent)
        'column_ah', //Diagnostic: Lexile Range (Most Recent)
        'column_ai', //Diagnostic: Grouping (Most Recent)
        'column_aj', //Diagnostic: Start Date (1)
        'column_ak', //Diagnostic: Completion Date (1)
        'column_al', //Diagnostic: Time on Task (min) (1)
        'column_am', //Diagnostic: Rush Flag (1)
        'column_an', //Diagnostic: Overall Scale Score (1)
        'column_ao', //Diagnostic: Overall Placement (1)
        'column_ap', //Diagnostic: Percentile (1)
        'column_aq', //Diagnostic: Overall Relative Placement (1)
        'column_ar', //Diagnostic: Tier (1)
        'column_as', //Diagnostic: Start Date (2)
        'column_at', //Diagnostic: Completion Date (2)
        'column_au', //Diagnostic: Time on Task (min) (2)
        'column_av', //Diagnostic: Rush Flag (2)
        'column_aw', //Diagnostic: Overall Scale Score (2)
        'column_ax', //Diagnostic: Overall Placement (2)
        'column_ay', //Diagnostic: Percentile (2)
        'column_az', //Diagnostic: Overall Relative Placement (2)
        'column_ba', //Diagnostic: Tier (2)
        'column_bb', //Diagnostic: Start Date (3)
        'column_bc', //Diagnostic: Completion Date (3)
        'column_bd', //Diagnostic: Time on Task (min) (3)
        'column_be', //Diagnostic: Rush Flag (3)
        'column_bf', //Diagnostic: Overall Scale Score (3)
        'column_bg', //Diagnostic: Overall Placement (3)
        'column_bh', //Diagnostic: Percentile (3)
        'column_bi', //Diagnostic: Overall Relative Placement (3)
        'column_bj', //Diagnostic: Tier (3)
        'column_bk', //Diagnostic: Start Date (4)
        'column_bl', //Diagnostic: Completion Date (4)
        'column_bm', //Diagnostic: Time on Task (min) (4)
        'column_bn', //Diagnostic: Rush Flag (4)
        'column_bo', //Diagnostic: Overall Scale Score (4)
        'column_bp', //Diagnostic: Overall Placement (4)
        'column_bq', //Diagnostic: Percentile (4)
        // 'column_br', //Diagnostic: Overall Relative Placement (4)
        // 'column_bs', //Diagnostic: Tier (4)
        // 'column_bt', //Diagnostic: Start Date (5)
        // 'column_bu', //Diagnostic: Completion Date (5)
        // 'column_bv', //Diagnostic: Time on Task (min) (5)
        // 'column_bw', //Diagnostic: Rush Flag (5)
        // 'column_bx', //Diagnostic: Overall Scale Score (5)
        // 'column_by', //Diagnostic: Overall Placement (5)
        // 'column_bz', //Diagnostic: Percentile (5)
        // 'column_ca', //Diagnostic: Overall Relative Placement (5)
        // 'column_cb', //Diagnostic: Tier (5)
        // 'column_cc', //Instruction: Overall Lessons Passed
        // 'column_cd', //Instruction: Overall Lessons Not Passed
        // 'column_ce', //Instruction: Overall Lessons Completed
        // 'column_cf', //Instruction: Overall Pass Rate (%)
        // 'column_cg', //Instruction: Overall Time on Task (min)
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
