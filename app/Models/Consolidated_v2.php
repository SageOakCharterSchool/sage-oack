<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consolidated_v2 extends Model
{
    use HasFactory;
    protected $table = "consolidated_v2";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'cycle_id', //cycle_id
        'teacher_id', //teacher_id
        'column_a', //id
        'column_b', //teacher_id
        'column_c', //cycle_id
        'column_d', //Student ID
        'column_e', //Student Last Name
        'column_f', //Student First Name
        'column_g', //Grade
        'column_h', //SIS
        'column_i', //Program
        'column_j', //Qualifying Subject 1
        'column_k', //Teacher Name
        'column_l', //Qualifying Subject 2
        'column_m', //INTERVENTION PROGRAM RECOMMENDED
        'column_n', //INTERVENTION PROGRAM RECOMMENDED
        'column_o', //INTERVENTION PROGRAM SELECTED
        'column_p', //INTERVENTION PROGRAM SELECTED
        'column_q', //IREADY POINTS MATH FALL
        'column_r', //IREADY RELATIVE PLACEMENT MATH FALL
        'column_s', //IREADY LEVEL MATH FALL
        'column_t', //IREADY POINTS READING FALL
        'column_u', //IREADY RELATIVE PLACEMENT READING FALL
        'column_v', //IREADY LEVEL READING FALL
        'column_w', //IREADY POINTS MATH MID YEAR
        'column_x', //IREADY RELATIVE PLACEMENT MATH MID YEAR
        'column_y', //IREADY LEVEL MATH MID YEAR
        'column_z', //IREADY POINTS READING MID YEAR
        'column_aa', //IREADY RELATIVE PLACEMENT READING MID YEAR
        'column_ab', //IREADY LEVEL READING MID YEAR
        'column_ac', //IREADY POINTS MATH END OF YEAR
        'column_ad', //IREADY RELATIVE PLACEMENT MATH END OF YEAR
        'column_ae', //IREADY LEVEL MATH END OF YEAR
        'column_af', //IREADY POINTS READING END OF YEAR
        'column_ag', //IREADY RELATIVE PLACEMENT READING END OF YAER
        'column_ah', //IREADY LEVEL READING END OF YEAR
        'column_ai', //IREADY GROWTH POINTS MATH MID YEAR
        'column_aj', //IREADY LEVELS MATH GROWTH MID YEAR
        'column_ak', //IREADY GROWTH POINTS READING MID YEAR
        'column_al', //IREADY LEVELS READING GROWTH MID YEAR
        'column_am', //IREADY GROWTH POINTS MATH END OF YEAR
        'column_an', //IREADY LEVELS MATH GROWTH END OF YEAR
        'column_ao', //IREADY GROWTH POINTS READING END OF YEAR
        'column_ap', //IREADY LEVELS READING GROWTH END OF YEAR
        'column_aq', //FLUENCY Percentile
        'column_ar', //VOCAB Percentile
        'column_as', //PROF Passage Reading
        'column_at', //letter name accuracy
        'column_au', //letter sound accuracy
        'column_av', //word accuracy
        'column_aw', //phoneme accuracy
        'column_ax', //READING RISK
        'column_ay', //PROF MATH PERCENTILE
        'column_az', //MATH RISK
        'column_ba', //Progress Monitoring Test Given
        'column_bb', //Progress Monitoring Accuracy Percentile
        'column_bc', //STAR Assessment Math Fall
        'column_bd', //STAR Assessment Reading Fall
        'column_be', //STAR Assessment Math Mid Year
        'column_bf', //STAR Assessment Reading Mid Year
        'column_bg', //STAR Assessment Math End of Year
        'column_bh', //STAR Assessment Reading End of Year
        'column_bi', //STAR Assessment GROWTH Math Mid Year
        'column_bj', //STAR Assessment GROWTH Reading Mid Year
        'column_bk', //STAR Assessment GROWTH Math End of Year
        'column_bl', //STAR Assessment GROWTH Reading End of Year
        'column_bm', //Intervention class attendance
        'column_bn', //Intervention class attendance
        'column_bo', //IREADY MINUTES MATH
        'column_bp', //IREADY MINUTES READING
        'column_bq', //FRECKLE MINUTES MATH
        'column_br', //FRECKLE MINUTES READING
        'column_bs', //Read 180 Minutes
        'column_bt', //Vmath Minutes
        'column_bu', //Math 180 Minutes
        'column_bv', //CLASS INFO
        'column_bw', //CLASS INFO
        'column_bx', //Notes
        'column_by', //Notes
    ];

    public static function getTableName()
        {
            return (new self())->getTable();
        }
        protected function removeRecordsOnCurrentCycle($cycle) {
            $this->where('cycle_id',$cycle->id)->delete();
        }

        protected function checkIfStudentAlreadyExistsOnCycle($cycle,$student) {
            $rows = $this->where('cycle_id',$cycle->id)
                        ->where('student_id',$student->student_id)
                        ->get();
            if ($rows->isNotEmpty()) {
                return $rows;
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
            //dd("here",$teacherId,$studentID,$cycle);
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
