<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IReadyReadingBOY extends Model
{
    use HasFactory;

    protected $table = "i_ready_reading_boy_s";
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
        'column_ah', //Lexile Measure
        'column_ai', //Lexile Range
        'column_aj', //Phonological Awareness Scale Score
        'column_ak', //Phonological Awareness Placement
        'column_al', //Phonological Awareness Relative Placement
        'column_am', //Phonics Scale Score
        'column_an', //Phonics Placement
        'column_ao', //Phonics Relative Placement
        'column_ap', //High-Frequency Words Scale Score
        'column_aq', //High-Frequency Words Placement
        'column_ar', //High-Frequency Words Relative Placement
        'column_as', //Vocabulary Scale Score
        'column_at', //Vocabulary Placement
        'column_au', //Vocabulary Relative Placement
        'column_av', //Comprehension: Overall Scale Score
        'column_aw', //Comprehension: Overall Placement
        'column_ax', //Comprehension: Overall Relative Placement
        'column_ay', //Comprehension: Literature Scale Score
        'column_az', //Comprehension: Literature Placement
        'column_ba', //Comprehension: Literature Relative Placement
        'column_bb', //Comprehension: Informational Text Scale Score
        'column_bc', //Comprehension: Informational Text Placement
        'column_bd', //Comprehension: Informational Text Relative Placement
        'column_be', //Diagnostic Gain
        'column_bf', //Annual Typical Growth Measure
        'column_bg', //Annual Stretch Growth Measure
        'column_bh', //Percent Progress to Annual Typical Growth (%)
        'column_bi', //Percent Progress to Annual Stretch Growth (%)
        'column_bj', //Mid On Grade Level Scale Score
        'column_bk', //Reading Difficulty Indicator (Y/N)
        'column_bl', //504 Plan
        'column_bm', //English Language Acquisition
        'column_bn', //Foster Youth
        'column_bo', //Gifted and Talented (GATE)
        'column_bp', //Homeless Youth
        'column_bq', //Student with Disabilities
        // 'column_br', //Transitional Kindergarten
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
