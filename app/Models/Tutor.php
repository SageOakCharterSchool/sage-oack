<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;
    protected $table = "tutor";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'cycle_id', //cycle_id
        'teacher_id', //teacher_id
        'column_a', //user id
        'column_b', //First Name
        'column_c', //Last Name
        'column_d', //Email
        'column_e', //Username
        'column_f', //Access Point
        'column_g', //Start Date
        'column_h', //Total Minutes Used
        'column_i', //Minutes Used this period
        'column_j', //Total Sessions
        'column_k', //Sessions this period
        'column_l', //Total Early Alerts
        'column_m', //Early Alerts this period
        'column_n', //Subjects
        'column_o', //Total Minutes Used
        'column_p', //Minutes Used this period
        'column_q', //Total Sessions
        'column_r', //Sessions this period
        'column_s', //Total Early Alerts
        'column_t', //Early Alerts this period
        'column_u', //SSID
        'created_at',
        'updated_at',
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
