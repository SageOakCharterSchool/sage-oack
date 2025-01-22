<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EasyCBMProgMon extends Model
{
    use HasFactory;

    protected $table = "easy_cbm_progmons";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //student_id
        'column_a', //last
        'column_b', //first
        'column_c', //student_id
        'column_d', //student_dob
        'column_e', //student_easycbmid
        'column_f', //student_grade
        'column_g', //student_gender
        'column_h', //student_sped
        'column_i', //student_ethnicity
        'column_j', //student_race
        'column_k', //student_ell
        'column_l', //student_active
        'column_m', //building_name
        'column_n', //district_data_1
        'column_o', //district_data_2
        'column_p', //district_data_3
        'column_q', //district_data_4
        'column_r', //district_data_5
        'column_s', //measure_type
        'column_t', //measure_grade
        'column_u', //measure_form
        'column_v', //score
        'column_w', //accuracy
        'column_x', //date_given
        'column_y', //academic_year
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
