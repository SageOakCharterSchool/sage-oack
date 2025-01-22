<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consolidated extends Model
{
    use HasFactory;
    protected $table = 'consolidateds';
        protected $fillable = [
            'created_by', //created_by
            'teacher_id', //student id
            'student_id', //student id
            'cycle_id',
            'column_a', //Student ID
            'column_b', //Student Last Name
            'column_c', //Student First Name
            'column_d', //Grade
            'column_e', //SIS
            'column_f', //Qualifying Subject 1
            'column_g', //Teacher Name
            'column_h', //Qualifying Subject 2
            'column_i', //INTERVENTION PROGRAM RECOMMENDED
            'column_j', //INTERVENTION PROGRAM SELECTED
            'column_k', //IREADY POINTS MATH FALL
            'column_l', //IREADY RELATIVE PLACEMENT MATH FALL
            'column_m', //IREADY LEVEL MATH FALL
            'column_n', //IREADY POINTS READING FALL
            'column_o', //IREADY RELATIVE PLACEMENT READING FALL
            'column_p', //IREADY LEVEL READING FALL
            'column_q', //IREADY POINTS MATH MID YEAR
            'column_r', //IREADY RELATIVE PLACEMENT MATH MID YEAR
            'column_s', //IREADY LEVEL MATH MID YEAR
            'column_t', //IREADY POINTS READING MID YEAR
            'column_u', //IREADY RELATIVE PLACEMENT READING MID YEAR
            'column_v', //IREADY LEVEL READING MID YEAR
            'column_w', //IREADY POINTS MATH END OF YEAR
            'column_x', //IREADY RELATIVE PLACEMENT MATH END OF YEAR
            'column_y', //IREADY LEVEL MATH END OF YEAR
            'column_z', //IREADY POINTS READING END OF YEAR
            'column_aa', //IREADY RELATIVE PLACEMENT READING END OF YAER
            'column_ab', //IREADY LEVEL READING END OF YEAR
            'column_ac', //IREADY GROWTH POINTS MATH MID YEAR
            'column_ad', //IREADY LEVELS MATH GROWTH MID YEAR
            'column_ae', //IREADY GROWTH POINTS READING MID YEAR
            'column_af', //IREADY LEVELS READING GROWTH MID YEAR
            'column_ag', //IREADY GROWTH POINTS MATH END OF YEAR
            'column_ah', //IREADY LEVELS MATH GROWTH END OF YEAR
            'column_ai', //IREADY GROWTH POINTS READING END OF YEAR
            'column_aj', //IREADY LEVELS READING GROWTH END OF YEAR
            'column_ak', //FLUENCY Percentile
            'column_al', //VOCAB Percentile
            'column_am', //PROF Passage Reading
            'column_an', //letter name accuracy
            'column_ao', //letter sound accuracy
            'column_ap', //word accuracy
            'column_aq', //phoneme accuracy
            'column_ar', //READING RISK
            'column_as', //PROF MATH PERCENTILE
            'column_at', //MATH RISK
            'column_au', //Progress Monitoring Test Given
            'column_av', //Progress Monitoring Accuracy Percentile
            'column_aw', //STAR Assessment Math Fall
            'column_ax', //STAR Assessment Reading Fall
            'column_ay', //STAR Assessment Math Mid Year
            'column_az', //STAR Assessment Reading Mid Year
            'column_ba', //STAR Assessment Math End of Year
            'column_bb', //STAR Assessment Reading End of Year
            'column_bc', //STAR Assessment GROWTH Math Mid Year
            'column_bd', //STAR Assessment GROWTH Reading Mid Year
            'column_be', //STAR Assessment GROWTH Math End of Year
            'column_bf', //STAR Assessment GROWTH Reading End of Year
            'column_bg', //Intervention class attendance
            'column_bh', //IREADY MINUTES MATH
            'column_bi', //IREADY MINUTES READING
            'column_bj', //FRECKLE MINUTES MATH
            'column_bk', //FRECKLE MINUTES READING
            'column_bl', //Read 180 Minutes
            'column_bm', //Vmath Minutes
            'column_bn', //Math 180 Minutes
            'column_bo', //CLASS INFO
            'column_bp', //Notes
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
