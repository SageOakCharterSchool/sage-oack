<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarFallReading extends Model
{
    use HasFactory;
    protected $table = "star_fall_readings";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //student_id
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
        'column_l',  //IRL
        'column_m',  //Est. ORF
        'column_n',  //ZPD
        'column_o',  //Test Duration
        'column_p',  //Test Fidelity
        'column_q',  //Standard Set Description
        'column_r',  //Domain Group 1
        'column_s',  //Domain 1
        'column_t',  //Domain Score 1
        'column_u',  //Domain Group 2
        'column_v',  //Domain 2
        'column_w',  //Domain Score 2
        'column_x',  //Domain Group 3
        'column_y',  //Domain 3
        'column_z',  //Domain Score 3
        'column_aa', //Domain Group 4
        'column_ab', //Domain 4
        'column_ac', //Domain Score 4
        'column_ad', //Domain Group 5
        'column_ae', //Student ID
        'column_af', //Domain Score 5
        'column_ag', //Domain Group 6
        'column_ah', //Domain 6
        'column_ai', //Domain Score 6
        'column_aj', //Domain Group 7
        'column_ak', //Domain 7
        'column_al', //Domain Score 7
        'column_am', //Domain Group 8
        'column_an', //Domain 8
        'column_ao', //Domain Score 8
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
