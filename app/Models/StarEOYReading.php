<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarEOYReading extends Model
{
    use HasFactory;

    protected $table = "star_eoy_readings";
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
        'column_m', //Test 1 IRL
        'column_n', //Test 1 ZPD
        'column_o', //Test 1 Est. ORF
        'column_p', //Test 2 Test Type
        'column_q', //Test 2 Test Date
        'column_r', //Test 2 Test Duration
        'column_s', //Test 2 SS
        'column_t', //Test 2 Benchmark Category
        'column_u', //Test 2 PR
        'column_v', //Test 2 NCE
        'column_w', //Test 2 IRL
        'column_x', //Test 2 ZPD
        'column_y', //Test 2 Est. ORF
        'column_z', //Latest Change in Score
        'column_aa', //Latest Change in PR
        'column_ab', //Latest Change in NCE
        'column_ac', //Latest Change in IRL
        'column_ad', //Latest Change in Est. ORF
        'column_ae', //Student Id
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
