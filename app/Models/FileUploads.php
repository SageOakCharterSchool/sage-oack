<?php

namespace App\Models;

use App\Helpers\JMHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use App\Models\Consolidate3;



class FileUploads extends Model
{
    use HasFactory;

    protected $table = "file_uploads";
    protected $fillable = [
        'created_by',
        'uploaded_on',
        'processed_on',
        'status',
        'cycle_id',
        'table_name',
        'file_name',
        'file_path',
        "cols_expected",
        "num_of_rows",
        "num_of_cols",
        "error_reported"
    ];

    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
    }

    protected function removeAnyUnusedgUpload($table) {
        $this->where("created_by",Auth::id())
                ->where("table_name",$table)
                ->where('status',0)
                ->delete();

    }

    protected function markAnyUnusedUpload($table) {
        $this->where("created_by",Auth::id())
                ->where("table_name",$table)
                ->where('status',0)
                ->update(['status'=>2]);

    }

    protected function checkIfCreateOrReplace($table) {
        $row = $this->where("created_by",Auth::id())
                    ->where("table_name",$table)
                    ->where("uploaded_on",date("Y-m-d"))
                    ->where('status',0)
                    ->first();
        return $row;
    }

    protected function checkForFileToUpload($table) {
        $row = $this->where("created_by",Auth::id())
                    ->where("table_name",'data_file_' . $table)
                    ->where('status',0)
                    ->first();
        if ($row) {
            return true;
        }
        return false;
    }
    protected function returnFileToUploadInfo($table) {
        $row = $this->where("created_by",Auth::id())
                    ->where("table_name",'data_file_' . $table)
                    ->where('status',0)
                    ->first();
        if ($row) {
            return $row;
        }
        return false;
    }
    protected function returnLastUploadedFileInfo($table) {
        $row = $this->where("created_by",Auth::id())
                    ->where("table_name",'data_file_' . $table)
                    ->where('status',1)
                    ->latest()
                    ->first();
        if ($row) {
            return $row;
        }
        return false;
    }

    protected function uploadFile() {

    }

    protected function processUpload($request) {
        die("here");
        $cycle = Cycle::getCurrentCycle();
        $table = $request->my_table_name;
        if (!$cycle) {
            $fileErrors[] = "No available cycle to run ";
            return redirect('admin/consolidate')->with('errorMessage_'. $table, $fileErrors);
        }
        $criticalError = 0;
        $fileToUploadInfo = FileUploads::returnFileToUploadInfo($table);
        //dd($fileToUploadInfo,$table);
        if ($fileToUploadInfo) {
            $tableColumnInfo = DB::select('SHOW FULL COLUMNS FROM '. $table);
            if ($table == "teacher_students") {
                $numOfColumns = count($tableColumnInfo) - 9; // remove ids
            } else if ($table == "student_accounts") {
                $numOfColumns = count($tableColumnInfo) - 8; // remove ids
            } else if ($table == "easy_cbm_falls") {
                $numOfColumns = count($tableColumnInfo) - 13; // remove ids
                //dd($fileToUploadInfo,$table,$numOfColumns,count($tableColumnInfo));
            } else {
                $numOfColumns = count($tableColumnInfo) - 7; // remove ids
            }
            //dd($numOfColumns);
            $row = 1;
            $path = storage_path('app/');
            $fileName = $path . $fileToUploadInfo->file_path . "/". $fileToUploadInfo->file_name;
            $fileErrors = [];
            $hasError = 0;
            $colsToCheck = 0;
            $rowsToLoad = [];
            $fileToUploadInfo->cols_expected  = $numOfColumns;
            if (file_exists($fileName)) {
                if (($handle = fopen($fileName, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                        $data = array_map(function($e) { return rtrim($e, "\n\r"); }, $data);
                        foreach ($data as $k => $field) {
                            $field = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $field);
                            $field = preg_replace('/\s+/S', " ", $field);
                            $field = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $field);
                            $field = trim(preg_replace("/(\s*[\r\n]+\s*|\s+)/", ' ', $field));
                            $data[$k] = $field;
                        }
                        if ($row >= 1) {
                            foreach ($data as $tmp) {
                                if (!empty($tmp)) {
                                    $colsToCheck++;
                                }
                            }
                        }
                        //dd($data);
                        if ($numOfColumns > count($data)) {
                            $tmpMsg = 'Row ' . $row . ' has ' . count($data) . ' columns and we are expecting ' .  $numOfColumns;
                            $fileErrors[] = $tmpMsg;
                            if ($hasError==0) {
                                $fileToUploadInfo->num_of_cols  = count($data);
                                $fileToUploadInfo->error_reported  = $tmpMsg;
                            }
                            $hasError = 1;
                        }
                        if ($numOfColumns > count($data)) {
                            $fileErrors[] = 'Row ' . $row . ' has ' . count($data) . ' columns and we are expecting ' .  $numOfColumns;
                            //$hasError = 1;
                        }

                        if ($row > 1) {
                            $rowsToLoad[] = $data; // to prepare to load into tables after validation
                            if ($hasError==0) {
                                $fileToUploadInfo->num_of_cols  = count($data);
                            }
                        }
                        $row++;
                    }
                }
                $fileToUploadInfo->num_of_rows  = $row;

                if ($hasError == 0) {
                    $loadMethod = 'loadRecords_on_'.$table;
                    $inserts = $this->$loadMethod($rowsToLoad,$cycle);
                    $tmpMsg = "Table ". $table . " successfully " . $inserts . " records inserted ";
                    $fileErrors[] = $tmpMsg;
                    $fileToUploadInfo->error_reported  = $tmpMsg;
                    $this->removeFileAndUpdateProcess($fileToUploadInfo,$fileName);
                } else {
                    $fileToUploadInfo->error_reported  = $tmpMsg;
                }
                $fileToUploadInfo->save();
            } else {
                $tmpMsg  = "File seems was deleted or dont Exists";
                $fileToUploadInfo->error_reported  = $tmpMsg;
                $fileErrors[] = $tmpMsg;
                $hasError == 1;
                $this->removeFileAndUpdateProcess($fileToUploadInfo,$fileName);
            }
            return [
                'hasError' => $hasError,
                'fileErrors' => $fileErrors,
                'table' => $table
            ];
        } else {
            return [
                'hasError' => "",
                'fileErrors' => "",
                'table' => $table
            ];
        }
    }

    protected function csvToArray($file, $delimiter) {
        if (($handle = fopen($file, 'r')) !== FALSE) {

            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
                if (count($lineArray) == 1 && is_null($lineArray[0])) {
                    continue;
                }
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = trim($lineArray[$j]);
                }
                $i++;
            }
            fclose($handle);
        }
        return $arr;
    }



    protected function loadRecords_on_easy_cbm_falls($rows,Cycle $cycle) {
        EasyCBMFall::removeRecordsOnCurrentCycle($cycle);

        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;

            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a'  =>  $row[0], //last
                'column_b'  =>  $row[1], //first
                'column_c'  =>  $row[2], //student_id
                'column_d'  =>  $row[3], //student_dob
                'column_e'  =>  $row[4], //student_easycbmid
                'column_f'  =>  $row[5], //student_gender
                'column_g'  =>  $row[6], //student_grade
                'column_h'  =>  $row[7], //student_sped
                'column_i'  =>  $row[8], //student_ethnicity
                'column_j'  =>  $row[9], //student_race
                'column_k'  =>  $row[10], //student_ell
                'column_l'  =>  $row[11], //student_active
                'column_m'  =>  $row[12], //building_name
                'column_n'  =>  $row[13], //district_data_1
                'column_o'  =>  $row[14], //district_data_2
                'column_p'  =>  $row[15], //district_data_3
                'column_q'  =>  $row[16], //district_data_4
                'column_r'  =>  $row[17], //district_data_5
                'column_s'  =>  $row[18], //letter_names_score
                'column_t'  =>  $row[19], //letter_names_percentile
                'column_u'  =>  $row[20], //letter_names_accuracy
                'column_v'  =>  $row[21], //letter_sounds_score
                'column_w'  =>  $row[22], //letter_sounds_percentile
                'column_x'  =>  $row[23], //letter_sounds_accuracy
                'column_y'  =>  $row[28], //proficient_reading_score
                'column_z'  =>  $row[29], //proficient_reading_percentile
                'column_aa'  => $row[30], //proficient_reading_accuracy
                'column_ab'  => $row[31], //Lexile Suggestion
                'column_ac'  => $row[32], //passage_reading_fluency_score
                'column_ad'  => $row[33], //passage_reading_fluency_percentile
                'column_ae'  => $row[34], //passage_reading_fluency_accuracy
                'column_af'  => $row[35], //phoneme_segmenting_score
                'column_ag'  => $row[36], //phoneme_segmenting_percentile
                'column_ah'  => $row[37], //phoneme_segmenting_accuracy
                'column_ai'  => $row[38], //vocabulary_score
                'column_aj'  => $row[39], //vocabulary_percentile
                'column_ak'  => '', //vocabulary_accuracy
                'column_al'  => '', //word_reading_fluency_score
                'column_am'  => '', //word_reading_fluency_percentile
                'column_an'  => '', //word_reading_fluency_accuracy
                'column_ao'  => $row[40], //proficient_math_benchmark_score
                'column_ap'  => $row[41], //proficient_math_benchmark_percentile
                'column_aq'  => $row[42], //proficient_math_benchmark_accuracy
                'column_ar'  => $row[43], //proficient_math_benchmark_sp_count
                'column_as'  => $row[44], //reading_risk
                'column_at'  => $row[45], //math_risk
                'column_au'  => $row[46], //date_of_assessment
                'column_av'  => $row[47], //academic_year
                'column_aw'  => $row[48], //season
                'column_ax'  => $row[49], //rows_for_this_student
                'column_ay'  => '',
                'column_az'  => '',
                'column_ba'  => '',
                'column_bb'  => '',
                'column_bx'  => '',
                'column_bd'  => '',
                'column_be'  => '',
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    EasyCBMFall::insert($t);
                };
                $bulkData = [];
            }
            //EasyCBMFall::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            EasyCBMFall::insert($t);
        };
        $table = EasyCBMFall::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_easy_cbm_progmons($rows,Cycle $cycle) {
        EasyCBMProgMon::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //last
                'column_b' => $row[$i++], //first
                'column_c' => $row[$i++], //student_id
                'column_d' => $row[$i++], //student_dob
                'column_e' => $row[$i++], //student_easycbmid
                'column_f' => $row[$i++], //student_grade
                'column_g' => $row[$i++], //student_gender
                'column_h' => $row[$i++], //student_sped
                'column_i' => $row[$i++], //student_ethnicity
                'column_j' => $row[$i++], //student_race
                'column_k' => $row[$i++], //student_ell
                'column_l' => $row[$i++], //student_active
                'column_m' => $row[$i++], //building_name
                'column_n' => $row[$i++], //district_data_1
                'column_o' => $row[$i++], //district_data_2
                'column_p' => $row[$i++], //district_data_3
                'column_q' => $row[$i++], //district_data_4
                'column_r' => $row[$i++], //district_data_5
                'column_s' => $row[$i++], //measure_type
                'column_t' => $row[$i++], //measure_grade
                'column_u' => $row[$i++], //measure_form
                'column_v' => $row[$i++], //score
                'column_w' => $row[$i++], //accuracy
                'column_x' => $row[$i++], //date_given
                'column_y' => $row[$i++], //academic_year
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    EasyCBMProgMon::insert($t);
                };
                $bulkData = [];
            }
            //EasyCBMProgMon::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            EasyCBMProgMon::insert($t);
        };
        $table = EasyCBMProgMon::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_freckle_minutes($rows,Cycle $cycle) {
        FreckleMinutes::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[1], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //STUDENT_NAME
                'column_b' => $row[$i++], //SIS_ID
                'column_c' => $row[$i++], //TOTAL_SESSIONS
                'column_d' => $row[$i++], //TOTAL_MINUTES
                'column_e' => $row[$i++], //MATH_SESSIONS
                'column_f' => $row[$i++], //ELA_SESSIONS
                'column_g' => $row[$i++], //SOCIAL_STUDIES_SESSIONS
                'column_h' => $row[$i++], //SCIENCE_SESSIONS
                'column_i' => $row[$i++], //MINS_SPENT_IN_MATH
                'column_j' => $row[$i++], //MINS_SPENT_IN_ELA
                'column_k' => $row[$i++], //MINS_SPENT_IN_SOCIAL_STUDIES
                'column_l' => $row[$i++], //MINS_SPENT_IN_SCIENCE
                'column_m' => $row[$i++], //TEACHERS
                'column_n' => $row[$i++], //SCHOOLS
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    FreckleMinutes::insert($t);
                };
                $bulkData = [];
            }
            //FreckleMinutes::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            FreckleMinutes::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = FreckleMinutes::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_i_ready_math_boys($rows,Cycle $cycle) {
        IReadyMathBOY::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //Student ID
                'column_d' => $row[$i++], //Student Grade
                'column_e' => $row[$i++], //Academic Year
                'column_f' => $row[$i++], //School
                'column_g' => $row[$i++], //Enrolled
                'column_h' => $row[$i++], //District State ID
                'column_i' => $row[$i++], //Account State ID
                'column_j' => $row[$i++], //School State ID
                'column_k' => $row[$i++], //Student State ID
                'column_l' => $row[$i++], //User Name
                'column_m' => $row[$i++], //Sex
                'column_n' => $row[$i++], //Hispanic or Latino
                'column_o' => $row[$i++], //Race
                'column_p' => $row[$i++], //English Language Learner
                'column_q' => $row[$i++], //Special Education
                'column_r' => $row[$i++], //Economically Disadvantaged
                'column_s' => $row[$i++], //Migrant
                'column_t' => $row[$i++], //Class(es)
                'column_u' => $row[$i++], //Class Teacher(s)
                'column_v' => $row[$i++], //Report Group(s)
                'column_w' => $row[$i++], //Start Date
                'column_x' => $row[$i++], //Completion Date
                'column_y' => $row[$i++], //Baseline Diagnostic (Y/N)
                'column_z' => $row[$i++], //Most Recent Diagnostic YTD (Y/N)
                'column_aa' => $row[$i++], //Duration (min)
                'column_ab' => $row[$i++], //Rush Flag
                'column_ac' => $row[$i++], //Overall Scale Score
                'column_ad' => $row[$i++], //Overall Placement
                'column_ae' => $row[$i++], //Overall Relative Placement
                'column_af' => $row[$i++], //Percentile
                'column_ag' => $row[$i++], //Grouping
                'column_ah' => $row[$i++], //Quantile Measure
                'column_ai' => $row[$i++], //Quantile Range
                'column_aj' => $row[$i++], //Number and Operations Scale Score
                'column_ak' => $row[$i++], //Number and Operations Placement
                'column_al' => $row[$i++], //Number and Operations Relative Placement
                'column_am' => $row[$i++], //Algebra and Algebraic Thinking Scale Score
                'column_an' => $row[$i++], //Algebra and Algebraic Thinking Placement
                'column_ao' => $row[$i++], //Algebra and Algebraic Thinking Relative Placement
                'column_ap' => $row[$i++], //Measurement and Data Scale Score
                'column_aq' => $row[$i++], //Measurement and Data Placement
                'column_ar' => $row[$i++], //Measurement and Data Relative Placement
                'column_as' => $row[$i++], //Geometry Scale Score
                'column_at' => $row[$i++], //Geometry Placement
                'column_au' => $row[$i++], //Geometry Relative Placement
                'column_av' => $row[$i++], //Diagnostic Gain
                'column_aw' => $row[$i++], //Annual Typical Growth Measure
                'column_ax' => $row[$i++], //Annual Stretch Growth Measure
                'column_ay' => $row[$i++], //Percent Progress to Annual Typical Growth (%)
                'column_az' => $row[$i++], //Percent Progress to Annual Stretch Growth (%)
                'column_ba' => $row[$i++], //Mid On Grade Level Scale Score
                'column_bb' => $row[$i++], //504 Plan
                'column_bc' => $row[$i++], //English Language Acquisition
                'column_bd' => $row[$i++], //Foster Youth
                'column_be' => $row[$i++], //Gifted and Talented (GATE)
                'column_bf' => $row[$i++], //Homeless Youth
                'column_bg' => $row[$i++], //Student with Disabilities
                // 'column_bh' => $row[$i++], //Transitional Kindergarten
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()

            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyMathBOY::insert($t);
                };
                $bulkData = [];
            }
            //IReadyMathBOY::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyMathBOY::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyMathBOY::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_i_ready_math_eoy_s($rows,Cycle $cycle) {
        IReadyMathEOY::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //student_id
                'column_d' => $row[$i++], //Enrolled
                'column_e' => $row[$i++], //Student Grade
                'column_f' => $row[$i++], //Academic Year
                'column_g' => $row[$i++], //School
                'column_h' => $row[$i++], //Subject
                'column_i' => $row[$i++], //User Name
                'column_j' => $row[$i++], //Sex
                'column_k' => $row[$i++], //Hispanic or Latino
                'column_l' => $row[$i++], //Race
                'column_m' => $row[$i++], //English Language Learner
                'column_n' => $row[$i++], //Special Education
                'column_o' => $row[$i++], //Economically Disadvantaged
                'column_p' => $row[$i++], //Migrant
                'column_q' => $row[$i++], //Class(es)
                'column_r' => $row[$i++], //Class Teacher(s)
                'column_s' => $row[$i++], //Report Group(s)
                'column_t' => $row[$i++], //Number of Completed Diagnostics during the time frame
                'column_u' => $row[$i++], //Annual Typical Growth Measure
                'column_v' => $row[$i++], //Annual Stretch Growth Measure
                'column_w' => $row[$i++], //Diagnostic Gain (Note: negative gains=zero)
                'column_x' => $row[$i++], //Diagnostic: Start Date (Most Recent)
                'column_y' => $row[$i++], //Diagnostic: Completion Date (Most Recent)
                'column_z' => $row[$i++], //Diagnostic: Time on Task (min) (Most Recent)
                'column_aa' => $row[$i++], //Diagnostic: Rush Flag (Most Recent)
                'column_ab' => $row[$i++], //Diagnostic: Overall Scale Score (Most Recent)
                'column_ac' => $row[$i++], //Diagnostic: Overall Placement (Most Recent)
                'column_ad' => $row[$i++], //Diagnostic: Percentile (Most Recent)
                'column_ae' => $row[$i++], //Diagnostic: Overall Relative Placement (Most Recent)
                'column_af' => $row[$i++], //Diagnostic: Tier (Most Recent)
                'column_ag' => $row[$i++], //Diagnostic: Quantile Measure (Most Recent)
                'column_ah' => $row[$i++], //Diagnostic: Quantile Range (Most Recent)
                'column_ai' => $row[$i++], //Diagnostic: Grouping (Most Recent)
                'column_aj' => $row[$i++], //Diagnostic: Start Date (1)
                'column_ak' => $row[$i++], //Diagnostic: Completion Date (1)
                'column_al' => $row[$i++], //Diagnostic: Time on Task (min) (1)
                'column_am' => $row[$i++], //Diagnostic: Rush Flag (1)
                'column_an' => $row[$i++], //Diagnostic: Overall Scale Score (1)
                'column_ao' => $row[$i++], //Diagnostic: Overall Placement (1)
                'column_ap' => $row[$i++], //Diagnostic: Percentile (1)
                'column_aq' => $row[$i++], //Diagnostic: Overall Relative Placement (1)
                'column_ar' => $row[$i++], //Diagnostic: Tier (1)
                'column_as' => $row[$i++], //Diagnostic: Start Date (2)
                'column_at' => $row[$i++], //Diagnostic: Completion Date (2)
                'column_au' => $row[$i++], //Diagnostic: Time on Task (min) (2)
                'column_av' => $row[$i++], //Diagnostic: Rush Flag (2)
                'column_aw' => $row[$i++], //Diagnostic: Overall Scale Score (2)
                'column_ax' => $row[$i++], //Diagnostic: Overall Placement (2)
                'column_ay' => $row[$i++], //Diagnostic: Percentile (2)
                'column_az' => $row[$i++], //Diagnostic: Overall Relative Placement (2)
                'column_ba' => $row[$i++], //Diagnostic: Tier (2)
                'column_bb' => $row[$i++], //Diagnostic: Start Date (3)
                'column_bc' => $row[$i++], //Diagnostic: Completion Date (3)
                'column_bd' => $row[$i++], //Diagnostic: Time on Task (min) (3)
                'column_be' => $row[$i++], //Diagnostic: Rush Flag (3)
                'column_bf' => $row[$i++], //Diagnostic: Overall Scale Score (3)
                'column_bg' => $row[$i++], //Diagnostic: Overall Placement (3)
                // 'column_bh' => $row[$i++], //Diagnostic: Percentile (3)
                // 'column_bi' => $row[$i++], //Diagnostic: Overall Relative Placement (3)
                // 'column_bj' => $row[$i++], //Diagnostic: Tier (3)
                // 'column_bk' => $row[$i++], //Diagnostic: Start Date (4)
                // 'column_bl' => $row[$i++], //Diagnostic: Completion Date (4)
                // 'column_bm' => $row[$i++], //Diagnostic: Time on Task (min) (4)
                // 'column_bn' => $row[$i++], //Diagnostic: Rush Flag (4)
                // 'column_bo' => $row[$i++], //Diagnostic: Overall Scale Score (4)
                // 'column_bp' => $row[$i++], //Diagnostic: Overall Placement (4)
                // 'column_bq' => $row[$i++], //Diagnostic: Percentile (4)
                // 'column_br' => $row[$i++], //Diagnostic: Overall Relative Placement (4)
                // 'column_bs' => $row[$i++], //Diagnostic: Tier (4)
                // 'column_bt' => $row[$i++], //Diagnostic: Start Date (5)
                // 'column_bu' => $row[$i++], //Diagnostic: Completion Date (5)
                // 'column_bv' => $row[$i++], //Diagnostic: Time on Task (min) (5)
                // 'column_bw' => $row[$i++], //Diagnostic: Rush Flag (5)
                // 'column_bx' => $row[$i++], //Diagnostic: Overall Scale Score (5)
                // 'column_by' => $row[$i++], //Diagnostic: Overall Placement (5)
                // 'column_bz' => $row[$i++], //Diagnostic: Percentile (5)
                // 'column_ca' => $row[$i++], //Diagnostic: Overall Relative Placement (5)
                // 'column_cb' => $row[$i++], //Diagnostic: Tier (5)
                // 'column_cc' => $row[$i++], //Instruction: Overall Lessons Passed
                // 'column_cd' => $row[$i++], //Instruction: Overall Lessons Not Passed
                // 'column_ce' => $row[$i++], //Instruction: Overall Lessons Completed
                // 'column_cf' => $row[$i++], //Instruction: Overall Pass Rate (%)
                // 'column_cg' => $row[$i++], //Instruction: Overall Time on Task (min)
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyMathEOY::insert($t);
                };
                $bulkData = [];
            }
            //IReadyMathEOY::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyMathEOY::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyMathEOY::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_i_ready_math_mid_years($rows,Cycle $cycle) {
        IReadyMathMidYear::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //student_id
                'column_d' => $row[$i++], //Enrolled
                'column_e' => $row[$i++], //Student Grade
                'column_f' => $row[$i++], //Academic Year
                'column_g' => $row[$i++], //School
                'column_h' => $row[$i++], //Subject
                'column_i' => $row[$i++], //User Name
                'column_j' => $row[$i++], //Sex
                'column_k' => $row[$i++], //Hispanic or Latino
                'column_l' => $row[$i++], //Race
                'column_m' => $row[$i++], //English Language Learner
                'column_n' => $row[$i++], //Special Education
                'column_o' => $row[$i++], //Economically Disadvantaged
                'column_p' => $row[$i++], //Migrant
                'column_q' => $row[$i++], //Class(es)
                'column_r' => $row[$i++], //Class Teacher(s)
                'column_s' => $row[$i++], //Report Group(s)
                'column_t' => $row[$i++], //Number of Completed Diagnostics during the time frame
                'column_u' => $row[$i++], //Annual Typical Growth Measure
                'column_v' => $row[$i++], //Annual Stretch Growth Measure
                'column_w' => $row[$i++], //Diagnostic Gain (Note: negative gains=zero)
                'column_x' => $row[$i++], //Diagnostic: Start Date (Most Recent)
                'column_y' => $row[$i++], //Diagnostic: Completion Date (Most Recent)
                'column_z' => $row[$i++], //Diagnostic: Time on Task (min) (Most Recent)
                'column_aa' => $row[$i++], //Diagnostic: Rush Flag (Most Recent)
                'column_ab' => $row[$i++], //Diagnostic: Overall Scale Score (Most Recent)
                'column_ac' => $row[$i++], //Diagnostic: Overall Placement (Most Recent)
                'column_ad' => $row[$i++], //Diagnostic: Percentile (Most Recent)
                'column_ae' => $row[$i++], //Diagnostic: Overall Relative Placement (Most Recent)
                'column_af' => $row[$i++], //Diagnostic: Tier (Most Recent)
                'column_ag' => $row[$i++], //Diagnostic: Quantile Measure (Most Recent)
                'column_ah' => $row[$i++], //Diagnostic: Quantile Range (Most Recent)
                'column_ai' => $row[$i++], //Diagnostic: Grouping (Most Recent)
                'column_aj' => $row[$i++], //Diagnostic: Start Date (1)
                'column_ak' => $row[$i++], //Diagnostic: Completion Date (1)
                'column_al' => $row[$i++], //Diagnostic: Time on Task (min) (1)
                'column_am' => $row[$i++], //Diagnostic: Rush Flag (1)
                'column_an' => $row[$i++], //Diagnostic: Overall Scale Score (1)
                'column_ao' => $row[$i++], //Diagnostic: Overall Placement (1)
                'column_ap' => $row[$i++], //Diagnostic: Percentile (1)
                'column_aq' => $row[$i++], //Diagnostic: Overall Relative Placement (1)
                'column_ar' => $row[$i++], //Diagnostic: Tier (1)
                'column_as' => $row[$i++], //Diagnostic: Start Date (2)
                'column_at' => $row[$i++], //Diagnostic: Completion Date (2)
                'column_au' => $row[$i++], //Diagnostic: Time on Task (min) (2)
                'column_av' => $row[$i++], //Diagnostic: Rush Flag (2)
                'column_aw' => $row[$i++], //Diagnostic: Overall Scale Score (2)
                'column_ax' => $row[$i++], //Diagnostic: Overall Placement (2)
                'column_ay' => $row[$i++], //Diagnostic: Percentile (2)
                'column_az' => $row[$i++], //Diagnostic: Overall Relative Placement (2)
                'column_ba' => $row[$i++], //Diagnostic: Tier (2)
                'column_bb' => $row[$i++], //Diagnostic: Start Date (3)
                'column_bc' => $row[$i++], //Diagnostic: Completion Date (3)
                'column_bd' => $row[$i++], //Diagnostic: Time on Task (min) (3)
                'column_be' => $row[$i++], //Diagnostic: Rush Flag (3)
                'column_bf' => $row[$i++], //Diagnostic: Overall Scale Score (3)
                'column_bg' => $row[$i++], //Diagnostic: Overall Placement (3)
                // 'column_bh' => $row[$i++], //Diagnostic: Percentile (3)
                // 'column_bi' => $row[$i++], //Diagnostic: Overall Relative Placement (3)
                // 'column_bj' => $row[$i++], //Diagnostic: Tier (3)
                // 'column_bk' => $row[$i++], //Diagnostic: Start Date (4)
                // 'column_bl' => $row[$i++], //Diagnostic: Completion Date (4)
                // 'column_bm' => $row[$i++], //Diagnostic: Time on Task (min) (4)
                // 'column_bn' => $row[$i++], //Diagnostic: Rush Flag (4)
                // 'column_bo' => $row[$i++], //Diagnostic: Overall Scale Score (4)
                // 'column_bp' => $row[$i++], //Diagnostic: Overall Placement (4)
                // 'column_bq' => $row[$i++], //Diagnostic: Percentile (4)
                // 'column_br' => $row[$i++], //Diagnostic: Overall Relative Placement (4)
                // 'column_bs' => $row[$i++], //Diagnostic: Tier (4)
                // 'column_bt' => $row[$i++], //Diagnostic: Start Date (5)
                // 'column_bu' => $row[$i++], //Diagnostic: Completion Date (5)
                // 'column_bv' => $row[$i++], //Diagnostic: Time on Task (min) (5)
                // 'column_bw' => $row[$i++], //Diagnostic: Rush Flag (5)
                // 'column_bx' => $row[$i++], //Diagnostic: Overall Scale Score (5)
                // 'column_by' => $row[$i++], //Diagnostic: Overall Placement (5)
                // 'column_bz' => $row[$i++], //Diagnostic: Percentile (5)
                // 'column_ca' => $row[$i++], //Diagnostic: Overall Relative Placement (5)
                // 'column_cb' => $row[$i++], //Diagnostic: Tier (5)
                // 'column_cc' => $row[$i++], //Instruction: Overall Lessons Passed
                // 'column_cd' => $row[$i++], //Instruction: Overall Lessons Not Passed
                // 'column_ce' => $row[$i++], //Instruction: Overall Lessons Completed
                // 'column_cf' => $row[$i++], //Instruction: Overall Pass Rate (%)
                // 'column_cg' => $row[$i++], //Instruction: Overall Time on Task (min)
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyMathMidYear::insert($t);
                };
                $bulkData = [];
            }
            //IReadyMathMidYear::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyMathMidYear::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyMathMidYear::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_i_ready_reading_boy_s($rows,Cycle $cycle) {
        IReadyReadingBOY::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //Student ID
                'column_d' => $row[$i++], //Student Grade
                'column_e' => $row[$i++], //Academic Year
                'column_f' => $row[$i++], //School
                'column_g' => $row[$i++], //Enrolled
                'column_h' => $row[$i++], //District State ID
                'column_i' => $row[$i++], //Account State ID
                'column_j' => $row[$i++], //School State ID
                'column_k' => $row[$i++], //Student State ID
                'column_l' => $row[$i++], //User Name
                'column_m' => $row[$i++], //Sex
                'column_n' => $row[$i++], //Hispanic or Latino
                'column_o' => $row[$i++], //Race
                'column_p' => $row[$i++], //English Language Learner
                'column_q' => $row[$i++], //Special Education
                'column_r' => $row[$i++], //Economically Disadvantaged
                'column_s' => $row[$i++], //Migrant
                'column_t' => $row[$i++], //Class(es)
                'column_u' => $row[$i++], //Class Teacher(s)
                'column_v' => $row[$i++], //Report Group(s)
                'column_w' => $row[$i++], //Start Date
                'column_x' => $row[$i++], //Completion Date
                'column_y' => $row[$i++], //Baseline Diagnostic (Y/N)
                'column_z' => $row[$i++], //Most Recent Diagnostic YTD (Y/N)
                'column_aa' => $row[$i++], //Duration (min)
                'column_ab' => $row[$i++], //Rush Flag
                'column_ac' => $row[$i++], //Overall Scale Score
                'column_ad' => $row[$i++], //Overall Placement
                'column_ae' => $row[$i++], //Overall Relative Placement
                'column_af' => $row[$i++], //Percentile
                'column_ag' => $row[$i++], //Grouping
                'column_ah' => $row[$i++], //Lexile Measure
                'column_ai' => $row[$i++], //Lexile Range
                'column_aj' => $row[$i++], //Phonological Awareness Scale Score
                'column_ak' => $row[$i++], //Phonological Awareness Placement
                'column_al' => $row[$i++], //Phonological Awareness Relative Placement
                'column_am' => $row[$i++], //Phonics Scale Score
                'column_an' => $row[$i++], //Phonics Placement
                'column_ao' => $row[$i++], //Phonics Relative Placement
                'column_ap' => $row[$i++], //High-Frequency Words Scale Score
                'column_aq' => $row[$i++], //High-Frequency Words Placement
                'column_ar' => $row[$i++], //High-Frequency Words Relative Placement
                'column_as' => $row[$i++], //Vocabulary Scale Score
                'column_at' => $row[$i++], //Vocabulary Placement
                'column_au' => $row[$i++], //Vocabulary Relative Placement
                'column_av' => $row[$i++], //Comprehension: Overall Scale Score
                'column_aw' => $row[$i++], //Comprehension: Overall Placement
                'column_ax' => $row[$i++], //Comprehension: Overall Relative Placement
                'column_ay' => $row[$i++], //Comprehension: Literature Scale Score
                'column_az' => $row[$i++], //Comprehension: Literature Placement
                'column_ba' => $row[$i++], //Comprehension: Literature Relative Placement
                'column_bb' => $row[$i++], //Comprehension: Informational Text Scale Score
                'column_bc' => $row[$i++], //Comprehension: Informational Text Placement
                'column_bd' => $row[$i++], //Comprehension: Informational Text Relative Placement
                'column_be' => $row[$i++], //Diagnostic Gain
                'column_bf' => $row[$i++], //Annual Typical Growth Measure
                'column_bg' => $row[$i++], //Annual Stretch Growth Measure
                'column_bh' => $row[$i++], //Percent Progress to Annual Typical Growth (%)
                'column_bi' => $row[$i++], //Percent Progress to Annual Stretch Growth (%)
                'column_bj' => $row[$i++], //Mid On Grade Level Scale Score
                'column_bk' => $row[$i++], //Reading Difficulty Indicator (Y/N)
                'column_bl' => $row[$i++], //504 Plan
                'column_bm' => $row[$i++], //English Language Acquisition
                'column_bn' => $row[$i++], //Foster Youth
                'column_bo' => $row[$i++], //Gifted and Talented (GATE)
                'column_bp' => $row[$i++], //Homeless Youth
                'column_bq' => $row[$i++], //Student with Disabilities
                // 'column_br' => $row[$i++], //Transitional Kindergarten
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyReadingBOY::insert($t);
                };
                $bulkData = [];
            }
            //IReadyReadingBOY::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyReadingBOY::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyReadingBOY::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_i_ready_reading_eoy_s($rows,Cycle $cycle) {
        IReadyReadingEOY::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //student_id
                'column_d' => $row[$i++], //Enrolled
                'column_e' => $row[$i++], //Student Grade
                'column_f' => $row[$i++], //Academic Year
                'column_g' => $row[$i++], //School
                'column_h' => $row[$i++], //Subject
                'column_i' => $row[$i++], //User Name
                'column_j' => $row[$i++], //Sex
                'column_k' => $row[$i++], //Hispanic or Latino
                'column_l' => $row[$i++], //Race
                'column_m' => $row[$i++], //English Language Learner
                'column_n' => $row[$i++], //Special Education
                'column_o' => $row[$i++], //Economically Disadvantaged
                'column_p' => $row[$i++], //Migrant
                'column_q' => $row[$i++], //Class(es)
                'column_r' => $row[$i++], //Class Teacher(s)
                'column_s' => $row[$i++], //Report Group(s)
                'column_t' => $row[$i++], //Number of Completed Diagnostics during the time frame
                'column_u' => $row[$i++], //Annual Typical Growth Measure
                'column_v' => $row[$i++], //Annual Stretch Growth Measure
                'column_w' => $row[$i++], //Diagnostic Gain (Note: negative gains=zero)
                'column_x' => $row[$i++], //Diagnostic: Start Date (Most Recent)
                'column_y' => $row[$i++], //Diagnostic: Completion Date (Most Recent)
                'column_z' => $row[$i++], //Diagnostic: Time on Task (min) (Most Recent)
                'column_aa' => $row[$i++], //Diagnostic: Rush Flag (Most Recent)
                'column_ab' => $row[$i++], //Diagnostic: Overall Scale Score (Most Recent)
                'column_ac' => $row[$i++], //Diagnostic: Overall Placement (Most Recent)
                'column_ad' => $row[$i++], //Diagnostic: Percentile (Most Recent)
                'column_ae' => $row[$i++], //Diagnostic: Overall Relative Placement (Most Recent)
                'column_af' => $row[$i++], //Diagnostic: Tier (Most Recent)
                'column_ag' => $row[$i++], //Diagnostic: Lexile Measure (Most Recent)
                'column_ah' => $row[$i++], //Diagnostic: Lexile Range (Most Recent)
                'column_ai' => $row[$i++], //Diagnostic: Grouping (Most Recent)
                'column_aj' => $row[$i++], //Diagnostic: Start Date (1)
                'column_ak' => $row[$i++], //Diagnostic: Completion Date (1)
                'column_al' => $row[$i++], //Diagnostic: Time on Task (min) (1)
                'column_am' => $row[$i++], //Diagnostic: Rush Flag (1)
                'column_an' => $row[$i++], //Diagnostic: Overall Scale Score (1)
                'column_ao' => $row[$i++], //Diagnostic: Overall Placement (1)
                'column_ap' => $row[$i++], //Diagnostic: Percentile (1)
                'column_aq' => $row[$i++], //Diagnostic: Overall Relative Placement (1)
                'column_ar' => $row[$i++], //Diagnostic: Tier (1)
                'column_as' => $row[$i++], //Diagnostic: Start Date (2)
                'column_at' => $row[$i++], //Diagnostic: Completion Date (2)
                'column_au' => $row[$i++], //Diagnostic: Time on Task (min) (2)
                'column_av' => $row[$i++], //Diagnostic: Rush Flag (2)
                'column_aw' => $row[$i++], //Diagnostic: Overall Scale Score (2)
                'column_ax' => $row[$i++], //Diagnostic: Overall Placement (2)
                'column_ay' => $row[$i++], //Diagnostic: Percentile (2)
                'column_az' => $row[$i++], //Diagnostic: Overall Relative Placement (2)
                'column_ba' => $row[$i++], //Diagnostic: Tier (2)
                'column_bb' => $row[$i++], //Diagnostic: Start Date (3)
                'column_bc' => $row[$i++], //Diagnostic: Completion Date (3)
                'column_bd' => $row[$i++], //Diagnostic: Time on Task (min) (3)
                'column_be' => $row[$i++], //Diagnostic: Rush Flag (3)
                'column_bf' => $row[$i++], //Diagnostic: Overall Scale Score (3)
                'column_bg' => $row[$i++], //Diagnostic: Overall Placement (3)
                'column_bh' => $row[$i++], //Diagnostic: Percentile (3)
                'column_bi' => $row[$i++], //Diagnostic: Overall Relative Placement (3)
                'column_bj' => $row[$i++], //Diagnostic: Tier (3)
                'column_bk' => $row[$i++], //Diagnostic: Start Date (4)
                'column_bl' => $row[$i++], //Diagnostic: Completion Date (4)
                'column_bm' => $row[$i++], //Diagnostic: Time on Task (min) (4)
                'column_bn' => $row[$i++], //Diagnostic: Rush Flag (4)
                'column_bo' => $row[$i++], //Diagnostic: Overall Scale Score (4)
                'column_bp' => $row[$i++], //Diagnostic: Overall Placement (4)
                'column_bq' => $row[$i++], //Diagnostic: Percentile (4)
                // 'column_br' => $row[$i++], //Diagnostic: Overall Relative Placement (4)
                // 'column_bs' => $row[$i++], //Diagnostic: Tier (4)
                // 'column_bt' => $row[$i++], //Diagnostic: Start Date (5)
                // 'column_bu' => $row[$i++], //Diagnostic: Completion Date (5)
                // 'column_bv' => $row[$i++], //Diagnostic: Time on Task (min) (5)
                // 'column_bw' => $row[$i++], //Diagnostic: Rush Flag (5)
                // 'column_bx' => $row[$i++], //Diagnostic: Overall Scale Score (5)
                // 'column_by' => $row[$i++], //Diagnostic: Overall Placement (5)
                // 'column_bz' => $row[$i++], //Diagnostic: Percentile (5)
                // 'column_ca' => $row[$i++], //Diagnostic: Overall Relative Placement (5)
                // 'column_cb' => $row[$i++], //Diagnostic: Tier (5)
                // 'column_cc' => $row[$i++], //Instruction: Overall Lessons Passed
                // 'column_cd' => $row[$i++], //Instruction: Overall Lessons Not Passed
                // 'column_ce' => $row[$i++], //Instruction: Overall Lessons Completed
                // 'column_cf' => $row[$i++], //Instruction: Overall Pass Rate (%)
                // 'column_cg' => $row[$i++], //Instruction: Overall Time on Task (min)
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyReadingEOY::insert($t);
                };
                $bulkData = [];
            }
            //IReadyReadingEOY::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyReadingEOY::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyReadingEOY::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_i_ready_reading_mid_years($rows,Cycle $cycle) {
        IReadyReadingMidYear::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //student_id
                'column_d' => $row[$i++], //Enrolled
                'column_e' => $row[$i++], //Student Grade
                'column_f' => $row[$i++], //Academic Year
                'column_g' => $row[$i++], //School
                'column_h' => $row[$i++], //Subject
                'column_i' => $row[$i++], //User Name
                'column_j' => $row[$i++], //Sex
                'column_k' => $row[$i++], //Hispanic or Latino
                'column_l' => $row[$i++], //Race
                'column_m' => $row[$i++], //English Language Learner
                'column_n' => $row[$i++], //Special Education
                'column_o' => $row[$i++], //Economically Disadvantaged
                'column_p' => $row[$i++], //Migrant
                'column_q' => $row[$i++], //Class(es)
                'column_r' => $row[$i++], //Class Teacher(s)
                'column_s' => $row[$i++], //Report Group(s)
                'column_t' => $row[$i++], //Number of Completed Diagnostics during the time frame
                'column_u' => $row[$i++], //Annual Typical Growth Measure
                'column_v' => $row[$i++], //Annual Stretch Growth Measure
                'column_w' => $row[$i++], //Diagnostic Gain (Note: negative gains=zero)
                'column_x' => $row[$i++], //Diagnostic: Start Date (Most Recent)
                'column_y' => $row[$i++], //Diagnostic: Completion Date (Most Recent)
                'column_z' => $row[$i++], //Diagnostic: Time on Task (min) (Most Recent)
                'column_aa' => $row[$i++], //Diagnostic: Rush Flag (Most Recent)
                'column_ab' => $row[$i++], //Diagnostic: Overall Scale Score (Most Recent)
                'column_ac' => $row[$i++], //Diagnostic: Overall Placement (Most Recent)
                'column_ad' => $row[$i++], //Diagnostic: Percentile (Most Recent)
                'column_ae' => $row[$i++], //Diagnostic: Overall Relative Placement (Most Recent)
                'column_af' => $row[$i++], //Diagnostic: Tier (Most Recent)
                'column_ag' => $row[$i++], //Diagnostic: Lexile Measure (Most Recent)
                'column_ah' => $row[$i++], //Diagnostic: Lexile Range (Most Recent)
                'column_ai' => $row[$i++], //Diagnostic: Grouping (Most Recent)
                'column_aj' => $row[$i++], //Diagnostic: Start Date (1)
                'column_ak' => $row[$i++], //Diagnostic: Completion Date (1)
                'column_al' => $row[$i++], //Diagnostic: Time on Task (min) (1)
                'column_am' => $row[$i++], //Diagnostic: Rush Flag (1)
                'column_an' => $row[$i++], //Diagnostic: Overall Scale Score (1)
                'column_ao' => $row[$i++], //Diagnostic: Overall Placement (1)
                'column_ap' => $row[$i++], //Diagnostic: Percentile (1)
                'column_aq' => $row[$i++], //Diagnostic: Overall Relative Placement (1)
                'column_ar' => $row[$i++], //Diagnostic: Tier (1)
                'column_as' => $row[$i++], //Diagnostic: Start Date (2)
                'column_at' => $row[$i++], //Diagnostic: Completion Date (2)
                'column_au' => $row[$i++], //Diagnostic: Time on Task (min) (2)
                'column_av' => $row[$i++], //Diagnostic: Rush Flag (2)
                'column_aw' => $row[$i++], //Diagnostic: Overall Scale Score (2)
                'column_ax' => $row[$i++], //Diagnostic: Overall Placement (2)
                'column_ay' => $row[$i++], //Diagnostic: Percentile (2)
                'column_az' => $row[$i++], //Diagnostic: Overall Relative Placement (2)
                'column_ba' => $row[$i++], //Diagnostic: Tier (2)
                'column_bb' => $row[$i++], //Diagnostic: Start Date (3)
                'column_bc' => $row[$i++], //Diagnostic: Completion Date (3)
                'column_bd' => $row[$i++], //Diagnostic: Time on Task (min) (3)
                'column_be' => $row[$i++], //Diagnostic: Rush Flag (3)
                'column_bf' => $row[$i++], //Diagnostic: Overall Scale Score (3)
                'column_bg' => $row[$i++], //Diagnostic: Overall Placement (3)
                'column_bh' => $row[$i++], //Diagnostic: Percentile (3)
                'column_bi' => $row[$i++], //Diagnostic: Overall Relative Placement (3)
                'column_bj' => $row[$i++], //Diagnostic: Tier (3)
                'column_bk' => $row[$i++], //Diagnostic: Start Date (4)
                'column_bl' => $row[$i++], //Diagnostic: Completion Date (4)
                'column_bm' => $row[$i++], //Diagnostic: Time on Task (min) (4)
                'column_bn' => $row[$i++], //Diagnostic: Rush Flag (4)
                'column_bo' => $row[$i++], //Diagnostic: Overall Scale Score (4)
                'column_bp' => $row[$i++], //Diagnostic: Overall Placement (4)
                'column_bq' => $row[$i++], //Diagnostic: Percentile (4)
                // 'column_br' => $row[$i++], //Diagnostic: Overall Relative Placement (4)
                // 'column_bs' => $row[$i++], //Diagnostic: Tier (4)
                // 'column_bt' => $row[$i++], //Diagnostic: Start Date (5)
                // 'column_bu' => $row[$i++], //Diagnostic: Completion Date (5)
                // 'column_bv' => $row[$i++], //Diagnostic: Time on Task (min) (5)
                // 'column_bw' => $row[$i++], //Diagnostic: Rush Flag (5)
                // 'column_bx' => $row[$i++], //Diagnostic: Overall Scale Score (5)
                // 'column_by' => $row[$i++], //Diagnostic: Overall Placement (5)
                // 'column_bz' => $row[$i++], //Diagnostic: Percentile (5)
                // 'column_ca' => $row[$i++], //Diagnostic: Overall Relative Placement (5)
                // 'column_cb' => $row[$i++], //Diagnostic: Tier (5)
                // 'column_cc' => $row[$i++], //Instruction: Overall Lessons Passed
                // 'column_cd' => $row[$i++], //Instruction: Overall Lessons Not Passed
                // 'column_ce' => $row[$i++], //Instruction: Overall Lessons Completed
                // 'column_cf' => $row[$i++], //Instruction: Overall Pass Rate (%)
                // 'column_cg' => $row[$i++], //Instruction: Overall Time on Task (min)
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];

            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyReadingMidYear::insert($t);
                };
                $bulkData = [];
            }
            //IReadyReadingMidYear::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyReadingMidYear::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyReadingMidYear::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_math180_minutes($rows,Cycle $cycle) {
        Math180Minutes::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Student Last Name
                'column_b' => $row[$i++], //Student First Name
                'column_c' => $row[$i++], //SSID
                'column_d' => $row[$i++], //Grade
                'column_e' => $row[$i++], //SIS
                'column_f' => $row[$i++], //Qualifying Subject
                'column_g' => $row[$i++], //Teacher Name
                'column_h' => $row[$i++], //Read 180 Minutes
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Math180Minutes::insert($t);
                };
                $bulkData = [];
            }
            //Math180Minutes::create($data);
            $inserts++;
        }

        foreach (array_chunk($bulkData,100) as $t)
        {
            Math180Minutes::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = Math180Minutes::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_read180_minutes($rows,Cycle $cycle) {
        Read180Minutes::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Student Last Name
                'column_b' => $row[$i++], //Student First Name
                'column_c' => $row[$i++], //SSID
                'column_d' => $row[$i++], //Grade
                'column_e' => $row[$i++], //SIS
                'column_f' => $row[$i++], //Qualifying Subject
                'column_g' => $row[$i++], //Teacher Name
                'column_h' => $row[$i++], //Read 180 Minutes
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Read180Minutes::insert($t);
                };
                $bulkData = [];
            }
            //Read180Minutes::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            Read180Minutes::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = Read180Minutes::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_sheet15s($rows,Cycle $cycle) {
        Sheet15::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => 0, //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Student Last Name
                'column_b' => $row[$i++], //Student First Name
                'column_c' => $row[$i++], //Teacher
                'column_d' => $row[$i++], //Report
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];

            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Sheet15::insert($t);
                };
                $bulkData = [];
            }
            //Sheet15::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            Sheet15::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = Sheet15::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_star_eoy_maths($rows,Cycle $cycle) {
        StarEOYMath::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => 0, //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Grade
                'column_b' => $row[$i++], //Student
                'column_c' => $row[$i++], //Assignment Type
                'column_d' => $row[$i++], //Growth Proficiency Category
                'column_e' => $row[$i++], //SGP (Expectation=50)
                'column_f' => $row[$i++], //Test 1 Test Type
                'column_g' => $row[$i++], //Test 1 Test Date
                'column_h' => $row[$i++], //Test 1 Test Duration
                'column_i' => $row[$i++], //Test 1 SS
                'column_j' => $row[$i++], //Test 1 Benchmark Category
                'column_k' => $row[$i++], //Test 1 PR
                'column_l' => $row[$i++], //Test 1 NCE
                'column_m' => $row[$i++], //Test 2 Test Type
                'column_n' => $row[$i++], //Test 2 Test Date
                'column_o' => $row[$i++], //Test 2 Test Duration
                'column_p' => $row[$i++], //Test 2 SS
                'column_q' => $row[$i++], //Test 2 Benchmark Category
                'column_r' => $row[$i++], //Test 2 PR
                'column_s' => $row[$i++], //Test 2 NCE
                'column_t' => $row[$i++], //Test 3 Test Type
                'column_u' => $row[$i++], //Test 3 Test Date
                'column_v' => $row[$i++], //Test 3 Test Duration
                'column_w' => $row[$i++], //Test 3 SS
                'column_x' => $row[$i++], //Test 3 Benchmark Category
                'column_y' => $row[$i++], //Test 3 PR
                'column_z' => $row[$i++], //Test 3 NCE
                'column_aa' => $row[$i++], //Latest Change in Score
                'column_ab' => $row[$i++], //Latest Change in PR
                'column_ac' => $row[$i++], //Latest Change in NCE
                'column_ad' => $row[$i], //student id
                'student_id' => $row[$i], //student_id
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    StarEOYMath::insert($t);
                };
                $bulkData = [];
            }
            //StarEOYMath::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StarEOYMath::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = StarEOYMath::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_star_eoy_readings($rows,Cycle $cycle) {
        StarEOYReading::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => 0, //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Grade
                'column_b' => $row[$i++], //Student
                'column_c' => $row[$i++], //Assignment Type
                'column_d' => $row[$i++], //Growth Proficiency Category
                'column_e' => $row[$i++], //SGP (Expectation=50)
                'column_f' => $row[$i++], //Test 1 Test Type
                'column_g' => $row[$i++], //Test 1 Test Date
                'column_h' => $row[$i++], //Test 1 Test Duration
                'column_i' => $row[$i++], //Test 1 SS
                'column_j' => $row[$i++], //Test 1 Benchmark Category
                'column_k' => $row[$i++], //Test 1 PR
                'column_l' => $row[$i++], //Test 1 NCE
                'column_m' => $row[$i++], //Test 1 IRL
                'column_n' => $row[$i++], //Test 1 ZPD
                'column_o' => $row[$i++], //Test 1 Est. ORF
                'column_p' => $row[$i++], //Test 2 Test Type
                'column_q' => $row[$i++], //Test 2 Test Date
                'column_r' => $row[$i++], //Test 2 Test Duration
                'column_s' => $row[$i++], //Test 2 SS
                'column_t' => $row[$i++], //Test 2 Benchmark Category
                'column_u' => $row[$i++], //Test 2 PR
                'column_v' => $row[$i++], //Test 2 NCE
                'column_w' => $row[$i++], //Test 2 IRL
                'column_x' => $row[$i++], //Test 2 ZPD
                'column_y' => $row[$i++], //Test 2 Est. ORF
                'column_z' => $row[$i++], //Latest Change in Score
                'column_aa' => $row[$i++], //Latest Change in PR
                'column_ab' => $row[$i++], //Latest Change in NCE
                'column_ac' => $row[$i++], //Latest Change in IRL
                'column_ad' => $row[$i++], //Latest Change in Est. ORF
                'column_ae' => $row[$i], //student id
                'student_id' => $row[$i], //student_id
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    StarEOYReading::insert($t);
                };
                $bulkData = [];
            }
            //StarEOYReading::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StarEOYReading::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = StarEOYReading::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_star_fall_maths($rows,Cycle $cycle) {
        StarFallMath::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                //$row[29] = encrypt($row[29]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'cycle_id' => $cycle->id, //
                'student_id' => $row[3], //student_id
                'column_a' => $row[$i++],  //School
                'column_b' => $row[$i++],  //Class/Group
                'column_c' => $row[$i++],  //Student
                'column_d' => $row[$i++],  //Student ID
                'column_e' => $row[$i++],  //Grade
                'column_f' => $row[$i++],  //SS (Star Unified)
                'column_g' => $row[$i++],  //Benchmark Type
                'column_h' => $row[$i++],  //
                'column_i' => $row[$i++],  //
                'column_j' => $row[$i++],  //
                'column_k' => $row[$i++],  //PR
                'column_l' => $row[$i++],  //Test Duration
                'column_m' => $row[$i++],  //Test Fidelity
                'column_n' => $row[$i++],  //Standard Set Description
                'column_o' => $row[$i++],  //Domain Group 1
                'column_p' => $row[$i++],  //Domain 1
                'column_q' => $row[$i++],  //Domain Score 1
                'column_r' => $row[$i++],  //Domain Group 2
                'column_s' => $row[$i++],  //Domain 2
                'column_t' => $row[$i++],  //Domain Score 2
                'column_u' => $row[$i++],  //Domain Group 3
                'column_v' => $row[$i++],  //Domain 3
                'column_w' => $row[$i++],  //Domain Score 3
                'column_x' => $row[$i++],  //Domain Group 4
                'column_y' => $row[$i++],  //Domain 4
                'column_z' => $row[$i++],  //Domain Score 4
                'column_aa' => $row[$i++],  //Domain Group 5
                'column_ab' => $row[$i++],  //Domain 5
                'column_ac' => $row[$i++],  //Domain Score 5
                'column_ad' => $row[$i++],  //Domain Group 6
                'column_ae' => $row[$i++],  //Student ID
                'column_af' => $row[$i++], //Domain Score 6
                'column_ag' => $row[$i++], //Domain Group 7
                'column_ah' => $row[$i++], //Domain 7
                'column_ai' => $row[$i++], //Domain Score 7
                'column_aj' => $row[$i++], //Domain Group 8
                'column_ak' => $row[$i++], //Domain 8
                'column_al' => $row[$i++], //Domain Score 8
                'column_am' => $row[$i++], //Domain Group 9
                'column_an' => $row[$i++], //Domain 9
                'column_ao' => $row[$i++], //Domain Score 9
                'column_ap' => $row[$i++], //Domain Group 10
                'column_aq' => $row[$i++], //Domain 10
                'column_ar' => $row[$i++], //Domain Score 10
                'column_as' => $row[$i++], //Domain Group 11
                'column_at' => $row[$i++], //Domain 11
                'column_au' => $row[$i++], //Domain Score 11
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    StarFallMath::insert($t);
                };
                $bulkData = [];
            }
            //StarFallMath::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StarFallMath::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = StarFallMath::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
    }

    protected function loadRecords_on_star_fall_readings($rows,Cycle $cycle) {
        StarFallReading::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'cycle_id' => $cycle->id, //
                'student_id' => $row[3], //student_id
                'column_a' => $row[$i++], //School
                'column_b' => $row[$i++], //Class/Group
                'column_c' => $row[$i++], //Student
                'column_d' => $row[$i++], //Student ID
                'column_e' => $row[$i++], //Grade
                'column_f' => $row[$i++], //SS (Star Unified)
                'column_g' => $row[$i++], //Benchmark Type
                'column_h' => $row[$i++], //
                'column_i' => $row[$i++], //
                'column_j' => $row[$i++], //
                'column_k' => $row[$i++], //PR
                'column_l' => $row[$i++], //IRL
                'column_m' => $row[$i++], //Est. ORF
                'column_n' => $row[$i++], //ZPD
                'column_o' => $row[$i++], //Test Duration
                'column_p' => $row[$i++], //Test Fidelity
                'column_q' => $row[$i++], //Standard Set Description
                'column_r' => $row[$i++], //Domain Group 1
                'column_s' => $row[$i++], //Domain 1
                'column_t' => $row[$i++], //Domain Score 1
                'column_u' => $row[$i++], //Domain Group 2
                'column_v' => $row[$i++], //Domain 2
                'column_w' => $row[$i++], //Domain Score 2
                'column_x' => $row[$i++], //Domain Group 3
                'column_y' => $row[$i++], //Domain 3
                'column_z' => $row[$i++], //Domain Score 3
                'column_aa' => $row[$i++], //Domain Group 4
                'column_ab' => $row[$i++], //Domain 4
                'column_ac' => $row[$i++], //Domain Score 4
                'column_ad' => $row[$i++], //Domain Group 5
                'column_ae' => $row[$i++], //Student ID
                'column_af' => $row[$i++], //Domain Score 5
                'column_ag' => $row[$i++], //Domain Group 6
                'column_ah' => $row[$i++], //Domain 6
                'column_ai' => $row[$i++], //Domain Score 6
                'column_aj' => $row[$i++], //Domain Group 7
                'column_ak' => $row[$i++], //Domain 7
                'column_al' => $row[$i++], //Domain Score 7
                'column_am' => $row[$i++], //Domain Group 8
                'column_an' => $row[$i++], //Domain 8
                'column_ao' => $row[$i++], //Domain Score 8
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    StarFallReading::insert($t);
                };
                $bulkData = [];
            }
            //StarFallReading::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StarFallReading::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = StarFallReading::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_star_mid_year_maths($rows,Cycle $cycle) {
        StarMidYearMath::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by

                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Grade
                'column_b' => $row[$i++], //Student
                'column_c' => $row[$i++], //Assignment Type
                'column_d' => $row[$i++], //Growth Proficiency Category
                'column_e' => $row[$i++], //SGP (Expectation=50)
                'column_f' => $row[$i++], //Test 1 Test Type
                'column_g' => $row[$i++], //Test 1 Test Date
                'column_h' => $row[$i++], //Test 1 Test Duration
                'column_i' => $row[$i++], //Test 1 SS
                'column_j' => $row[$i++], //Test 1 Benchmark Category
                'column_k' => $row[$i++], //Test 1 PR
                'column_l' => $row[$i++], //Test 1 NCE
                'column_m' => $row[$i++], //Test 2 Test Type
                'column_n' => $row[$i++], //Test 2 Test Date
                'column_o' => $row[$i++], //Test 2 Test Duration
                'column_p' => $row[$i++], //Test 2 SS
                'column_q' => $row[$i++], //Test 2 Benchmark Category
                'column_r' => $row[$i++], //Test 2 PR
                'column_s' => $row[$i++], //Test 2 NCE
                'column_t' => $row[$i++], //Test 3 Test Type
                'column_u' => $row[$i++], //Test 3 Test Date
                'column_v' => $row[$i++], //Test 3 Test Duration
                'column_w' => $row[$i++], //Test 3 SS
                'column_x' => $row[$i++], //Test 3 Benchmark Category
                'column_y' => $row[$i++], //Test 3 PR
                'column_z' => $row[$i++], //Test 3 NCE
                'column_aa' => $row[$i++], //Latest Change in Score
                'column_ab' => $row[$i++], //Latest Change in PR
                'column_ac' => $row[$i++], //Latest Change in NCE
                'column_ad' => $row[$i], //student id
                'student_id' => $row[$i], //student_id
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    StarMidYearMath::insert($t);
                };
                $bulkData = [];
            }
            //StarMidYearMath::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StarMidYearMath::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = StarMidYearMath::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_star_mid_year_readings($rows,Cycle $cycle) {
        StarMidYearReading::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => 0, //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Grade
                'column_b' => $row[$i++], //Student
                'column_c' => $row[$i++], //Assignment Type
                'column_d' => $row[$i++], //Growth Proficiency Category
                'column_e' => $row[$i++], //SGP (Expectation=50)
                'column_f' => $row[$i++], //Test 1 Test Type
                'column_g' => $row[$i++], //Test 1 Test Date
                'column_h' => $row[$i++], //Test 1 Test Duration
                'column_i' => $row[$i++], //Test 1 SS
                'column_j' => $row[$i++], //Test 1 Benchmark Category
                'column_k' => $row[$i++], //Test 1 PR
                'column_l' => $row[$i++], //Test 1 NCE
                'column_m' => $row[$i++], //Test 1 IRL
                'column_n' => $row[$i++], //Test 1 ZPD
                'column_o' => $row[$i++], //Test 1 Est. ORF
                'column_p' => $row[$i++], //Test 2 Test Type
                'column_q' => $row[$i++], //Test 2 Test Date
                'column_r' => $row[$i++], //Test 2 Test Duration
                'column_s' => $row[$i++], //Test 2 SS
                'column_t' => $row[$i++], //Test 2 Benchmark Category
                'column_u' => $row[$i++], //Test 2 PR
                'column_v' => $row[$i++], //Test 2 NCE
                'column_w' => $row[$i++], //Test 2 IRL
                'column_x' => $row[$i++], //Test 2 ZPD
                'column_y' => $row[$i++], //Test 2 Est. ORF
                'column_z' => $row[$i++], //Latest Change in Score
                'column_aa' => $row[$i++], //Latest Change in PR
                'column_ab' => $row[$i++], //Latest Change in NCE
                'column_ac' => $row[$i++], //Latest Change in IRL
                'column_ad' => $row[$i++], //Latest Change in Est. ORF
                'column_ae' => $row[$i], //Student id
                'student_id' => $row[$i], //student_id
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    StarMidYearReading::insert($t);
                };
                $bulkData = [];
            }
            //StarMidYearReading::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StarMidYearReading::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = StarMidYearReading::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_student_lists($rows,Cycle $cycle) {
        StudentList::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Student Last Name
                'column_b' => $row[$i++], //Student First Name
                'column_c' => $row[$i++], //SSID
                'column_d' => $row[$i++], //Grade
                'column_e' => $row[$i++], //SIS
                'column_f' => $row[$i++], //Qualifying Subject
                'column_g' => $row[$i++], //Teacher Name
                'column_h' => $row[$i++], //Diagnostic Placement
                'column_i' => $row[$i++], //Qualified for Intervention
                'column_j' => $row[$i++], //Recommended Program
                'column_k' => $row[$i++], //Student School Email
                'column_l' => $row[$i++], //SPED Y/N
                'column_m' => $row[$i++], //SAI Teacher
                'column_n' => $row[$i++], //Easycbm Fall Assessment Score
                'column_o' => $row[$i++], //Intervention selection
                'column_p' => $row[$i++], //6-8th Grade Only                   PAPER REQUEST
                'column_q' => $row[$i++], //iReady mid year Relative Placement
                'column_r' => $row[$i++], //Growth iReady
                'column_s' => $row[$i++], //Easycbm Spring Assessment Score (add as comment)
                'column_t' => $row[$i++], //iReady Post Test Relative Placement
                'column_u' => $row[$i++], //Growth iReady
                'column_v' => $row[$i++], //Easycbm Fall Assessment Point/Percent
                'column_w' => $row[$i++], //Easycbm Winter Assessment Point/Percent
                'column_x' => $row[$i++], //Easycbm Spring Assessment Point/Percent
                'column_y' => $row[$i++], //Growth Easycbm points/percent
                'column_z' => $row[$i++], //Class info link
                'column_aa' => $row[$i++], //Notes
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    StudentList::insert($t);
                };
                $bulkData = [];
            }
            //StudentList::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StudentList::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = StudentList::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_trans_math_minutes($rows,Cycle $cycle) {
        TransMathMinutes::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //
                'column_a' => $row[$i++], //Student Last Name
                'column_b' => $row[$i++], //Student First Name
                'column_c' => $row[$i++], //SSID
                'column_d' => $row[$i++], //Grade
                'column_e' => $row[$i++], //SIS
                'column_f' => $row[$i++], //Qualifying Subject
                'column_g' => $row[$i++], //Teacher Name
                'column_h' => $row[$i++], //VMATH Minutes
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    TransMathMinutes::insert($t);
                };
                $bulkData = [];
            }
            //TransMathMinutes::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            TransMathMinutes::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = TransMathMinutes::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_v_math_minutes($rows,Cycle $cycle) {
        VMathMinutes::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //scycle_id
                'column_a' => $row[$i++], //Student Last Name
                'column_b' => $row[$i++], //Student First Name
                'column_c' => $row[$i++], //SSID
                'column_d' => $row[$i++], //Grade
                'column_e' => $row[$i++], //SIS
                'column_f' => $row[$i++], //Qualifying Subject
                'column_g' => $row[$i++], //Teacher Name
                'column_h' => $row[$i++], //VMATH Minutes
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    VMathMinutes::insert($t);
                };
                $bulkData = [];
            }
            VMathMinutes::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            VMathMinutes::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = VMathMinutes::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_i_ready_reading_minutes($rows,Cycle $cycle) {
        IReadyReadingMinutes::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //scycle_id
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //Student ID
                'column_d' => $row[$i++], //Student Grade
                'column_e' => $row[$i++], //Academic Year
                'column_f' => $row[$i++], //School
                'column_g' => $row[$i++], //Subject
                'column_h' => $row[$i++], //Enrolled
                'column_i' => $row[$i++], //User Name
                'column_j' => $row[$i++], //Sex
                'column_k' => $row[$i++], //Hispanic or Latino
                'column_l' => $row[$i++], //Race
                'column_m' => $row[$i++], //English Language Learner
                'column_n' => $row[$i++], //Special Education
                'column_o' => $row[$i++], //Economically Disadvantaged
                'column_p' => $row[$i++], //Migrant
                'column_q' => $row[$i++], //Class(es)
                'column_r' => $row[$i++], //Class Teacher(s)
                'column_s' => $row[$i++], //Report Group(s)
                'column_t' => $row[$i++], //First Lesson Completion Date
                'column_u' => $row[$i++], //Most Recent Lesson Completion Date
                'column_v' => $row[$i++], //Year-to-Date Overall Time on Task (min)
                'column_w' => $row[$i++], //Year-to-Date Overall Lessons Passed
                'column_x' => $row[$i++], //Year-to-Date Overall Lessons Completed
                'column_y' => $row[$i++], //Year-to-Date Overall % Lessons Passed
                'column_z' => $row[$i++], //Year-to-Date Phonological Awareness Time on Task (min)
                'column_aa' => $row[$i++], //Year-to-Date Phonological Awareness Lessons Passed
                'column_ab' => $row[$i++], //Year-to-Date Phonological Awareness Lessons Completed
                'column_ac' => $row[$i++], //Year-to-Date Phonological Awareness % Lessons Passed
                'column_ad' => $row[$i++], //Year-to-Date Phonics Time on Task (min)
                'column_ae' => $row[$i++], //Year-to-Date Phonics Lessons Passed
                'column_af' => $row[$i++], //Year-to-Date Phonics Lessons Completed
                'column_ag' => $row[$i++], //Year-to-Date Phonics % Lessons Passed
                'column_ah' => $row[$i++], //Year-to-Date High-Frequency Words Time on Task (min)
                'column_ai' => $row[$i++], //Year-to-Date High-Frequency Words Lessons Passed
                'column_aj' => $row[$i++], //Year-to-Date High-Frequency Words Lessons Completed
                'column_ak' => $row[$i++], //Year-to-Date High-Frequency Words % Lessons Passed
                'column_al' => $row[$i++], //Year-to-Date Vocabulary Time on Task (min)
                'column_am' => $row[$i++], //Year-to-Date Vocabulary Lessons Passed
                'column_an' => $row[$i++], //Year-to-Date Vocabulary Lessons Completed
                'column_ao' => $row[$i++], //Year-to-Date Vocabulary % Lessons Passed
                'column_ap' => $row[$i++], //Year-to-Date Comprehension Time on Task (min)
                'column_aq' => $row[$i++], //Year-to-Date Comprehension Lessons Passed
                'column_ar' => $row[$i++], //Year-to-Date Comprehension Lessons Completed
                'column_as' => $row[$i++], //Year-to-Date Comprehension % Lessons Passed
                'column_at' => $row[$i++], //Year-to-Date Comprehension: Close Reading Time on Task (min)
                'column_au' => $row[$i++], //Year-to-Date Comprehension: Close Reading Lessons Passed
                'column_av' => $row[$i++], //Year-to-Date Comprehension: Close Reading Lessons Completed
                'column_aw' => $row[$i++], //Year-to-Date Comprehension: Close Reading % Lessons Passed
                'column_ax' => $row[$i++],
                'column_ay' => $row[$i++],
                'column_az' => $row[$i++],
                'column_ba' => $row[$i++],
                'column_bb' => $row[$i++],
                'column_bc' => $row[$i++],
                'column_bd' => $row[$i++],
                'column_be' => $row[$i++],
                'column_bf' => $row[$i++],
                'column_bg' => $row[$i++],
                'column_bh' => $row[$i++],
                'column_bi' => $row[$i++],
                'column_bj' => $row[$i++],
                'column_bk' => $row[$i++],
                'column_bl' => $row[$i++],
                'column_bm' => $row[$i++],
                'column_bn' => $row[$i++],
                'column_bo' => $row[$i++],
                'column_bp' => $row[$i++],
                'column_bq' => $row[$i++],
                'column_br' => $row[$i++],
                'column_bs' => $row[$i++],
                'column_bt' => $row[$i++],
                'column_bu' => $row[$i++],
                'column_bv' => $row[$i++],
                'column_bw' => $row[$i++],
                'column_bx' => $row[$i++],
                'column_by' => $row[$i++],
                'column_bz' => $row[$i++],
                'column_ca' => $row[$i++],
                'column_cb' => $row[$i++],
                'column_cc' => $row[$i++],
                'column_cd' => $row[$i++],
                'column_ce' => $row[$i++],
                'column_cf' => $row[$i++],
                'column_cg' => $row[$i++],
                'column_ch' => $row[$i++],
                'column_ci' => $row[$i++],
                'column_cj' => $row[$i++],
                'column_ck' => $row[$i++],
                'column_cl' => $row[$i++],
                'column_cm' => $row[$i++],
                'column_cn' => $row[$i++],
                'column_co' => $row[$i++],
                'column_cp' => $row[$i++],
                'column_cq' => $row[$i++],
                'column_cr' => $row[$i++],
                'column_cs' => $row[$i++],
                'column_ct' => $row[$i++],
                'column_cu' => $row[$i++],
                'column_cv' => $row[$i++],
                'column_cw' => $row[$i++],
                'column_cx' => $row[$i++],
                'column_cy' => $row[$i++],
                'column_cz' => $row[$i++],
                'column_da' => $row[$i++],
                'column_db' => $row[$i++],
                'column_dc' => $row[$i++],
                'column_dd' => $row[$i++],
                'column_de' => $row[$i++],
                'column_df' => $row[$i++],
                'column_dg' => $row[$i++],
                'column_dh' => $row[$i++],
                'column_di' => $row[$i++],
                'column_dj' => $row[$i++],
                'column_dk' => $row[$i++],
                'column_dl' => $row[$i++],
                'column_dm' => $row[$i++],
                'column_dn' => $row[$i++],
                'column_do' => $row[$i++],
                'column_dp' => $row[$i++],
                'column_dq' => $row[$i++],
                'column_dr' => $row[$i++],
                'column_ds' => $row[$i++],
                'column_dt' => $row[$i++],
                'column_du' => $row[$i++],
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()

            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyReadingMinutes::insert($t);
                };
                $bulkData = [];
            }
            //IReadyReadingMinutes::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyReadingMinutes::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyReadingMinutes::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_i_ready_math_minutes($rows,Cycle $cycle) {
        IReadyMathMinutes::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //scycle_id
                'column_a' => $row[$i++], //Last Name
                'column_b' => $row[$i++], //First Name
                'column_c' => $row[$i++], //Student ID
                'column_d' => $row[$i++], //Student Grade
                'column_e' => $row[$i++], //Academic Year
                'column_f' => $row[$i++], //School
                'column_g' => $row[$i++], //Subject
                'column_h' => $row[$i++], //Enrolled
                'column_i' => $row[$i++], //User Name
                'column_j' => $row[$i++], //Sex
                'column_k' => $row[$i++], //Hispanic or Latino
                'column_l' => $row[$i++], //Race
                'column_m' => $row[$i++], //English Language Learner
                'column_n' => $row[$i++], //Special Education
                'column_o' => $row[$i++], //Economically Disadvantaged
                'column_p' => $row[$i++], //Migrant
                'column_q' => $row[$i++], //Class(es)
                'column_r' => $row[$i++], //Class Teacher(s)
                'column_s' => $row[$i++], //Report Group(s)
                'column_t' => $row[$i++], //First Lesson Completion Date
                'column_u' => $row[$i++], //Most Recent Lesson Completion Date
                'column_v' => $row[$i++], //Year-to-Date Overall Time on Task (min)
                'column_w' => $row[$i++], //Year-to-Date Overall Lessons Passed
                'column_x' => $row[$i++], //Year-to-Date Overall Lessons Completed
                'column_y' => $row[$i++], //Year-to-Date Overall % Lessons Passed
                'column_z' => $row[$i++], //Year-to-Date Number and Operations Time on Task (min)
                'column_aa' => $row[$i++], //Year-to-Date Number and Operations Lessons Passed
                'column_ab' => $row[$i++], //Year-to-Date Number and Operations Lessons Completed
                'column_ac' => $row[$i++], //Year-to-Date Number and Operations % Lessons Passed
                'column_ad' => $row[$i++], //Year-to-Date Algebra and Algebraic Thinking Time on Task (min)
                'column_ae' => $row[$i++], //Year-to-Date Algebra and Algebraic Thinking Lessons Passed
                'column_af' => $row[$i++], //Year-to-Date Algebra and Algebraic Thinking Lessons Completed
                'column_ag' => $row[$i++], //Year-to-Date Algebra and Algebraic Thinking % Lessons Passed
                'column_ah' => $row[$i++], //Year-to-Date Measurement and Data Time on Task (min)
                'column_ai' => $row[$i++], //Year-to-Date Measurement and Data Lessons Passed
                'column_aj' => $row[$i++], //Year-to-Date Measurement and Data Lessons Completed
                'column_ak' => $row[$i++], //Year-to-Date Measurement and Data % Lessons Passed
                'column_al' => $row[$i++], //Year-to-Date Geometry Time on Task (min)
                'column_am' => $row[$i++], //Year-to-Date Geometry Lessons Passed
                'column_an' => $row[$i++], //Year-to-Date Geometry Lessons Completed
                'column_ao' => $row[$i++], //Year-to-Date Geometry % Lessons Passed
                'column_ap' => $row[$i++],
                'column_aq' => $row[$i++],
                'column_ar' => $row[$i++],
                'column_as' => $row[$i++],
                'column_at' => $row[$i++],
                'column_au' => $row[$i++],
                'column_av' => $row[$i++],
                'column_aw' => $row[$i++],
                'column_ax' => $row[$i++],
                'column_ay' => $row[$i++],
                'column_az' => $row[$i++],
                'column_ba' => $row[$i++],
                'column_bb' => $row[$i++],
                'column_bc' => $row[$i++],
                'column_bd' => $row[$i++],
                'column_be' => $row[$i++],
                'column_bf' => $row[$i++],
                'column_bg' => $row[$i++],
                'column_bh' => $row[$i++],
                'column_bi' => $row[$i++],
                'column_bj' => $row[$i++],
                'column_bk' => $row[$i++],
                'column_bl' => $row[$i++],
                'column_bm' => $row[$i++],
                'column_bn' => $row[$i++],
                'column_bo' => $row[$i++],
                'column_bp' => $row[$i++],
                'column_bq' => $row[$i++],
                'column_br' => $row[$i++],
                'column_bs' => $row[$i++],
                'column_bt' => $row[$i++],
                'column_bu' => $row[$i++],
                'column_bv' => $row[$i++],
                'column_bw' => $row[$i++],
                'column_bx' => $row[$i++],
                'column_by' => $row[$i++],
                'column_bz' => $row[$i++],
                'column_ca' => $row[$i++],
                'column_cb' => $row[$i++],
                'column_cc' => $row[$i++],
                'column_cd' => $row[$i++],
                'column_ce' => $row[$i++],
                'column_cf' => $row[$i++],
                'column_cg' => $row[$i++],
                'column_ch' => $row[$i++],
                'column_ci' => $row[$i++],
                'column_cj' => $row[$i++],
                'column_ck' => $row[$i++],
                'column_cl' => $row[$i++],
                'column_cm' => $row[$i++],
                'column_cn' => $row[$i++],
                'column_co' => $row[$i++],
                'column_cp' => $row[$i++],
                'column_cq' => $row[$i++],
                'column_cr' => $row[$i++],
                'column_cs' => $row[$i++],
                'column_ct' => $row[$i++],
                'column_cu' => $row[$i++],
                'column_cv' => $row[$i++],
                'column_cw' => $row[$i++],
                'column_cx' => $row[$i++],
                'column_cy' => $row[$i++],
                'column_cz' => $row[$i++],
                'column_ca' => $row[$i++],
                'column_db' => $row[$i++],
                'column_dc' => $row[$i++],
                'column_dd' => $row[$i++],
                'column_de' => $row[$i++],
                'column_df' => $row[$i++],
                'column_dg' => $row[$i++],
                'column_dh' => $row[$i++],
                'column_di' => $row[$i++],
                'column_dj' => $row[$i++],
                'column_dk' => $row[$i++],
                'column_dl' => $row[$i++],
                'column_dm' => $row[$i++],
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    IReadyMathMinutes::insert($t);
                };
                $bulkData = [];
            }
            //IReadyMathMinutes::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            IReadyMathMinutes::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = IReadyMathMinutes::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_math_lists($rows,Cycle $cycle) {
        MathList::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                //$row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[2], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //Student Last Name
                'column_b' => $row[$i++], //Student First Name
                'column_c' => $row[$i++], //SSID
                'column_d' => $row[$i++], //Grade
                'column_e' => $row[$i++], //SIS
                'column_f' => $row[$i++], //Qualifying Subject
                'column_g' => $row[$i++], //Teacher Name
                'column_h' => $row[$i++], //Diagnostic Placement
                'column_i' => $row[$i++], //Qualified for Intervention
                'column_j' => $row[$i++], //Recommended Program
                'column_k' => $row[$i++], //Student School Email
                'column_l' => $row[$i++], //SPED Y/N
                'column_m' => $row[$i++], //SAI Teacher
                'column_n' => $row[$i++], //Easycbm Fall Assessment Score
                'column_o' => $row[$i++], //Intervention selection
                'column_p' => $row[$i++], //6-8th Grade Only                   PAPER REQUEST
                'column_q' => $row[$i++], //iReady mid year Relative Placement
                'column_r' => $row[$i++], //Growth iReady
                'column_s' => $row[$i++], //Easycbm Spring Assessment Score (add as comment)
                'column_t' => $row[$i++], //iReady Post Test Relative Placement
                'column_u' => $row[$i++], //Growth iReady
                'column_v' => $row[$i++], //Easycbm Fall Assessment Point/Percent
                'column_w' => $row[$i++], //Easycbm Winter Assessment Point/Percent
                'column_x' => $row[$i++], //Easycbm Spring Assessment Point/Percent
                'column_y' => $row[$i++], //Growth Easycbm points/percent
                'column_z' => $row[$i++], //Class info link
                'column_aa' => $row[$i++], //Notes
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    MathList::insert($t);
                };
                $bulkData = [];
            }
            //MathList::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            MathList::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = MathList::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_attendance_elas($rows,Cycle $cycle) {
        AttendanceEla::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                //$row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                $row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[0], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //SSID
                'column_b' => $row[$i++], //Student Last Name
                'column_c' => $row[$i++], //Student First Name
                'column_d' => $row[$i++], //44993
                'column_e' => $row[$i++], //44994
                'column_f' => $row[$i++], //44995
                'column_g' => $row[$i++], //44996
                'column_h' => $row[$i++], //Percentage of attendance
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    AttendanceEla::insert($t);
                };
                $bulkData = [];
            }
            //AttendanceEla::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            AttendanceEla::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = AttendanceEla::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_attendance_maths($rows,Cycle $cycle) {
        AttendanceMath::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                //$row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                $row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[0], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //SSID
                'column_b' => $row[$i++], //Student Last Name
                'column_c' => $row[$i++], //Student First Name
                'column_d' => $row[$i++], //44993
                'column_e' => $row[$i++], //44994
                'column_f' => $row[$i++], //44995
                'column_g' => $row[$i++], //44996
                'column_h' => $row[$i++], //Percentage of attendance
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    AttendanceMath::insert($t);
                };
                $bulkData = [];
            }
            //AttendanceMath::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            AttendanceMath::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = AttendanceMath::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }


    protected function loadRecords_on_teacher_students($rows,Cycle $cycle) {
        TeacherStudent::removeRecordsOnCurrentCycle($cycle);
        TeacherStudent::clearTeacherIdFromAllTables();
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            $name = "";
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[5] = encrypt($row[5]); //HIPPA & ERPA
                $row[6] = encrypt($row[6]); //HIPPA & ERPA
                $name = encrypt($row[1] . ' ' . $row[0]); //HIPPA & ERPA
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
            }
            $i = 0;
            // All the other tables will be updates via
            // TeacheStudentObserver
            $data = [
                'cycle_id' => $cycle->id, //cicly id
                'created_by' => Auth::id(), //created_by
                'teacher_id' => $row[3],
                'email' => $row[2],
                'name' => $name,
                'students_list' => "",
                'student_id' => $row[8],
                'first_name' => $row[0],
                'last_name' => $row[1],
                'column_d' => $row[3],
                'column_e' => $row[4],
                'column_f' => $row[5],
                'column_g' => $row[6],
                'column_h' => $row[7],
                'column_i' => $row[8],
                'column_j' => $row[9],
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            //TeacherStudent::create($data);
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    TeacherStudent::insert($t);
                };
                $bulkData = [];
            }
            //TeacherStudent::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            TeacherStudent::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = TeacherStudent::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_caaspps($rows,Cycle $cycle) {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        Caaspp::removeRecordsOnCurrentCycle($cycle);

        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[2] = encrypt($row[2]); //HIPPA & ERPA
                $row[3] = encrypt($row[3]); //HIPPA & ERPA
                $row[4] = encrypt($row[4]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[1], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //RecordType
                'column_b' => $row[$i++], //SSID
                'column_c' => $row[$i++], //StudentLastName
                'column_d' => $row[$i++], //StudentFirstName
                'column_e' => $row[$i++], //StudentMiddleName
                'column_f' => $row[$i++], //DateofBirth
                'column_g' => $row[$i++], //Gender
                'column_h' => $row[$i++], //Blank1
                'column_i' => $row[$i++], //Blank2
                'column_j' => $row[$i++], //CALPADSGrade
                'column_k' => $row[$i++], //GradeAssessed
                'column_l' => $row[$i++], //CALPADSDistrictCode
                'column_m' => $row[$i++], //CALPADSDistrictName
                'column_n' => $row[$i++], //CALPADSSchoolCode
                'column_o' => $row[$i++], //CALPADSSchoolName
                'column_p' => $row[$i++], //CALPADSCharterCode
                'column_q' => $row[$i++], //CALPADSCharterSchoolIndicator
                'column_r' => $row[$i++], //SPEDAcctDist
                'column_s' => $row[$i++], //Section504Status
                'column_t' => $row[$i++], //PrimaryDisabilityType
                'column_u' => $row[$i++], //PrimaryDisabilityforTesting
                'column_v' => $row[$i++], //CALPADSIDEAIndicator
                'column_w' => $row[$i++], //IDEAIndicatorforTesting
                'column_x' => $row[$i++], //MigrantStatus
                'column_y' => $row[$i++], //ELStatus
                'column_z' => $row[$i++], //ELEntryDate
                'column_aa' => $row[$i++], //RFEPDate
                'column_ab' => $row[$i++], //FirstEntryDateInUSSchool
                'column_ac' => $row[$i++], //EnrollmentEffectiveDate
                'column_ad' => $row[$i++], //ELAS
                'column_ae' => $row[$i++], //CEDSLanguageCode
                'column_af' => $row[$i++], //CALPADSPrimaryLanguage
                'column_ag' => $row[$i++], //MilitaryStatus
                'column_ah' => $row[$i++], //FosterStatus
                'column_ai' => $row[$i++], //HomelessStatus
                'column_aj' => $row[$i++], //EconomicDisadvantageStatus
                'column_ak' => $row[$i++], //EconomicDisadvantageTesting
                'column_al' => $row[$i++], //CALPADSNPSSchoolFlag
                'column_am' => $row[$i++], //HispanicorLatino
                'column_an' => $row[$i++], //AmericanIndianorAlaskaNative
                'column_ao' => $row[$i++], //Asian
                'column_ap' => $row[$i++], //HawaiianOrOtherPacificIslander
                'column_aq' => $row[$i++], //Filipino
                'column_ar' => $row[$i++], //BlackorAfricanAmerican
                'column_as' => $row[$i++], //White
                'column_at' => $row[$i++], //TwoorMoreRaces
                'column_au' => $row[$i++], //ReportingEthnicity
                'column_av' => $row[$i++], //ParentEducationLevel
                'column_aw' => $row[$i++], //Blank3
                'column_ax' => $row[$i++], //OpportunityID1
                'column_ay' => $row[$i++], //OpportunityTestingStatus1
                'column_az' => $row[$i++], //OpportunityID2
                'column_ba' => $row[$i++], //OpportunityTestingStatus2
                'column_bb' => $row[$i++], //OpportunityID3
                'column_bc' => $row[$i++], //OpportunityTestingStatus3
                'column_bd' => $row[$i++], //OpportunityID4
                'column_be' => $row[$i++], //OpportunityTestingStatus4
                'column_bf' => $row[$i++], //TestRegistrationID
                'column_bg' => $row[$i++], //TestedDistrictName1
                'column_bh' => $row[$i++], //TestedDistrictCode1
                'column_bi' => $row[$i++], //TestedSchoolName1
                'column_bj' => $row[$i++], //TestedSchoolCode1
                'column_bk' => $row[$i++], //TestedCharterSchoolIndicator1
                'column_bl' => $row[$i++], //TestedCharterCode1
                'column_bm' => $row[$i++], //TestedSchoolNPSFlag1
                'column_bn' => $row[$i++], //PaperTestCompletionDate
                'column_bo' => $row[$i++], //TestedDistrictName2
                'column_bp' => $row[$i++], //TestedDistrictCode2
                'column_bq' => $row[$i++], //TestedSchoolName2
                'column_br' => $row[$i++], //TestedSchoolCode2
                'column_bs' => $row[$i++], //TestedCharterSchoolIndicator2
                'column_bt' => $row[$i++], //TestedCharterCode2
                'column_bu' => $row[$i++], //TestedSchoolNPSFlag2
                'column_bv' => $row[$i++], //TestedDistrictName3
                'column_bw' => $row[$i++], //TestedDistrictCode3
                'column_bx' => $row[$i++], //TestedSchoolName3
                'column_by' => $row[$i++], //TestedSchoolCode3
                'column_bz' => $row[$i++], //TestedCharterSchoolIndicator3
                'column_ca' => $row[$i++], //TestedCharterCode3
                'column_cb' => $row[$i++], //TestedSchoolNPSFlag3
                'column_cc' => $row[$i++], //TestedDistrictName4
                'column_cd' => $row[$i++], //TestedDistrictCode4
                'column_ce' => $row[$i++], //TestedSchoolName4
                'column_cf' => $row[$i++], //TestedSchoolCode4
                'column_cg' => $row[$i++], //TestedCharterSchoolIndicator4
                'column_ch' => $row[$i++], //TestedCharterCode4
                'column_ci' => $row[$i++], //TestedSchoolNPSFlag4
                'column_cj' => $row[$i++], //TestStartDate1
                'column_ck' => $row[$i++], //TestCompletedDate1
                'column_cl' => $row[$i++], //TestStartDate2
                'column_cm' => $row[$i++], //TestCompletedDate2
                'column_cn' => $row[$i++], //TestStartDate3
                'column_co' => $row[$i++], //TestCompletedDate3
                'column_cp' => $row[$i++], //TestStartDate4
                'column_cq' => $row[$i++], //TestCompletedDate4
                'column_cr' => $row[$i++], //FinalTestedDistrictName
                'column_cs' => $row[$i++], //FinalTestedDistrictCode
                'column_ct' => $row[$i++], //FinalTestedSchoolName
                'column_cu' => $row[$i++], //FinalTestedSchoolCode
                'column_cv' => $row[$i++], //FinalTestedCharterSchoolIndicator
                'column_cw' => $row[$i++], //FinalTestedCharterCode
                'column_cx' => $row[$i++], //FinalTestedSchoolNPSFlag
                'column_cy' => $row[$i++], //FinalTestCompletedDate
                'column_cz' => $row[$i++], //SchoolStartDateTestWindow1
                'column_da' => $row[$i++], //SchoolEndDateTestWindow1
                'column_db' => $row[$i++], //SchoolStartDateTestWindow2
                'column_dc' => $row[$i++], //SchoolEndDateTestWindow2
                'column_dd' => $row[$i++], //SchoolStartDateTestWindow3
                'column_de' => $row[$i++], //SchoolEndDateTestWindow3
                'column_df' => $row[$i++], //SchoolStartDateTestWindow4
                'column_dg' => $row[$i++], //SchoolEndDateTestWindow4
                'column_dh' => $row[$i++], //StudentExitCode
                'column_di' => $row[$i++], //StudentExitWithdrawalDate
                'column_dj' => $row[$i++], //StudentRemovedCALPADSFileDate
                'column_dk' => $row[$i++], //ELASCorrectionCode
                'column_dl' => $row[$i++], //CASTCurrentYearFlag
                'column_dm' => $row[$i++], //CASTParticipatedHighSchoolGrade
                'column_dn' => $row[$i++], //CASTParticipatedNPSflag
                'column_do' => $row[$i++], //CASTParticipatedDistrictofAccountability
                'column_dp' => $row[$i++], //CASTLastScienceClassFlag
                'column_dq' => $row[$i++], //ConditionCode
                'column_dr' => $row[$i++], //Attemptedness
                'column_ds' => $row[$i++], //ScoreStatus
                'column_dt' => $row[$i++], //UnlistedResourcesConstructChange
                'column_du' => $row[$i++], //TestMode
                'column_dv' => $row[$i++], //IncludeIndicator
                'column_dw' => $row[$i++], //RemoteTester1
                'column_dx' => $row[$i++], //RemoteTester2
                'column_dy' => $row[$i++], //RemoteTester3
                'column_dz' => $row[$i++], //RemoteTester4
                'column_ea' => $row[$i++], //SSREligible
                'column_eb' => $row[$i++], //ScoreAvailableDate
                'column_ec' => $row[$i++], //LexileorQuantileMeasure
                'column_ed' => $row[$i++], //GrowthScore
                'column_ee' => $row[$i++], //Blank4
                'column_ef' => $row[$i++], //RawScore1
                'column_eg' => $row[$i++], //RawScore2
                'column_eh' => $row[$i++], //RawScore3
                'column_ei' => $row[$i++], //RawScore4
                'column_ej' => $row[$i++], //Blank5
                'column_ek' => $row[$i++], //Blank6
                'column_el' => $row[$i++], //Blank7
                'column_em' => $row[$i++], //Blank8
                'column_en' => $row[$i++], //Blank9
                'column_eo' => $row[$i++], //Blank10
                'column_ep' => $row[$i++], //Blank11
                'column_eq' => $row[$i++], //Blank12
                'column_er' => $row[$i++], //ScaleScore
                'column_es' => $row[$i++], //StandardErrorMeasurement
                'column_et' => $row[$i++], //SmarterScaleScoresErrorBandsMin
                'column_eu' => $row[$i++], //SmarterScaleScoresErrorBandsMax
                'column_ev' => $row[$i++], //AchievementLevels
                'column_ew' => $row[$i++], //Domain1Level
                'column_ex' => $row[$i++], //Domain2Level
                'column_ey' => $row[$i++], //Domain3Level
                'column_ez' => $row[$i++], //Genre
                'column_fa' => $row[$i++], //WERPOR
                'column_fb' => $row[$i++], //WERDEVEEL
                'column_fc' => $row[$i++], //WERCOV
                'column_fd' => $row[$i++], //WERPORConditionCode
                'column_fe' => $row[$i++], //WERDEVEELConditionCode
                'column_ff' => $row[$i++], //WERCOVConditionCode
                'column_fg' => $row[$i++], //EAP
                'column_fh' => $row[$i++], //ItemsAttempted1
                'column_fi' => $row[$i++], //ItemsAttempted2
                'column_fj' => $row[$i++], //ItemsAttempted3
                'column_fk' => $row[$i++], //ItemsAttempted4
                'column_fl' => $row[$i++], //AccommodationsIndicator
                'column_fm' => $row[$i++], //DesignatedSupportIndicator
                'column_fn' => $row[$i++], //EAAmericanSignLanguage1
                'column_fo' => $row[$i++], //EAAmericanSignLanguage2
                'column_fp' => $row[$i++], //EAAudioTransScript1
                'column_fq' => $row[$i++], //EAAudioTransScript2
                'column_fr' => $row[$i++], //EABraille1
                'column_fs' => $row[$i++], //EABraille2
                'column_ft' => $row[$i++], //EAClosedCaptioning1
                'column_fu' => $row[$i++], //EAClosedCaptioning2
                'column_fv' => $row[$i++], //EASpeeachtoText1
                'column_fw' => $row[$i++], //EASpeeachtoText2
                'column_fx' => $row[$i++], //EATexttoSpeech1
                'column_fy' => $row[$i++], //EATexttoSpeech2
                'column_fz' => $row[$i++], //NEA100NumberTable1
                'column_ga' => $row[$i++], //NEA100NumberTable2
                'column_gb' => $row[$i++], //NEAAbacus1
                'column_gc' => $row[$i++], //NEAAbacus2
                'column_gd' => $row[$i++], //NEAAbacus3
                'column_ge' => $row[$i++], //NEAAbacus4
                'column_gf' => $row[$i++], //NEASupportsforAltAssessments1
                'column_gg' => $row[$i++], //NEASupportsforAltAssessments2
                'column_gh' => $row[$i++], //NEASupportsforAltAssessments3
                'column_gi' => $row[$i++], //NEASupportsforAltAssessments4
                'column_gj' => $row[$i++], //NEAAltResponseOptions1
                'column_gk' => $row[$i++], //NEAAltResponseOptions2
                'column_gl' => $row[$i++], //NEAAltResponseOptions3
                'column_gm' => $row[$i++], //NEAAltResponseOptions4
                'column_gn' => $row[$i++], //NEABraillePaper
                'column_go' => $row[$i++], //NEACalculator1
                'column_gp' => $row[$i++], //NEACalculator2
                'column_gq' => $row[$i++], //NEALargePrintSpecialPaper
                'column_gr' => $row[$i++], //NEAMultiplicationTable1
                'column_gs' => $row[$i++], //NEAMultiplicationTable2
                'column_gt' => $row[$i++], //NEAPrintonDemand1
                'column_gu' => $row[$i++], //NEAPrintonDemand2
                'column_gv' => $row[$i++], //NEAPrintonDemand3
                'column_gw' => $row[$i++], //NEAPrintonDemand4
                'column_gx' => $row[$i++], //NEAReadAloudPassages1
                'column_gy' => $row[$i++], //NEAScribe1
                'column_gz' => $row[$i++], //NEAScribe2
                'column_ha' => $row[$i++], //NEASpeechtoText1
                'column_hb' => $row[$i++], //NEASpeechtoText2
                'column_hc' => $row[$i++], //NEAUnlistedResources1
                'column_hd' => $row[$i++], //NEAUnlistedResources2
                'column_he' => $row[$i++], //NEAUnlistedResources3
                'column_hf' => $row[$i++], //NEAUnlistedResources4
                'column_hg' => $row[$i++], //NEAWordPrediction1
                'column_hh' => $row[$i++], //NEAWordPrediction2
                'column_hi' => $row[$i++], //EDSColorContrast1
                'column_hj' => $row[$i++], //EDSColorContrast2
                'column_hk' => $row[$i++], //EDSColorContrast3
                'column_hl' => $row[$i++], //EDSColorContrast4
                'column_hm' => $row[$i++], //EDSMasking1
                'column_hn' => $row[$i++], //EDSMasking2
                'column_ho' => $row[$i++], //EDSMasking3
                'column_hp' => $row[$i++], //EDSMasking4
                'column_hq' => $row[$i++], //EDSMousePointer1
                'column_hr' => $row[$i++], //EDSMousePointer2
                'column_hs' => $row[$i++], //EDSMousePointer3
                'column_ht' => $row[$i++], //EDSMousePointer4
                'column_hu' => $row[$i++], //EDSPermissiveMode1
                'column_hv' => $row[$i++], //EDSPermissiveMode2
                'column_hw' => $row[$i++], //EDSPermissiveMode3
                'column_hx' => $row[$i++], //EDSPermissiveMode4
                'column_hy' => $row[$i++], //EDSPrintSize1
                'column_hz' => $row[$i++], //EDSPrintSize2
                'column_ia' => $row[$i++], //EDSPrintSize3
                'column_ib' => $row[$i++], //EDSPrintSize4
                'column_ic' => $row[$i++], //EDSTranslatedTestDirections1
                'column_id' => $row[$i++], //EDSTranslatedTestDirections2
                'column_ie' => $row[$i++], //EDSStreamline1
                'column_if' => $row[$i++], //EDSStreamline2
                'column_ig' => $row[$i++], //EDSStreamline3
                'column_ih' => $row[$i++], //EDSStreamline4
                'column_ii' => $row[$i++], //EDSTexttoSpeech1
                'column_ij' => $row[$i++], //EDSTexttoSpeech2
                'column_ik' => $row[$i++], //EDSTranslations1
                'column_il' => $row[$i++], //EDSTranslations2
                'column_im' => $row[$i++], //EDSTurnoffUniversalTools1
                'column_in' => $row[$i++], //EDSTurnoffUniversalTools2
                'column_io' => $row[$i++], //EDSTurnoffUniversalTools3
                'column_ip' => $row[$i++], //EDSTurnoffUniversalTools4
                'column_iq' => $row[$i++], //NEDS100NumberTable1
                'column_ir' => $row[$i++], //NEDS100NumberTable2
                'column_is' => $row[$i++], //NEDS100NumberTable3
                'column_it' => $row[$i++], //NEDS100NumberTable4
                'column_iu' => $row[$i++], //NEDSAmplification1
                'column_iv' => $row[$i++], //NEDSAmplification2
                'column_iw' => $row[$i++], //NEDSAmplification3
                'column_ix' => $row[$i++], //NEDSAmplification4
                'column_iy' => $row[$i++], //NEDSBilingualDictionary1
                'column_iz' => $row[$i++], //NEDSBilingualDictionary2
                'column_ja' => $row[$i++], //NEDSCalculator1
                'column_jb' => $row[$i++], //NEDSColorContrast1
                'column_jc' => $row[$i++], //NEDSColorContrast2
                'column_jd' => $row[$i++], //NEDSColorContrast3
                'column_je' => $row[$i++], //NEDSColorContrast4
                'column_jf' => $row[$i++], //NEDSColorOverlay1
                'column_jg' => $row[$i++], //NEDSColorOverlay2
                'column_jh' => $row[$i++], //NEDSMagnification1
                'column_ji' => $row[$i++], //NEDSMagnification2
                'column_jj' => $row[$i++], //NEDSMagnification3
                'column_jk' => $row[$i++], //NEDSMagnification4
                'column_jl' => $row[$i++], //NEDSMedicalSupports1
                'column_jm' => $row[$i++], //NEDSMedicalSupports2
                'column_jn' => $row[$i++], //NEDSMedicalSupports3
                'column_jo' => $row[$i++], //NEDSMedicalSupports4
                'column_jp' => $row[$i++], //NEDSMultiplicationTable1
                'column_jq' => $row[$i++], //NEDSMultiplicationTable2
                'column_jr' => $row[$i++], //NEDSMultiplicationTable3
                'column_js' => $row[$i++], //NEDSMultiplicationTable4
                'column_jt' => $row[$i++], //NEDSNoiseBuffers1
                'column_ju' => $row[$i++], //NEDSNoiseBuffers2
                'column_jv' => $row[$i++], //NEDSNoiseBuffers3
                'column_jw' => $row[$i++], //NEDSNoiseBuffers4
                'column_jx' => $row[$i++], //NEDSReadAloudItems1
                'column_jy' => $row[$i++], //NEDSReadAloudItems2
                'column_jz' => $row[$i++], //NEDSReadAloudItems3
                'column_ka' => $row[$i++], //NEDSReadAloudItems4
                'column_kb' => $row[$i++], //NEDSReadAloudinSpanish1
                'column_kc' => $row[$i++], //NEDSReadAloudinSpanish2
                'column_kd' => $row[$i++], //NEDSScienceCharts1
                'column_ke' => $row[$i++], //NEDSScribeItems1
                'column_kf' => $row[$i++], //NEDSScribeItems2
                'column_kg' => $row[$i++], //NEDSScribeItems3
                'column_kh' => $row[$i++], //NEDSScribeItems4
                'column_ki' => $row[$i++], //NEDSSeparateSetting1
                'column_kj' => $row[$i++], //NEDSSeparateSetting2
                'column_kk' => $row[$i++], //NEDSSeparateSetting3
                'column_kl' => $row[$i++], //NEDSSeparateSetting4
                'column_km' => $row[$i++], //NEDSSimplifiedTestDirections1
                'column_kn' => $row[$i++], //NEDSSimplifiedTestDirections2
                'column_ko' => $row[$i++], //NEDSSimplifiedTestDirections3
                'column_kp' => $row[$i++], //NEDSSimplifiedTestDirections4
                'column_kq' => $row[$i++], //NEDSTranslatedTestDirections1
                'column_kr' => $row[$i++], //NEDSTranslatedTestDirections2
                'column_ks' => $row[$i++], //NEDSTranslationsPaper
                'column_kt' => $row[$i++], //SSREligibleMinus1
                'column_ku' => $row[$i++], //GradeAssessedMinus1
                'column_kv' => $row[$i++], //Blank13
                'column_kw' => $row[$i++], //SEMMinus1
                'column_kx' => $row[$i++], //ScaleScoreMinus1
                'column_ky' => $row[$i++], //AchievementLevelMinus1
                'column_kz' => $row[$i++], //ConditionCodeMinus1
                'column_la' => $row[$i++], //Blank14
                'column_lb' => $row[$i++], //Blank15
                'column_lc' => $row[$i++], //Blank16
                'column_ld' => $row[$i++], //Blank17
                'column_le' => $row[$i++], //Blank18
                'column_lf' => $row[$i++], //Blank19
                'column_lg' => $row[$i++], //Blank20
                'column_lh' => $row[$i++], //Blank21
                'column_li' => $row[$i++], //Blank22
                'column_lj' => $row[$i++], //Blank23
                'column_lk' => $row[$i++], //Blank24
                'column_ll' => $row[$i++], //SSREligibleMinus2
                'column_lm' => $row[$i++], //GradeAssessedMinus2
                'column_ln' => $row[$i++], //Blank25
                'column_lo' => $row[$i++], //SEMMinus2
                'column_lp' => $row[$i++], //ScaleScoreMinus2
                'column_lq' => $row[$i++], //AchievementLevelMinus2
                'column_lr' => $row[$i++], //ConditionCodeMinus2
                'column_ls' => $row[$i++], //Blank26
                'column_lt' => $row[$i++], //Blank27
                'column_lu' => $row[$i++], //Blank28
                'column_lv' => $row[$i++], //Blank29
                'column_lw' => $row[$i++], //Blank30
                'column_lx' => $row[$i++], //Blank31
                'column_ly' => $row[$i++], //Blank32
                'column_lz' => $row[$i++], //Blank33
                'column_ma' => $row[$i++], //Blank34
                'column_mb' => $row[$i++], //Blank35
                'column_mc' => $row[$i++], //Blank36
                'column_md' => $row[$i++], //SSREligibleMinus3
                'column_me' => $row[$i++], //GradeAssessedMinus3
                'column_mf' => $row[$i++], //Blank37
                'column_mg' => $row[$i++], //SEMMinus3
                'column_mh' => $row[$i++], //ScaleScoreMinus3
                'column_mi' => $row[$i++], //AchievementLevelMinus3
                'column_mj' => $row[$i++], //ConditionCodeMinus3
                'column_mk' => $row[$i++], //Blank38
                'column_ml' => $row[$i++], //Blank39
                'column_mm' => $row[$i++], //Blank40
                'column_mn' => $row[$i++], //Blank41
                'column_mo' => $row[$i++], //Blank42
                'column_mp' => $row[$i++], //Blank43
                'column_mq' => $row[$i++], //Blank44
                'column_mr' => $row[$i++], //Blank45
                'column_ms' => $row[$i++], //Blank46
                'column_mt' => $row[$i++], //Blank47
                'column_mu' => $row[$i++], //Blank48
                'column_mv' => $row[$i++], //Blank49
                'column_mw' => $row[$i++], //UIN
                'column_mx' => $row[$i++], //Blank50
                'column_my' => $row[$i++], //EndofRecord
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Caaspp::insert($t);
                };
                $bulkData = [];
            }
            //Caaspp::create($data);
            //var_dump($inserts);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            Caaspp::insert($t);
        };
        //dd($bulkData);
        $table = Caaspp::getTableName();
        //dd("here");
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }
    protected function loadRecords_on_elstudents($rows,Cycle $cycle) {
        Elstudent::removeRecordsOnCurrentCycle($cycle);

        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[4] = encrypt($row[4]); //HIPPA & ERPA
                $row[5] = encrypt($row[5]); //HIPPA & ERPA
                $row[18] = encrypt($row[18]); //HIPPA & ERPA
                $row[19] = encrypt($row[19]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[7], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //
                'column_b' => $row[$i++], //
                'column_c' => $row[$i++], //ELD Program Assigned
                'column_d' => $row[$i++], //Long Term EL (LTEL)/        At Risk"
                'column_e' => $row[$i++], //Student Last Name
                'column_f' => $row[$i++], //Student First Name
                'column_g' => $row[$i++], //Grade
                'column_h' => $row[$i++], //SSID
                'column_i' => $row[$i++], //90 (incl. 2 ALT)SOCS =  75  (2 ALT)      SOCS-K = 7        SOCS-S = 8
                'column_j' => $row[$i++], //PLA        VLA        HS        TK- No ADA"
                'column_k' => $row[$i++], //Primary Language:
                'column_l' => $row[$i++], //Local ID
                'column_m' => $row[$i++], //DOB
                'column_n' => $row[$i++], //Gender
                'column_o' => $row[$i++], //Teacher               LAST NAME
                'column_p' => $row[$i++], //Teacher          FIRST NAME
                'column_q' => $row[$i++], //IEP: 13 (incl. 2 ALT) SOCS: 12 SOCS-S: 1 SOCS- K: 0
                'column_r' => $row[$i++], //504
                'column_s' => $row[$i++], //Parent Name
                'column_t' => $row[$i++], //Parent Email
                'column_u' => $row[$i++], //Date/Yr Enrolled US School
                'column_v' => $row[$i++], //AFTER    Apr 15 US             < 1 yr
                'column_w' => $row[$i++], //Add to LIP        (date)
                'column_x' => $row[$i++], //Scale Score Overall 21/22
                'column_y' => $row[$i++], //21/22  Overall        ELPAC Level
                'column_z' => $row[$i++], //2023        SA Date Tested
                'column_aa' => $row[$i++], //22/23        Overall
                'column_ab' => $row[$i++], //22/23 Oral
                'column_ac' => $row[$i++], //22/23 Written
                'column_ad' => $row[$i++], //22/23        ELPAC Level
                'column_ae' => $row[$i++], //Scale Score Overall
                'column_af' => $row[$i++], //Score Diff  Pos/Neg
                'column_ag' => $row[$i++], // Improved  ONE Level
                'column_ah' => $row[$i++], // New / Returning  Student
                'column_ai' => $row[$i++], //Enrollment Date
                'column_aj' => $row[$i++], //RFEP Review (LL)
                'column_ak' => $row[$i++], //At Risk (LL)
                'column_al' => $row[$i++], //Long Term EL (LTEL) (LL)
                'column_am' => $row[$i++], //Alert  Theresa for curriculum
                'column_an' => $row[$i++], //*1 Primary Language
                'column_ao' => $row[$i++], //2  First Language
                'column_ap' => $row[$i++], //*3 Home Language
                'column_aq' => $row[$i++], //4  Spoken by parent to student
                'column_ar' => $row[$i++], //5  Spoken by parent at home
                'column_as' => $row[$i++], //English fluency
                'column_at' => $row[$i++], //17/18
                'column_au' => $row[$i++], //18/19
                'column_av' => $row[$i++], //19/20
                'column_aw' => $row[$i++], //20/21
                'column_ax' => $row[$i++], //21/22
                'column_ay' => $row[$i++], //22/23
                'column_az' => $row[$i++], //23/24
                'column_ba' => $row[$i++], //Overall 16/17
                'column_bb' => $row[$i++], //Overall 17/18
                'column_bc' => $row[$i++], //Overall 18/19
                'column_bd' => $row[$i++], //Overall 19/20
                'column_be' => $row[$i++], //Overall 20/21
                'column_bf' => $row[$i++], //Overall 21/22
                'column_bg' => $row[$i++], //Overall 22/23
                'column_bh' => $row[$i++], //Overall 23/24
                'column_bi' => $row[$i++], //General
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()

            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Elstudent::insert($t);
                };
                $bulkData = [];
            }
            //Elstudent::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            Elstudent::insert($t);
        };
        $table = Elstudent::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_brainpops($rows,Cycle $cycle) {
        Brainpop::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            $name = "";
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[2] = encrypt($row[2]); //HIPPA & ERPA
            }
            $i = 0;
            // All the other tables will be updates via
            // TeacheStudentObserver
            $data = [
                'cycle_id' => $cycle->id, //cicly id
                'created_by' => Auth::id(), //created_by
                'teacher_id' => $row[3],
                'student_id' => $row[1],
                'column_a' => $row[0], //Student_First_Name
                'column_b' => $row[1], //Student ID
                'column_c' => $row[2], //Username
                'column_d' => $row[3], //Date_of_activity_unix
                'column_e' => $row[4], //Date_of_activity
                'column_f' => $row[5], //Topic_Name_or_Name_of_Game/Quiz
                'column_g' => $row[6], //Type_of_Activity
                'column_h' => $row[7], //Website
                'column_i' => $row[8], //Score_(if_any)
                'column_j' => $row[9], //How many lessons
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Brainpop::insert($t);
                };
                $bulkData = [];
            }
            //Brainpop::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            Brainpop::insert($t);
        };
        $table = Brainpop::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_sst_reports($rows,Cycle $cycle) {
        Sstreports::removeRecordsOnCurrentCycle($cycle);
        $bulkData = [];
        $inserts = 0;
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'student_id' => $row[1], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //Student Name
                'column_b' => $row[$i++], //SSID
                'column_c' => $row[$i++], //Type of SST
                'column_d' => $row[$i++], //Date of
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Sstreports::insert($t);
                };
                $bulkData = [];
            }
            //Sstreports::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            Sstreports::insert($t);
        }
        $table = Sstreports::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function loadRecords_on_student_accounts($rows,Cycle $cycle) {
        StudentAccounts::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {

            $name = "";
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                $row[4] = encrypt($row[3]); //HIPPA & ERPA
            }
            $i = 0;
            // special formula for password (column_f)
            // Jun 08 2024
            // Here is the password format.
            // BB060824 FirstNameInital+LastNameInitial+DD+MM+YR
            // (Six digit date of birth)
            $tmp1 = substr(trim(strtoupper($row[0])),0,1); // FirstNameInital
            $tmp2 = substr(trim(strtoupper($row[1])),0,1); // LastNameInitial
            $tmp3 = date("d", strtotime($row[6]));
            $tmp4 = date("m", strtotime($row[6]));
            $tmp5 = date("y", strtotime($row[6]));
            $pass = $tmp1 . $tmp2 . $tmp4 . $tmp3 . $tmp5;
            // All the other tables will be updates via
            // TeacheStudentObserver
            $data = [
                'cycle_id' => $cycle->id, //cicly id
                'created_by' => Auth::id(), //created_by
                'teacher_id' => null,
                'student_id' => $row[2],
                'column_a' => $row[0], // first name
                'column_b' => $row[1], // last name
                'column_c' => $row[2], // student id
                'column_d' => $row[3], // grade
                'column_e' => $row[4], // email
                'column_f' => $pass, // Password
                'column_g' => $row[6], // Date of Birth
                'column_h' => $row[7], // Program
                'column_i' => $row[8], // SIS
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            // if (count($bulkData) == 5) {
            //     //dd($data);
            // }
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $k => $t)
                {
                    foreach ($t as $t1) {
                        if (StudentAccounts::checkIfStudentHasPasswordChangedOnCurrentCycle($cycle->id,$t1['student_id'])) {
                            //dd($t1,$k,$t[$k]);
                            //unset($t[$k]);
                        } else {
                            StudentAccounts::insert($t1);
                        }
                    }

                };
                $bulkData = [];
            }
            //StudentAccounts::create($data);
            //dd($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            StudentAccounts::insert($t);
        };
        $table = StudentAccounts::getTableName();
        return $inserts;
    }

    protected function loadRecords_on_tutor($rows,Cycle $cycle) {
        Tutor::removeRecordsOnCurrentCycle($cycle);
        $inserts = 0;
        $bulkData = [];
        foreach ($rows as $row) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                //$row[0] = encrypt($row[0]); //HIPPA & ERPA
                $row[1] = encrypt($row[1]); //HIPPA & ERPA
                $row[2] = encrypt($row[2]); //HIPPA & ERPA
                $row[3] = encrypt($row[3]); //HIPPA & ERPA
                $row[4] = encrypt($row[4]); //HIPPA & ERPA
                $row[20] = encrypt($row[20]); //HIPPA & ERPA
            }
            $i = 0;
            $data = [
                'created_by' => Auth::id(), //created_by
                'teacher_id' => null,
                'student_id' => $row[20], //student_id
                'cycle_id' => $cycle->id, //student_id
                'column_a' => $row[$i++], //user id
                'column_b' => $row[$i++], //first name
                'column_c' => $row[$i++], //last name
                'column_d' => $row[$i++], //First Name
                'column_e' => $row[$i++], //Last Name
                'column_f' => $row[$i++], //Email
                'column_g' => $row[$i++], //Username
                'column_h' => $row[$i++], //Access Point
                'column_i' => $row[$i++], //Start Date
                'column_j' => $row[$i++], //Total Minutes Used
                'column_k' => $row[$i++], //Minutes Used this period
                'column_l' => $row[$i++], //Total Sessions
                'column_m' => $row[$i++], //Sessions this period
                'column_n' => $row[$i++], //Total Early Alerts
                'column_o' => $row[$i++], //Early Alerts this period
                'column_p' => $row[$i++], //Subjects
                'column_q' => $row[$i++], //Total Minutes Used
                'column_r' => $row[$i++], //Minutes Used this period
                'column_s' => $row[$i++], //Total Sessions
                'column_t' => $row[$i++], //Sessions this period
                'column_u' => $row[$i++], //Total Early Alerts
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            $bulkData[] = $data;
            if (count($bulkData) == 500) {
                foreach (array_chunk($bulkData,100) as $t)
                {
                    Tutor::insert($t);
                };
                $bulkData = [];
            }
            //AttendanceMath::create($data);
            $inserts++;
        }
        foreach (array_chunk($bulkData,100) as $t)
        {
            Tutor::insert($t);
        };
        //$table = EasyCBMFall::getTableName();
        $table = Tutor::getTableName();
        $this->reprocessTeacherStudentForATable($table,$cycle);
        return $inserts;
    }

    protected function removeFileAndUpdateProcess(FileUploads $fileToUploadInfo,$fileName) {
        $fileToUploadInfo->status = 1;
        $fileToUploadInfo->processed_on = Carbon::now()->toDateTimeString();
        $fileToUploadInfo->save();
        unlink($fileName);
        $path = storage_path('app/');
        $filePath = $path . $fileToUploadInfo->file_path ;
        if (\File::exists($filePath)) \File::deleteDirectory($filePath);

    }

    protected function consolidateFiles() {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        $cycle = Cycle::getCurrentCycle();
        // step 1 find student Id for all students on missing files
        //FreckleMinutes::updateStudentIDFromStudentList($cycle);
        // $inserted = 943;
        // TeacherStudent::reprocessTeacherStudentForAllTables();
        // return $inserted;

        Consolidate3::removeRecordsOnCurrentCycle($cycle); // remove all the records for that cycle

        //$students = StudentList::getAllRecordsOnCycle($cycle);
        $students = StudentAccounts::getAllRecordsOnCycle($cycle);

        //dd($students);
        $inserted = 0;
        if (!$students) {
            return false;
        }
        foreach ($students as $student) {
            $allTables = $this->getAllRowsFromAllTabsByStudent($cycle,$student);
            //dd($allTables);
            //$this->createConsolidatedRecord($student,$cycle,$allTables);
            $this->createConsolidatedV3Record($student,$cycle,$allTables);

            $inserted++;
            //dd($data);
        }
        // step 2 find student Id for all math-lists

        $students = MathList::getAllRecordsOnCycle($cycle);
        if (!$students) {
            // now return false because the student_account is principal now
            //return false;
        } else {
            foreach ($students as $student) {
                if (!Consolidate3::checkIfStudentAlreadyExistsOnCycle($cycle,$student)) {
                    $allTables = $this->getAllRowsFromAllTabsByStudent($cycle,$student);
                    //$this->createConsolidatedRecord($student,$cycle,$allTables);
                    $this->createConsolidatedV3Record($student,$cycle,$allTables);
                    $inserted++;
                }

                //dd($data);
            }
        }
        // step 3 find student Id for all student_accounts

        $students = StudentList::getAllRecordsOnCycle($cycle);
        if (!$students) {
            // now return false because the student_account is principal now
            // return false;
        } else {

            foreach ($students as $student) {
                if (!Consolidate3::checkIfStudentAlreadyExistsOnCycle($cycle,$student)) {
                    $allTables = $this->getAllRowsFromAllTabsByStudent($cycle,$student);
                    //$this->createConsolidatedRecord($student,$cycle,$allTables);
                    $this->createConsolidatedV3Record($student,$cycle,$allTables);
                    $inserted++;
                }
                //dd($data);
            }
        }

        TeacherStudent::reprocessTeacherStudentForAllTables2();
        return $inserted;
    }




    // Here is where the consolidated process happens
    protected function createConsolidatedV3Record($student,$cycle,$allTables) {

        extract($allTables);
        $tmp = JMHelper::JMGetValues($i_ready_math_eoy_s,'column_ac','i_ready_math_eoy_s');
        //dd($allTables);
        // if ($student->student_id != 9924616686) {
        //     return;
        // }
        //dd($allTables,$teacherStudent);
        $data = [
            'created_by' => Auth::id(), //created_by
            'student_id' => $student->student_id, //student id
            'cycle_id' => $cycle->id,
            'column_a' => $student->student_id, //Student ID
            'column_b' => JMHelper::JMGetValues($student_accounts,'column_b','student_accounts'),
            'column_c' => JMHelper::JMGetValues($student_accounts,'column_a','student_accounts'),
            'column_d' => JMHelper::JMGetValues($student_accounts,'column_d','student_accounts'),
            'column_e' => JMHelper::JMGetValues($student_accounts,'column_i','student_accounts'),
            'column_f' => JMHelper::JMGetValues($student_list,'column_f','student_list'),
            //'column_f' => $student->column_f,
            //'column_g' => JMHelper::JMGetMultipleValues($teacherStudent,'first_name,last_name','teacher_student'),
            'column_g' => JMHelper::getTeacherName($teacherStudent),
            'column_h' => JMHelper::JMGetValues($math_lists,'column_f','math_lists'),
            'column_i' => JMHelper::JMGetValues($student_list,'column_j','student_list'),
            //'column_i' => $student->column_j,
            'column_j' => JMHelper::JMGetValues($math_lists,'column_j','math_lists'),
            'column_k' => JMHelper::JMGetValues($student_list,'column_o','student_list'),
            //'column_k' => $student->column_o,
            'column_l' => JMHelper::JMGetValues($math_lists,'column_o','math_lists'),
            //
            'column_m' => JMHelper::JMGetValues($i_ready_math_boys,'column_ae','i_ready_math_boys'), //IREADY POINTS MATH FALL
            'column_n' => JMHelper::JMGetValues($i_ready_math_boys,'column_ag','i_ready_math_boys'), //IREADY POINTS MATH FALL
            'column_o' => JMHelper::JMGetValues($i_ready_math_boys,'column_af','i_ready_math_boys'), //IREADY POINTS MATH FALL
            //

            'column_p' => JMHelper::JMGetValues($i_ready_reading_boy_s,'column_ae','i_ready_reading_boy_s'), //IREADY POINTS MATH FALL
            'column_q' => JMHelper::JMGetValues($i_ready_reading_boy_s,'column_ag','i_ready_reading_boy_s'), //IREADY POINTS MATH FALL
            'column_r' => JMHelper::JMGetValues($i_ready_reading_boy_s,'column_af','i_ready_reading_boy_s'), //IREADY POINTS MATH FALL
            //
            'column_s' => JMHelper::JMGetValues($i_ready_math_mid_years,'column_ac','i_ready_math_mid_years'), //IREADY POINTS MATH FALL
            'column_t' => JMHelper::JMGetValues($i_ready_math_mid_years,'column_ae','i_ready_math_mid_years'), //IREADY POINTS MATH FALL
            'column_u' => JMHelper::JMGetValues($i_ready_math_mid_years,'column_ad','i_ready_math_mid_years'), //IREADY POINTS MATH FALL
            //
            'column_v' => JMHelper::JMGetValues($i_ready_reading_mid_years,'column_ac','i_ready_reading_mid_years'), //IREADY POINTS MATH FALL
            'column_w' => JMHelper::JMGetValues($i_ready_reading_mid_years,'column_ae','i_ready_reading_mid_years'), //IREADY POINTS MATH FALL
            'column_x' => JMHelper::JMGetValues($i_ready_reading_mid_years,'column_ad','i_ready_reading_mid_years'), //IREADY POINTS MATH FALL
            //
            'column_y'  => JMHelper::JMGetValues($i_ready_math_eoy_s,'column_ac','i_ready_math_eoy_s'), //IREADY POINTS MATH FALL
            'column_z'  => JMHelper::JMGetValues($i_ready_math_eoy_s,'column_ae','i_ready_math_eoy_s'), //IREADY POINTS MATH FALL
            'column_aa' => JMHelper::JMGetValues($i_ready_math_eoy_s,'column_ad','i_ready_math_eoy_s'), //IREADY POINTS MATH FALL
            //
            'column_ab' => JMHelper::JMGetValues($i_ready_reading_eoy_s,'column_ac','i_ready_reading_eoy_s'), //IREADY POINTS MATH FALL
            'column_ac' => JMHelper::JMGetValues($i_ready_reading_eoy_s,'column_ae','i_ready_reading_eoy_s'), //IREADY POINTS MATH FALL
            'column_ad' => JMHelper::JMGetValues($i_ready_reading_eoy_s,'column_ad','i_ready_reading_eoy_s'), //IREADY POINTS MATH FALL
            //
            'column_ae' => '',
            'column_af' => '',
            'column_ag' => '',
            'column_ah' => '',
            'column_ai' => '',
            'column_aj' => '',
            'column_ak' => '',
            'column_al' => '',
            //
            'column_am' => JMHelper::JMGetValues($easy_cbm_falls,'column_ag','easy_cbm_falls'), //FLUENCY Percentile
            'column_an' => JMHelper::JMGetValues($easy_cbm_falls,'column_am','easy_cbm_falls'), //FLUENCY Percentile
            'column_ao' => JMHelper::JMGetValues($easy_cbm_falls,'column_ac','easy_cbm_falls'), //FLUENCY Percentile
            'column_ap' => JMHelper::JMGetValues($easy_cbm_falls,'column_w', 'easy_cbm_falls'), //FLUENCY Percentile
            'column_aq' => JMHelper::JMGetValues($easy_cbm_falls,'column_z', 'easy_cbm_falls'), //FLUENCY Percentile
            'column_ar' => JMHelper::JMGetValues($easy_cbm_falls,'column_ap','easy_cbm_falls'), //FLUENCY Percentile
            'column_as' => JMHelper::JMGetValues($easy_cbm_falls,'column_aj','easy_cbm_falls'), //FLUENCY Percentile
            'column_at' => JMHelper::JMGetValues($easy_cbm_falls,'column_as','easy_cbm_falls'), //FLUENCY Percentile
            'column_au' => JMHelper::JMGetValues($easy_cbm_falls,'column_aw','easy_cbm_falls'), //FLUENCY Percentile
            'column_av' => JMHelper::JMGetValues($easy_cbm_falls,'column_at','easy_cbm_falls'), //FLUENCY Percentile
            //
            'column_aw' => JMHelper::JMGetValues($easy_cbm_progmons,'column_s','easy_cbm_progmons'), //Progress Monitoring Test Given
            'column_ax' => JMHelper::JMGetValues($easy_cbm_progmons,'column_w','easy_cbm_progmons'), //Progress Monitoring Accuracy Percentile
            //
            'column_ay' => JMHelper::JMGetValues($star_fall_maths,'column_j','star_fall_maths'), //Progress Monitoring Accuracy Percentile
            'column_az' => JMHelper::JMGetValues($star_fall_readings,'column_j','star_fall_readings'), //Progress Monitoring Accuracy Percentile
            //
            'column_ba' => '',
            'column_bb' => '',
            'column_bc' => '',
            'column_bd' => '',
            'column_be' => '',
            'column_bf' => '',
            'column_bg' => '',
            'column_bh' => '',
            //
            'column_bi' => JMHelper::JMGetValues($attendance_elas,'column_h','attendance_elas') . ', ' . JMHelper::JMGetValues($attendance_maths,'column_h','attendance_elas'),
            //
            'column_bj' => JMHelper::JMGetValues($i_ready_math_minutes ,'column_cn','i_ready_math_minutes'),
            'column_bk' => JMHelper::JMGetValues($i_ready_reading_minutes ,'column_cn','i_ready_reading_minutes'),
            //
            'column_bl' => JMHelper::JMGetValues($freckle_minutes,'column_i','freckle_minutes'), //FRECKLE MINUTES MATH
            'column_bm' => JMHelper::JMGetValues($freckle_minutes,'column_j','freckle_minutes'), //FRECKLE MINUTES READING
            //
            'column_bn' => JMHelper::JMGetValues($read180_minutes,'column_h','read180_minutes'), //Read 180 Minutes
            'column_bo' => '',
            //
            'column_bp' => JMHelper::JMGetValues($math180_minutes,'column_h','math180_minutes'), //Read 180 Minutes
            //
            'column_bq' => (float)$student->column_z + (float)JMHelper::JMGetValues($math_lists,'column_z','math_lists'), //CLASS INFO
            'column_br' => (float)$student->column_aa + (float)JMHelper::JMGetValues($math_lists,'column_aa','math_lists'), //Notes
            //
            'column_bs' => JMHelper::JMGetValues($trans_math_minutes,'column_h','trans_math_minutes'), //transmath minutes
            'column_bt' => JMHelper::JMGetValues($sst_reports,'column_c','sst_reports'), //SST
            'column_bu' => JMHelper::JMGetValues($student_list,'column_l','student_list') . " - " . JMHelper::JMGetValues($math_lists,'column_l','math_lists'), //sped
            //
            'column_bv' => JMHelper::JMGetValues($elstudents,'column_ad','elstudents'),
            'column_bw' => '',

        ];
        $data['column_ae'] = JMHelper::JMCalculate($data,'column_s','column_m','-',$i_ready_math_mid_years,$i_ready_math_boys); //IREADY GROWTH POINTS MATH MID YEAR,
        $data['column_af'] = JMHelper::JMCalculate($data,'column_u','column_o','-',$i_ready_math_mid_years,$i_ready_math_boys); //IREADY GROWTH POINTS MATH MID YEAR,
        $data['column_ag'] = JMHelper::JMCalculate($data,'column_v','column_p','-',$i_ready_reading_mid_years,$i_ready_reading_boy_s); //IREADY GROWTH POINTS MATH MID YEAR,
        $data['column_ah'] = JMHelper::JMCalculate($data,'column_x','column_r','-',$i_ready_reading_mid_years,$i_ready_reading_boy_s); //IREADY GROWTH POINTS MATH MID YEAR,
        $data['column_ai'] = JMHelper::JMCalculate($data,'column_y','column_m','-',$i_ready_math_eoy_s,$i_ready_math_boys); //IREADY GROWTH POINTS MATH MID YEAR,
        $data['column_aj'] = JMHelper::JMCalculate($data,'column_aa','column_o','-',$i_ready_math_eoy_s,$i_ready_math_boys); //IREADY GROWTH POINTS MATH MID YEAR,
        $data['column_ak'] = JMHelper::JMCalculate($data,'column_ab','column_p','-',$i_ready_reading_eoy_s,$i_ready_reading_boy_s); //IREADY GROWTH POINTS MATH MID YEAR,
        $data['column_al'] = JMHelper::JMCalculate($data,'column_ad','column_r','-',$i_ready_reading_eoy_s,$i_ready_reading_boy_s); //IREADY GROWTH POINTS MATH MID YEAR,
        //dd($data,$allTables);
        Consolidate3::create($data);
    }



    protected function getAllRowsFromAllTabsByStudent(Cycle $cycle, $student) {


        //$attendances = Attendance::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $student_list = StudentList::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $student_accounts = StudentAccounts::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $attendance_elas = AttendanceEla::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $attendance_maths = AttendanceMath::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $easy_cbm_falls = EasyCBMFall::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $easy_cbm_progmons = EasyCBMProgMon::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $freckle_minutes = FreckleMinutes::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $math_lists = MathList::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_math_boys = IReadyMathBOY::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_math_eoy_s = IReadyMathEOY::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_math_mid_years = IReadyMathMidYear::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_reading_boy_s = IReadyReadingBOY::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_reading_eoy_s = IReadyReadingEOY::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_reading_mid_years = IReadyReadingMidYear::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $math180_minutes = Math180Minutes::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $read180_minutes = Read180Minutes::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        //$sheet15s = Sheet15::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $star_eoy_maths = StarEOYMath::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $star_eoy_readings = StarEOYReading::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $star_fall_maths = StarFallMath::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $star_fall_readings = StarFallReading::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $star_mid_year_maths = StarMidYearMath::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $star_mid_year_readings = StarMidYearReading::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $trans_math_minutes = TransMathMinutes::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_math_minutes = IReadyMathMinutes::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $i_ready_reading_minutes = IReadyReadingMinutes::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $v_math_minutes = VMathMinutes::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $caaspps = Caaspp::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id,false);
        $caasppsMath = Caaspp::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id,"02");
        $caasppsReading = Caaspp::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id,"01");
        $elstudents = Elstudent::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $sst_reports = Sstreports::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $brainpops = Brainpop::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $tutor = Tutor::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $teacherStudent = TeacherStudent::getAllRecordsByStudentIDOnCycle($cycle,$student->student_id);
        $return = compact(
            'student',
            'student_list',
            'student_accounts',
            //'attendances',
            'easy_cbm_falls',
            'easy_cbm_progmons',
            'freckle_minutes',
            'math_lists',
            'attendance_elas',
            'attendance_maths',
            'i_ready_math_boys',
            'i_ready_math_eoy_s',
            'i_ready_math_mid_years',
            'i_ready_reading_boy_s',
            'i_ready_reading_eoy_s',
            'i_ready_reading_mid_years',
            'math180_minutes',
            'read180_minutes',
            'star_eoy_maths',
            'star_eoy_readings',
            'star_fall_maths',
            'star_fall_readings',
            'star_mid_year_maths',
            'star_mid_year_readings',
            'trans_math_minutes',
            'i_ready_math_minutes',
            'i_ready_reading_minutes',
            'v_math_minutes',
            'caaspps',
            'caasppsMath',
            'caasppsReading',
            'elstudents',
            'sst_reports',
            'brainpops',
            'tutor',
            'teacherStudent',
        );
        return $return;
    }

    protected function generateReport($id,$overrideCycle=null) {
        if (!\Auth::user()->isAdmin()) {
            $overrideCycle=null;
        }
        $consolidate = Consolidate3::find($id);
        if (!$overrideCycle) {
            $cycle = Cycle::getCurrentCycle();
        } else {
            $cycle = Cycle::where('id',$overrideCycle)->first();
        }


        if (!$cycle || !$consolidate ) {
            return false;
        }
        $student = StudentList::getAllRecordsByStudentIDOnCycle($cycle,$consolidate->student_id);
        $isValid = 0;
        if (!$student ) {
            $student = MathList::getAllRecordsByStudentIDOnCycle($cycle,$consolidate->student_id);
            if (!$student ) {
                $student = StudentAccounts::getAllRecordsByStudentIDOnCycle($cycle,$consolidate->student_id);
                if (!$student ) {
                    $isValid = 0;
                } else {
                    $isValid = 1;
                }
            } else {
                $isValid = 1;
            }
        } else {
            $isValid = 1;
        }
        if ($isValid == 0 ) {
            return false;
        }
        $student = $student[0];
        $student_list = $student[0];

        $allTables = FileUploads::getAllRowsFromAllTabsByStudent($cycle,$student,$consolidate);

        return [$allTables,$consolidate];

    }

    protected function purgeUploadedFiles() {
        // this purge happend all Sundays
        if(date('D') == 'Sun') {
            $data = [
                'status' => 2  // inactived, uploaded but never used
            ];
            $this->where('status',0)
                ->update($data);
        }
    }

    protected function reprocessTeacherStudentForATable($table,$cycle) {
        $teachersStudents = [];
        //$teachersStudents = TeacherStudent::select('student_id')->get()->keyBy('teacher_id')->toArray();
        $rows = TeacherStudent::orderBy('teacher_id')
                    ->orderBy('student_id')
                    ->get(['student_id','teacher_id']);
        foreach ($rows as $row) {
            $teachersStudents[$row->teacher_id][] = $row->student_id;
        }
        Log::info($table);
        DB::disableQueryLog();
        foreach ($teachersStudents as $teacherId => $teachersStudent) {
            foreach ($teachersStudent as $studentID) {
                //echo ($teacherId . " -> " . $studentID) . "<br>";
                $myTable = $table;
                DB::table($myTable)->select(['id'])
                    ->where('cycle_id',$cycle->id)
                    ->where('student_id',$studentID)
                    ->whereNull('teacher_id')
                    ->chunkById(100, function ($rows) use($teacherId,$myTable) {
                        //dd($rows);
                        foreach($rows as $row) {
                            DB::table($myTable)
                                ->where('id',$row->id)
                                ->update([
                                    'teacher_id' => $teacherId
                                ]);
                        }
                        unset($rows);
                    });

                //$myModel::UpdateTeacherIdToItsStudents($teacherId,$studentID,$cycle);
            }
        }
        Log::info("Completed -> " . $table);
        Log::info('MEMORY USAGE: '.memory_get_usage(true) );
    }
}
