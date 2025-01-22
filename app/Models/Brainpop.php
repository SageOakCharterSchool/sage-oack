<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brainpop extends Model
{
    use HasFactory;

    protected $table = "brainpops";
    protected $fillable = [
        'created_by',
        'student_id',
        'cycle_id',
        'teacher_id',
        'column_a',
        'column_b',
        'column_c',
        'column_d',
        'column_e',
        'column_f',
        'column_g',
        'column_h',
        'column_i',
        'column_j',
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
