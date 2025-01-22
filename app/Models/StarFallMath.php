<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarFallMath extends Model
{
    use HasFactory;
    protected $table = "star_fall_maths";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //cycle_id
        'column_a',  //School
        'column_b',  //Class/Group
        'column_c',  //Student
        'column_d',  //Student ID
        'column_e',  //Grade
        'column_f',  //SS (Star Unified)
        'column_g',  //Benchmark Type
        'column_h',  //
        'column_i',  //
        'column_j',  //
        'column_k',  //PR
        'column_l',  //Test Duration
        'column_m',  //Test Fidelity
        'column_n',  //Standard Set Description
        'column_o',  //Domain Group 1
        'column_p',  //Domain 1
        'column_q',  //Domain Score 1
        'column_r',  //Domain Group 2
        'column_s',  //Domain 2
        'column_t',  //Domain Score 2
        'column_u',  //Domain Group 3
        'column_v',  //Domain 3
        'column_w',  //Domain Score 3
        'column_x',  //Domain Group 4
        'column_y',  //Domain 4
        'column_z',  //Domain Score 4
        'column_aa', //Domain Group 5
        'column_ab', //Domain 5
        'column_ac', //Domain Score 5
        'column_ad', //Domain Group 6
        'column_ae', //Student ID
        'column_af', //Domain Score 6
        'column_ag', //Domain Group 7
        'column_ah', //Domain 7
        'column_ai', //Domain Score 7
        'column_aj', //Domain Group 8
        'column_ak', //Domain 8
        'column_al', //Domain Score 8
        'column_am', //Domain Group 9
        'column_an', //Domain 9
        'column_ao', //Domain Score 9
        'column_ap', //Domain Group 10
        'column_aq', //Domain 10
        'column_ar', //Domain Score 10
        'column_as', //Domain Group 11
        'column_at', //Domain 11
        'column_au', //Domain Score 11
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
