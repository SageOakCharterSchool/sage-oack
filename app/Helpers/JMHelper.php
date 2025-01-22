<?php


namespace App\Helpers;

use App\Models\Consolidate3;
use Illuminate\Http\Request;


class JMHelper
{
    public static function JMEncrypt($var)
    {
        if (getenv("APP_ENV") == "PROD") {
            return encrypt($var); //HIPPA & ERPA
        } else {
            return $var; //HIPPA & ERPA
        }
    }
    public static function JMDecrypt($var)
    {
        if (getenv("APP_ENV") == "PROD") {
            return decrypt($var); //HIPPA & ERPA
        } else {
            return $var; //HIPPA & ERPA
        }
    }
    public static function JMCalculate($row, $column1, $column2, $operation, $source1, $source2)
    {
        $return = 0;
        if ($row) {
            if ($operation == "-") {
                //var_dump($source1,$source2);
                if (!$source1 || !$source2) {
                    return 0;
                }
                $return = (float)$row[$column1] - (float)$row[$column2];
            }
        }
        return $return;
    }
    public static function JMGetValues($rows, $column, $modelName)
    {
        $return = "";

        if ($rows && $rows->isNotEmpty()) {
            if (count($rows) > 1) {
                foreach ($rows as $row) {
                    if (!empty($row->{$column})) {
                        //$return =   $row->{$column} ."<br>";
                        $return =   $row->{$column};
                    }
                }
            } else {
                foreach ($rows as $row) {

                    if (!empty($row->{$column})) {
                        $return = $row->{$column};
                    }
                }
            }
        }
        $return = trim($return);
        //dd(Consolidate3::columnACADEquivalences());
        if (
            $modelName == "i_ready_math_boys" ||
            $modelName == "i_ready_math_eoy_s" ||
            $modelName == "i_ready_math_mid_years" ||
            $modelName == "i_ready_reading_boy_s" ||
            $modelName == "i_ready_reading_eoy_s" ||
            $modelName == "i_ready_reading_mid_years"
        ) {
            if ($column == "column_ad") {
                //var_dump($row['id'],$column,$modelName,$return);
                if ($return) {
                    $tmp = Consolidate3::columnACADEquivalences();

                    if (isset($tmp[$return])) {
                        $return = Consolidate3::columnACADEquivalences()[$return];
                    } else {

                        $return = 0;
                    }
                } else {

                    $return = 0;
                }
            }
        }


        return $return;
    }

    public static function getTeacherName($teacherStudent) {
        if (!$teacherStudent) {
            return '';
        }
        if (!isset($teacherStudent[0])) {
            return '';
        }
        return $teacherStudent[0]->first_name . ' ' . $teacherStudent[0]->last_name;
    }

    public static function JMGetMultipleValues($rows, $column, $modelName)
    {
        $return = "";
        $columns = explode(",", $column);
        if (empty($columns)) {
            return "";
        }
        //dd($rows);
        if ($rows && $rows->isNotEmpty()) {
            //
            if (count($rows) > 1) {
                //dd("here");
                foreach ($rows as $row) {
                    foreach ($columns as $colToGet) {
                        //dd($row,$colToGet);
                        if (!empty($row->{$colToGet})) {
                            $return .=  $row->{$colToGet} . " ";
                        }
                    }
                }
            } else {
                foreach ($rows as $row) {
                    //dd($row);
                    foreach ($columns as $colToGet) {
                        //dd($row,$colToGet,$row->{$colToGet});
                        if (!empty($row->{$colToGet})) {
                            $return .= $row->{$colToGet} . ' ';
                        }
                    }
                }
            }
        }
        $return = trim($return);
        //dd(Consolidate3::columnACADEquivalences());
        if (
            $modelName == "i_ready_math_boys" ||
            $modelName == "i_ready_math_eoy_s" ||
            $modelName == "i_ready_math_mid_years" ||
            $modelName == "i_ready_reading_boy_s" ||
            $modelName == "i_ready_reading_eoy_s" ||
            $modelName == "i_ready_reading_mid_years"
        ) {
            if ($column == "column_ad") {
                //var_dump($row['id'],$column,$modelName,$return);
                if ($return) {
                    $tmp = Consolidate3::columnACADEquivalences();

                    if (isset($tmp[$return])) {
                        $return = Consolidate3::columnACADEquivalences()[$return];
                    } else {

                        $return = 0;
                    }
                } else {

                    $return = 0;
                }
            }
        }


        return $return;
    }

    public static function JMGetFieldAnalysis($field, $tableValues, $consolidate)
    {
        // dd($tableValues);
        switch ($field->Field) {
            case "column_a":
                $return = [
                    'consolidated',
                    $consolidate->student_id,
                ];
                return $return;
                break;
            case "column_b":
                //var_dump($field);
                if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING") == "Y") {
                    $tmp = decrypt($tableValues['student']->column_a);
                } else {
                    $tmp = ($tableValues['student']->column_a);
                }
                $return = [
                    'student_list',
                    $tmp
                ];
                return $return;
                break;
            case "column_c":
                if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING") == "Y") {
                    $tmp = decrypt($tableValues['student']->column_b);
                } else {
                    $tmp = ($tableValues['student']->column_b);
                }
                $return = [
                    'student_list',
                    $tmp
                ];
                return $return;
                break;
            case "column_d":
                $return = [
                    'student_list',
                    ($tableValues['student']->column_d)
                ];
                return $return;
                break;
            case "column_e":
                $return = [
                    'student_list',
                    ($tableValues['student']->column_e)
                ];
                return $return;
                break;
            case "column_f":
                $return = [
                    'student_list',
                    ($tableValues['student']->column_f)
                ];
                return $return;
                break;
            case "column_g":
                $return = [
                    'student_list',
                    ($tableValues['student']->column_g)
                ];
                return $return;
                break;
            case "column_h":
                $return = [
                    'math_lists',
                    ($tableValues['math_lists'][0]->column_f ?? "")
                ];
                return $return;
                break;
            case "column_i":
                $return = [
                    'student_list',
                    ($tableValues['student']->column_j)
                ];
                return $return;
                break;
            case "column_j":
                $return = [
                    'math_lists',
                    ($tableValues['math_lists'][0]->column_j ?? "")
                ];
                return $return;
                break;
            case "column_k":
                $return = [
                    'student_list',
                    ($tableValues['student']->column_o)
                ];
                return $return;
                break;
            case "column_l":
                $return = [
                    'math_lists',
                    ($tableValues['math_lists'][0]->column_o ?? "")
                ];
                return $return;
                break;
            case "column_m":
                $return = [
                    'i_ready_math_boys',
                    ($tableValues['i_ready_math_boys'][0]->column_ab ?? "")
                ];
                return $return;
                break;
            case "column_n":
                $return = [
                    'i_ready_math_boys',
                    ($tableValues['i_ready_math_boys'][0]->column_ae ?? "")
                ];
                return $return;
                break;
            case "column_o":
                $return = [
                    'i_ready_math_boys',
                    ($tableValues['i_ready_math_boys'][0]->column_ac ?? "")
                ];
                return $return;
                break;
                //
            case "column_p":
                $return = [
                    'i_ready_reading_boy_s',
                    ($tableValues['i_ready_reading_boy_s'][0]->column_ab ?? '')
                ];
                return $return;
                break;
            case "column_q":
                $return = [
                    'i_ready_reading_boy_s',
                    ($tableValues['i_ready_reading_boy_s'][0]->column_ae ?? '')
                ];
                return $return;
                break;
            case "column_r":
                $return = [
                    'i_ready_reading_boy_s',
                    ($tableValues['i_ready_reading_boy_s'][0]->column_ac ?? '')
                ];
                return $return;
                break;
                //
            case "column_s":
                $return = [
                    'i_ready_math_mid_years',
                    ($tableValues['i_ready_math_mid_years'][0]->column_ab ?? '')
                ];
                return $return;
                break;
            case "column_t":
                $return = [
                    'i_ready_math_mid_years',
                    ($tableValues['i_ready_math_mid_years'][0]->column_ae ?? '')
                ];
                return $return;
                break;
            case "column_u":
                $return = [
                    'i_ready_math_mid_years',
                    ($tableValues['i_ready_math_mid_years'][0]->column_ac ?? '')
                ];
                return $return;
                break;
                //
            case "column_v":
                $return = [
                    'i_ready_reading_mid_years',
                    ($tableValues['i_ready_reading_mid_years'][0]->column_ab ?? '')
                ];
                return $return;
                break;
            case "column_w":
                $return = [
                    'i_ready_reading_mid_years',
                    ($tableValues['i_ready_reading_mid_years'][0]->column_ae ?? '')
                ];
                return $return;
                break;
            case "column_x":
                $return = [
                    'i_ready_reading_mid_years',
                    ($tableValues['i_ready_reading_mid_years'][0]->column_ac ?? '')
                ];
                return $return;
                break;
                //
            case "column_y":
                $return = [
                    'i_ready_math_eoy_s',
                    ($tableValues['i_ready_math_eoy_s'][0]->column_ab ?? '')
                ];
                return $return;
                break;
            case "column_z":
                $return = [
                    'i_ready_math_eoy_s',
                    ($tableValues['i_ready_math_eoy_s'][0]->column_ae ?? '')
                ];
                return $return;
                break;
            case "column_aa":
                $return = [
                    'i_ready_math_eoy_s',
                    ($tableValues['i_ready_math_eoy_s'][0]->column_ac ?? '')
                ];
                return $return;
                break;
                //
            case "column_ab":
                $return = [
                    'i_ready_reading_eoy_s',
                    ($tableValues['i_ready_reading_eoy_s'][0]->column_ab ?? '')
                ];
                return $return;
                break;
            case "column_ac":
                $return = [
                    'i_ready_reading_eoy_s',
                    ($tableValues['i_ready_reading_eoy_s'][0]->column_ae ?? '')
                ];
                return $return;
                break;
            case "column_ad":
                $return = [
                    'i_ready_reading_eoy_s',
                    ($tableValues['i_ready_reading_eoy_s'][0]->column_ac ?? '')
                ];
                return $return;
                break;
                // dd($tableValues);
            case "column_ae":
                $return = [
                    'consolidated S-M',
                    "Column S -> " . ($consolidate->column_s),
                    "Column M -> " . ($consolidate->column_m),
                    "Column S - Column M ->  "  . ((float)$consolidate->column_s - (float)$consolidate->column_m)
                ];
                return $return;
                break;
            case "column_af":
                $return = [
                    'consolidated U-O',
                    "Column U -> " . ($consolidate->column_u),
                    "Column O -> " . ($consolidate->column_o),
                    "Column U - Column O ->  "  . ((float)$consolidate->column_u - (float)$consolidate->column_o)
                ];
                return $return;
                break;
            case "column_ag":
                $return = [
                    'consolidated V-P',
                    "Column V -> " . ($consolidate->column_v),
                    "Column P -> " . ($consolidate->column_p),
                    "Column V - Column P ->  "  . ((float)$consolidate->column_v - (float)$consolidate->column_p)
                ];
                return $return;
                break;
            case "column_ah":
                $return = [
                    'consolidated X-R',
                    "Column X -> " . ($consolidate->column_x),
                    "Column R -> " . ($consolidate->column_r),
                    "Column X - Column R ->  "  . ((float)$consolidate->column_x - (float)$consolidate->column_r)
                ];
                return $return;
                break;
            case "column_ai":
                $return = [
                    'consolidated Y-M',
                    "Column Y -> " . ($consolidate->column_y),
                    "Column M -> " . ($consolidate->column_m),
                    "Column Y - Column M ->  "  . ((float)$consolidate->column_y - (float)$consolidate->column_m)
                ];
                return $return;
                break;
            case "column_aj":
                $return = [
                    'consolidated AA-O',
                    "Column AA -> " . ($consolidate->column_aa),
                    "Column O -> " . ($consolidate->column_o),
                    "Column AA - Column O ->  "  . ((float)$consolidate->column_aa - (float)$consolidate->column_o)
                ];
                return $return;
                break;
            case "column_ak":
                $return = [
                    'consolidated AB-P',
                    "Column AB -> " . ($consolidate->column_ab),
                    "Column P -> " . ($consolidate->column_p),
                    "Column AB - Column P ->  "  . ((float)$consolidate->column_ab - (float)$consolidate->column_p)
                ];
                return $return;
                break;
            case "column_al":
                $return = [
                    'consolidated AD-R',
                    "Column AD -> " . ($consolidate->column_ad),
                    "Column R -> " . ($consolidate->column_r),
                    "Column AD - Column R ->  "  . ((float)$consolidate->column_ad - (float)$consolidate->column_r)
                ];
                return $return;
                break;
            case "column_am":
                // dd($tableValues);
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_ad ?? '')
                ];
                return $return;
                break;
            case "column_an":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_aj ?? '')
                ];
                return $return;
                break;
            case "column_ao":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_z ?? '')
                ];
                return $return;
                break;
            case "column_ap":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_t ?? '')
                ];
                return $return;
                break;
            case "column_aq":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_w ?? '')
                ];
                return $return;
                break;
            case "column_ar":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_am ?? '')
                ];
                return $return;
                break;
            case "column_as":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_ag ?? '')
                ];
                return $return;
                break;
            case "column_at":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_as ?? '')
                ];
                return $return;
                break;
            case "column_au":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_ap ?? '')
                ];
                return $return;
                break;
            case "column_av":
                $return = [
                    'easy_cbm_falls',
                    ($tableValues['easy_cbm_falls'][0]->column_at ?? '')
                ];
                return $return;
                break;
            case "column_aw":
                $return = [
                    'easy_cbm_progmons',
                    ($tableValues['easy_cbm_progmons'][0]->column_s ?? '')
                ];
                return $return;
                break;
            case "column_ax":
                $return = [
                    'easy_cbm_progmons',
                    ($tableValues['easy_cbm_progmons'][0]->column_w ?? '')
                ];
                return $return;
                break;
            case "column_ay":
                $return = [
                    'star_fall_maths',
                    ($tableValues['star_fall_maths'][0]->column_j ?? '')
                ];
                return $return;
                break;
            case "column_az":
                $return = [
                    'star_fall_readings',
                    ($tableValues['star_fall_readings'][0]->column_j ?? '')
                ];
                return $return;
                break;
            case "column_ba":
                $return = [
                    'star_mid_year_maths',
                    ($tableValues['star_mid_year_maths'][0]->column_q ?? '')
                ];
                return $return;
                break;
            case "column_bb":
                $return = [
                    'star_mid_year_readings',
                    ($tableValues['star_mid_year_readings'][0]->column_t ?? '')
                ];
                return $return;
                break;
            case "column_bc":
                $return = [
                    'star_eoy_maths',
                    ($tableValues['star_eoy_maths'][0]->column_q ?? '')
                ];
                return $return;
                break;
            case "column_bd":
                $return = [
                    'star_eoy_readings',
                    ($tableValues['star_eoy_readings'][0]->column_t ?? '')
                ];
                return $return;
                break;
            case "column_be":
                $return = [
                    'consolidated BA-AY',
                    "Column BA -> " . ($consolidate->column_ba),
                    "Column AY -> " . ($consolidate->column_ay),
                    "Column BA - Column AY ->  "  . ((float)$consolidate->column_ba - (float)$consolidate->column_ay)
                ];
                return $return;
            case "column_bf":
                $return = [
                    'consolidated BB-AZ',
                    "Column BB -> " . ($consolidate->column_bb),
                    "Column AZ -> " . ($consolidate->column_az),
                    "Column BB - Column AA ->  "  . ((float)$consolidate->column_bb - (float)$consolidate->column_az)
                ];
                return $return;
            case "column_bg":
                $return = [
                    'consolidated BC-AY',
                    "Column BC -> " . ($consolidate->column_bc),
                    "Column AY -> " . ($consolidate->column_ay),
                    "Column BC - Column AY ->  "  . ((float)$consolidate->column_bc - (float)$consolidate->column_ay)
                ];
                return $return;
            case "column_bh":
                $return = [
                    'consolidated BD-AZ',
                    "Column BD -> " . ($consolidate->column_bd),
                    "Column AZ -> " . ($consolidate->column_az),
                    "Column BD - Column AZ ->  "  . ((float)$consolidate->column_bd - (float)$consolidate->column_az)
                ];
                return $return;
                break;
            case "column_bi":
                $return = [
                    'CLARIFY attendance',
                    '?????????????????',
                    '?????????????????',
                ];
                return $return;
                break;
            case "column_bj":
                $return = [
                    'i_ready_math_minutes',
                    ($tableValues['i_ready_math_minutes'][0]->column_v ?? '')
                ];
                return $return;
                break;
            case "column_bk":
                $return = [
                    'i_ready_reading_minutes',
                    ($tableValues['i_ready_reading_minutes'][0]->column_v ?? '')
                ];
                return $return;
                break;
            case "column_bl":
                $return = [
                    'freckle_minutes',
                    ($tableValues['freckle_minutes'][0]->column_i ?? '')
                ];
                return $return;
                break;
            case "column_bm":
                $return = [
                    'freckle_minutes',
                    ($tableValues['freckle_minutes'][0]->column_j ?? '')
                ];
                return $return;
                break;
            case "column_bn":
                $return = [
                    'read180_minutes',
                    ($tableValues['read180_minutes'][0]->column_h ?? '')
                ];
                return $return;
                break;
            case "column_bo":
                $return = [
                    'v_math_minutes',
                    ($tableValues['v_math_minutes'][0]->column_h ?? '')
                ];
                return $return;
                break;
            case "column_bp":
                $return = [
                    'math180_minutes',
                    ($tableValues['math180_minutes'][0]->column_h ?? '')
                ];
                return $return;
                break;
            case "column_bq":
                //dd($tableValues['student']);
                $return = [
                    'student',
                    ($tableValues['student']->column_z ?? '')
                ];
                return $return;
                break;
            case "column_br":
                //dd($tableValues['student']);
                $return = [
                    'student',
                    ($tableValues['student']->column_w ?? '')
                ];
                return $return;
                break;
            default:
                return "";
        }
    }
}
