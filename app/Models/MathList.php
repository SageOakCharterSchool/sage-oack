<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MathList extends Model
{
    use HasFactory;
    protected $table = "math_lists";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //cycle_id
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
