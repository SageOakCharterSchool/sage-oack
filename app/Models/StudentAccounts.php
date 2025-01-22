<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAccounts extends Model
{
    use HasFactory;
    protected $table = "student_accounts";

    // New set of fields from 08/10/2024
    //(Students1) First Name
    //(Students1) Last Name
    //(Enrollments1) Program
    //(Students1) SSID (State Student ID Number)
    //(Enrollments1) Grade Level
    //(Students1) School Email Address
    //Password
    //(Students1) Birth Date
    //SIS


    protected $fillable = [
        'cycle_id',
        'teacher_id',
        'email',
        'name',
        'students_list',
        'student_id',
        'password_changed',
        'column_a', // First Name
        'column_b', // Last Name
        'column_c', // Student Id
        'column_d', // Grade
        'column_e', // email
        'column_f', // Password
        'column_g', // Date of Birth
        'column_h', // Program
        'column_i', // SIS
        'column_j',
        'created_by',
    ];


    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
    }
    public static function getTableName()
    {
        return (new self())->getTable();
    }


    protected function removeRecordsOnCurrentCycle($cycle) {
        // preserves records with password changed
        $this->where('cycle_id',$cycle->id)
                ->where('password_changed',0)
                ->delete();
    }

    protected function checkIfStudentHasPasswordChangedOnCurrentCycle($cycleId,$studentID) {
        $row = $this->where('cycle_id',$cycleId)
                    ->where('password_changed',1)
                    ->where('student_id',$studentID)
                    ->first();
        if ($row) {
            return true; // has password changed
        }
        return false; // no password changed
    }

    protected function checkIfStudentListHasRecordsOnCycle($cycle) {
        $rows = $this->where('cycle_id',$cycle->id)->get();
        if ($rows->isNotEmpty()) {
            return true;
        }
        return false;

    }

    protected function getAllRecordsOnCycle($cycle) {
        $rows = $this->where('cycle_id',$cycle->id)->get();
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }

    protected function getAllRecordsByStudentIDOnCycle($cycle,$studentID) {
        if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
            $rows = $this->where('cycle_id',$cycle->id)
                        ->where('student_id',$studentID)->get();
        } else {
            $rows = $this->where('cycle_id',$cycle->id)
                        ->where('teacher_id',\Auth::user()->getTeacherId())
                        ->where('student_id',$studentID)->get();
        }
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }
    protected function getAllTeacherStudentsByStudentIDOnCycle($cycle,$studentID) {
        $rows = $this->where('cycle_id',$cycle->id)
                    ->where('teacher_id',\Auth::user()->getTeacherId())
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
    public function teacherStudent(Cycle $cycle=null) {

        $tmp = $this->belongsTo(TeacherStudent::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->first();
    }

    protected function createStudentAccount($request,$cycle) {
        $data = [
            'cycle_id' => $cycle->id, //cicly id
            'created_by' => \Auth::id(), //created_by
            'teacher_id' => $request->teacher_id,
            'student_id' => $request->student_id,
            'column_a' => $request->student_first_name, // first name
            'column_b' => $request->student_last_name, // last name
            'column_c' => $request->student_id, // student id
            'column_d' => $request->student_grade, // grade
            'column_e' => $request->student_email, // email
            'column_f' => $request->student_password, // Password
            'column_g' => $request->student_dob, // Date of Birth
            'column_h' => "Manually Created",
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
        ];
        StudentAccounts::create($data);
    }

}
