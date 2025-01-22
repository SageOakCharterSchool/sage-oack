<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EasyCBMFall extends Model
{
    use HasFactory;

    protected $table = "easy_cbm_falls";
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'teacher_id', //teacher_id
        'cycle_id', //cycle_id
        'column_a', //last
        'column_b', //first
        'column_c', //student_id
        'column_d', //student_dob
        'column_e', //student_easycbmid
        'column_f', //student_gender
        'column_g', //student_grade
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
        'column_s', //letter_names_score
        'column_t', //letter_names_percentile
        'column_u', //letter_names_accuracy
        'column_v', //letter_sounds_score
        'column_w', //letter_sounds_percentile
        'column_x', //letter_sounds_accuracy
        'column_y', //proficient_reading_score
        'column_z', //proficient_reading_percentile
        'column_aa', //proficient_reading_accuracy
        'column_ab', //Lexile Suggestion
        'column_ac', //passage_reading_fluency_score
        'column_ad', //passage_reading_fluency_percentile
        'column_ae', //passage_reading_fluency_accuracy
        'column_af', //phoneme_segmenting_score
        'column_ag', //phoneme_segmenting_percentile
        'column_ah', //phoneme_segmenting_accuracy
        'column_ai', //vocabulary_score
        'column_aj', //vocabulary_percentile
        'column_ak', //vocabulary_accuracy
        'column_al', //word_reading_fluency_score
        'column_am', //word_reading_fluency_percentile
        'column_an', //word_reading_fluency_accuracy
        'column_ao', //proficient_math_benchmark_score
        'column_ap', //proficient_math_benchmark_percentile
        'column_aq', //proficient_math_benchmark_accuracy
        'column_ar', //proficient_math_benchmark_sp_count
        'column_as', //reading_risk
        'column_at', //math_risk
        'column_au', //date_of_assessment
        'column_av', //academic_year
        'column_aw', //season
        'column_ax', //rows_for_this_student

        'column_ay', //proficient_math_benchmark_sp_count
        'column_az', //reading_risk
        'column_ba', //math_risk
        'column_bb', //date_of_assessment
        'column_bx', //academic_year
        'column_bd', //season
        'column_be', //rows_for_this_student


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
