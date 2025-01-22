<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consolidate3 extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by', //created_by
        'teacher_id', //student id
        'student_id', //student id
        'cycle_id',
        'column_a', //id
        'column_b', //teacher_id
        'column_c', //cycle_id
        'column_d', //Student ID
        'column_e', //Student Last Name
        'column_f', //Student First Name
        'column_g', //Grade
        'column_h', //SIS
        'column_i', //Qualifying Subject 1
        'column_j', //Teacher Name
        'column_k', //Qualifying Subject 2
        'column_l', //INTERVENTION PROGRAM RECOMMENDED
        'column_m', //INTERVENTION PROGRAM RECOMMENDED
        'column_n', //INTERVENTION PROGRAM SELECTED
        'column_o', //INTERVENTION PROGRAM SELECTED
        'column_p', //IREADY POINTS MATH FALL
        'column_q', //IREADY RELATIVE PLACEMENT MATH FALL
        'column_r', //IREADY LEVEL MATH FALL
        'column_s', //IREADY POINTS READING FALL
        'column_t', //IREADY RELATIVE PLACEMENT READING FALL
        'column_u', //IREADY LEVEL READING FALL
        'column_v', //IREADY POINTS MATH MID YEAR
        'column_w', //IREADY RELATIVE PLACEMENT MATH MID YEAR
        'column_x', //IREADY LEVEL MATH MID YEAR
        'column_y', //IREADY POINTS READING MID YEAR
        'column_z', //IREADY RELATIVE PLACEMENT READING MID YEAR
        'column_aa', //IREADY LEVEL READING MID YEAR
        'column_ab', //IREADY POINTS MATH END OF YEAR
        'column_ac', //IREADY RELATIVE PLACEMENT MATH END OF YEAR
        'column_ad', //IREADY LEVEL MATH END OF YEAR
        'column_ae', //IREADY POINTS READING END OF YEAR
        'column_af', //IREADY RELATIVE PLACEMENT READING END OF YAER
        'column_ag', //IREADY LEVEL READING END OF YEAR
        'column_ah', //IREADY GROWTH POINTS MATH MID YEAR
        'column_ai', //IREADY LEVELS MATH GROWTH MID YEAR
        'column_aj', //IREADY GROWTH POINTS READING MID YEAR
        'column_ak', //IREADY LEVELS READING GROWTH MID YEAR
        'column_al', //IREADY GROWTH POINTS MATH END OF YEAR
        'column_am', //IREADY LEVELS MATH GROWTH END OF YEAR
        'column_an', //IREADY GROWTH POINTS READING END OF YEAR
        'column_ao', //IREADY LEVELS READING GROWTH END OF YEAR
        'column_ap', //FLUENCY Percentile
        'column_aq', //VOCAB Percentile
        'column_ar', //PROF Passage Reading
        'column_as', //letter name accuracy
        'column_at', //letter sound accuracy
        'column_au', //word accuracy
        'column_av', //phoneme accuracy
        'column_aw', //READING RISK
        'column_ax', //PROF MATH PERCENTILE
        'column_ay', //MATH RISK
        'column_az', //Progress Monitoring Test Given
        'column_ba', //Progress Monitoring Accuracy Percentile
        'column_bb', //STAR Assessment Math Fall
        'column_bc', //STAR Assessment Reading Fall
        'column_bd', //STAR Assessment Math Mid Year
        'column_be', //STAR Assessment Reading Mid Year
        'column_bf', //STAR Assessment Math End of Year
        'column_bg', //STAR Assessment Reading End of Year
        'column_bh', //STAR Assessment GROWTH Math Mid Year
        'column_bi', //STAR Assessment GROWTH Reading Mid Year
        'column_bj', //STAR Assessment GROWTH Math End of Year
        'column_bk', //STAR Assessment GROWTH Reading End of Year
        'column_bl', //Intervention class attendance
        'column_bm', //IREADY MINUTES MATH
        'column_bn', //IREADY MINUTES READING
        'column_bo', //FRECKLE MINUTES MATH
        'column_bp', //FRECKLE MINUTES READING
        'column_bq', //Read 180 Minutes
        'column_br', //Vmath Minutes
        'column_bs', //Math 180 Minutes
        'column_bt', //CLASS INFO
        'column_bu', //Notes
        'column_bv', //transmath minutes
        'column_bw', //SST
        'column_bx', //sped
        'column_by', //ELD
        'column_bz', //Options

    ];

    protected function getHeaders() {
        $headers= [
            ['header' => 'id','field'=>'id'],
            ['header' => 'teacher_id','field'=>'teacher_id'],
            ['header' => 'cycle_id','field'=>'cycle_id'],
            ['header' => 'Student ID','field'=>'student_id'],
            ['header' => 'Student Last Name','field'=>'column_b'],
            ['header' => 'Student First Name','field'=>'column_c'],
            ['header' => 'Grade','field'=>'column_d'],
            ['header' => 'SIS','field'=>'column_e'],
            ['header' => 'Program','field'=>'program'],
            ['header' => 'Teacher Name','table' => 'teacher_students','field'=>'column_g'],
            ['header' => 'Qualifying Subject 1','field'=>'column_f'],
            ['header' => 'Qualifying Subject 2','field'=>'column_h'],
            ['header' => 'ELA Intervention Recommendation','field'=>'column_i'],
            ['header' => 'ELA Intervention Selected','field'=>'column_k'],
            ['header' => 'Math Intervention Recommendation','field'=>'column_j'],
            ['header' => 'MATH Intervention Selected','field'=>'column_l'],
            ['header' => 'CAASPP Math','field'=>'caaspp_math'],
            ['header' => 'CAASPP Reading','field'=>'caaspp_reading'],
            ['header' => 'iReady Math Points Fall','field'=>'column_m'],
            ['header' => 'iReady Relative Placement Math Fall','field'=>'column_n'],
            ['header' => 'iReady Level Math Fall','field'=>'column_o'],
            ['header' => 'iReady Reading Points Fall','field'=>'column_p'],
            ['header' => 'iReady Relative Placement Reading Fall','field'=>'column_q'],
            ['header' => 'iReady Level Reading Fall','field'=>'column_r'],
            ['header' => 'iReady Math Points Mid Year','field'=>'column_s'],
            ['header' => 'iReady Relative Placement Math Mid Year','field'=>'column_t'],
            ['header' => 'iReady Level Math Mid Year','field'=>'column_u'],
            ['header' => 'iReady Reading Points Mid Year','field'=>'column_v'],
            ['header' => 'iReady Relative Placement Reading Mid Year','field'=>'column_w'],
            ['header' => 'iReady Level Reading Mid Year','field'=>'column_x'],
            ['header' => 'iReady Math Points End of Year','field'=>'column_y'],
            ['header' => 'iReady Relative Placement Math End of Year','field'=>'column_z'],
            ['header' => 'iReady Level Math End of Year','field'=>'column_aa'],
            ['header' => 'iReady Reading Points End of Year','field'=>'column_ab'],
            ['header' => 'iReady Relative Placement Reading End of Year','field'=>'column_ac'],
            ['header' => 'iReady Level Reading End of Year','field'=>'column_ad'],
            ['header' => 'iReady Growth Points Math Mid Year','field'=>'column_ae'],
            ['header' => 'iReady Levels Math Growth Mid Year','field'=>'column_af'],
            ['header' => 'iReady Growth Points Reading Mid Year','field'=>'column_ag'],
            ['header' => 'iReady Levels Reading Growth Mid Year','field'=>'column_ah'],
            ['header' => 'iReady Growth Points Math End of Year','field'=>'column_ai'],
            ['header' => 'IReady Levels Math Growth End of Year','field'=>'column_aj'],
            ['header' => 'iReady Growth Points Reading End of Year','field'=>'column_ak'],
            ['header' => 'IReady Levels Reading Growth End of Year','field'=>'column_al'],
            ['header' => 'Reading Risk','field'=>'column_at'],
            ['header' => 'Math Risk','field'=>'column_av'],
            ['header' => 'Intervention Class Attendance','field'=>'column_bi'],
            ['header' => 'iReady Minutes Math','field'=>'column_bj'],
            ['header' => 'iReady Minutes Reading','field'=>'column_bk'],
            ['header' => 'Reading Class Minutes','field'=>'column_bn'],
            ['header' => 'Math Class Minutes','field'=>'column_bp'],
            ['header' => 'Tutor.com Sessions','field'=>'tutorcom'],
            ['header' => 'Class Info','field'=>'column_bq'],
            ['header' => 'Notes','field'=>'column_br'],
            ['header' => 'SST','field'=>'column_bt'],
            ['header' => 'sped','field'=>'column_bu'],
            ['header' => 'ELD','field'=>'column_bv'],
        ];

        $headr = [];

        foreach ($headers as $field) {
            $headr[$field['header']] = $field['field'];
        }




        return $headr;

    }

    protected function columnACADEquivalences() {
        $equivalences = [];
        $equivalences['Emerging K'] = -1;
        $equivalences['Early K'] =	0;
        $equivalences['Mid K'] = 0;
        $equivalences['Late K'] = 0;
        $equivalences['Level K'] = 0;
        $equivalences['Early 1'] = 1;
        $equivalences['Mid 1'] = 1;
        $equivalences['Late 1'] = 1;
        $equivalences['Level 1'] = 1;
        $equivalences['Early 2'] = 2;
        $equivalences['Mid 2'] = 2;
        $equivalences['Late 2'] = 2;
        $equivalences['Level 2'] = 2;
        $equivalences['Early 3'] = 3;
        $equivalences['Mid 3'] = 3;
        $equivalences['Late 3'] = 3;
        $equivalences['Level 3'] = 3;
        $equivalences['Early 4'] = 4;
        $equivalences['Mid 4'] = 4;
        $equivalences['Late 4'] = 4;
        $equivalences['Level 4'] = 4;
        $equivalences['Early 5'] = 5;
        $equivalences['Mid 5'] = 5;
        $equivalences['Late 5'] = 5;
        $equivalences['Level 5'] = 5;
        $equivalences['Early 6'] = 6;
        $equivalences['Mid 6'] = 6;
        $equivalences['Late 6'] = 6;
        $equivalences['Level 6'] = 6;
        $equivalences['Early 7'] = 7;
        $equivalences['Mid 7'] = 7;
        $equivalences['Late 7'] = 7;
        $equivalences['Level 7'] = 7;
        $equivalences['Early 8'] = 8;
        $equivalences['Mid 8'] = 8;
        $equivalences['Late 8'] = 8;
        $equivalences['Level 8'] = 8;
        $equivalences['Level 9'] = 9;
        $equivalences['Early 9'] = 9;
        $equivalences['Mid 9'] = 9;
        $equivalences['Level 10'] = 10;
        $equivalences['Early 10'] = 10;
        $equivalences['Mid 10'] = 10;
        $equivalences['Level 11'] =	11;
        $equivalences['Early 11'] = 11;
        $equivalences['Mid 11'] = 11;
        $equivalences['Early Algebra 1'] = 9;
        $equivalences['Mid Algebra 1'] = 9;
        $equivalences['Late Algebra 1'] = 9;
        $equivalences['Algebra 1'] = 9;
        $equivalences['Early Geometry'] = 10;
        $equivalences['Mid Geometry'] = 10;
        $equivalences['Late Geometry'] = 10;
        $equivalences['Geometry'] = 10;
        $equivalences['Early Algebra 2'] = 11;
        $equivalences['Mid Algebra 2'] = 11;
        $equivalences['Late Algebra 2'] = 11;
        $equivalences['Algebra 2'] = 11;
        $equivalences['Early CCR Math'] = 9;
        $equivalences['Mid CCR Math'] = 9;
        $equivalences['Late CCR Math'] = 9;
        $equivalences['CCR Math'] = 9;
        //New Equivalences
        $equivalences['Grade K'] = 0;
        $equivalences['Grade 1'] = 1;
        $equivalences['Grade 2'] = 2;
        $equivalences['Grade 3'] = 3;
        $equivalences['Grade 4'] = 4;
        $equivalences['Grade 5'] = 5;
        $equivalences['Grade 6'] = 6;
        $equivalences['Grade 7'] = 7;
        $equivalences['Grade 8'] = 8;
        $equivalences['Grade 9'] = 9;
        $equivalences['Grade 10'] = 10;
        $equivalences['Grade 11'] = 11;
        $equivalences['Grade 12'] = 12;

        return $equivalences;
    }

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

        public static function getProgramName($cycle,$studentId) {
            $student_accounts = StudentAccounts::getAllRecordsByStudentIDOnCycle($cycle,$studentId);
            $programName = "";
            if ($student_accounts) {
                $programName = str_replace("Independent Study - " , "", $student_accounts[0]->column_h);
            }
            return $programName;
        }
        public static function getCaasppMath($cycle,$studentId) {
            $caasppsMath = Caaspp::getAllRecordsByStudentIDOnCycle($cycle,$studentId,"02");
            $fieldValue = "";
            if ($caasppsMath && isset($caasppsMath[0]) && ($caasppsMath[0]->column_a == "02" || $caasppsMath[0]->column_a == "2")) {
                $fieldValue = $caasppsMath[0]->column_ev  ?? '';
            } elseif ($caasppsMath && isset($caasppsMath[1]) && ($caasppsMath[1]->column_a == "02" || $caasppsMath[1]->column_a == "2")) {
                $fieldValue = $caasppsMath[1]->column_ev  ?? '';
            }
            return $fieldValue;
        }

        public static function getCaasppReading($cycle,$studentId) {
            $caasppsReading = Caaspp::getAllRecordsByStudentIDOnCycle($cycle,$studentId,"01");
            $fieldValue = "";
            if ($caasppsReading && isset($caasppsReading[0]) && ($caasppsReading[0]->column_a == "01" || $caasppsReading[0]->column_a == "1")) {
                $fieldValue = $caasppsReading[0]->column_ev  ?? '';
            } elseif ($caasppsReading && isset($caasppsReading[1]) && ($caasppsReading[1]->column_a == "01" || $caasppsReading[1]->column_a == "1")) {
                $fieldValue = $caasppsReading[1]->column_ev  ?? '';
            }
            return $fieldValue;

        }
        public static function getTutorSessions($cycle,$studentId) {
            $tutor = Tutor::getAllRecordsByStudentIDOnCycle($cycle,$studentId);
            $fieldValue = "";
            if ($tutor) {
                $fieldValue = $tutor[0]?$tutor[0]['column_j']:'';
            }
            return $fieldValue;

        }
}
