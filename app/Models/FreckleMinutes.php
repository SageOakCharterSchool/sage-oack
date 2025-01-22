<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreckleMinutes extends Model
{
    use HasFactory;
    protected $table = "freckle_minutes";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //student_id
        'column_a', //STUDENT_NAME
        'column_b', //SIS_ID
        'column_c', //TOTAL_SESSIONS
        'column_d', //TOTAL_MINUTES
        'column_e', //MATH_SESSIONS
        'column_f', //ELA_SESSIONS
        'column_g', //SOCIAL_STUDIES_SESSIONS
        'column_h', //SCIENCE_SESSIONS
        'column_i', //MINS_SPENT_IN_MATH
        'column_j', //MINS_SPENT_IN_ELA
        'column_k', //MINS_SPENT_IN_SOCIAL_STUDIES
        'column_l', //MINS_SPENT_IN_SCIENCE
        'column_m', //TEACHERS
        'column_n', //SCHOOLS
    ];

    public static function getTableName()
    {
        return (new self())->getTable();
    }


    protected function removeRecordsOnCurrentCycle($cycle) {
        $this->where('cycle_id',$cycle->id)->delete();
    }

    protected function updateStudentIDFromStudentList(Cycle $cycle) {

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
        //dd($teacherId,$studentID,$cycle);
        $this->where('cycle_id',$cycle->id)
                ->where('student_id',$studentID)
                ->whereNull('teacher_id')
                ->update([
                    'teacher_id' => $teacherId
                ]);
                //dd(\DB::getQueryLog());
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
