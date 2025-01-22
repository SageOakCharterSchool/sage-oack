<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarMidYearMath extends Model
{
    use HasFactory;

    protected $table = "star_mid_year_maths";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //student_id
        'column_a', //Grade
        'column_b', //Student
        'column_c', //Assignment Type
        'column_d', //Growth Proficiency Category
        'column_e', //SGP (Expectation=50)
        'column_f', //Test 1 Test Type
        'column_g', //Test 1 Test Date
        'column_h', //Test 1 Test Duration
        'column_i', //Test 1 SS
        'column_j', //Test 1 Benchmark Category
        'column_k', //Test 1 PR
        'column_l', //Test 1 NCE
        'column_m', //Test 2 Test Type
        'column_n', //Test 2 Test Date
        'column_o', //Test 2 Test Duration
        'column_p', //Test 2 SS
        'column_q', //Test 2 Benchmark Category
        'column_r', //Test 2 PR
        'column_s', //Test 2 NCE
        'column_t', //Test 3 Test Type
        'column_u', //Test 3 Test Date
        'column_v', //Test 3 Test Duration
        'column_w', //Test 3 SS
        'column_x', //Test 3 Benchmark Category
        'column_y', //Test 3 PR
        'column_z', //Test 3 NCE
        'column_aa', //Latest Change in Score
        'column_ab', //Latest Change in PR
        'column_ac', //Latest Change in NCE
        'column_ad', //Student Id
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
