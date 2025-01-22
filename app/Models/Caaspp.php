<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caaspp extends Model
{
    use HasFactory;
    protected $table = "caaspps";
    // The score is in the same column, however,
    // the number code in column A will be 02 for math and 01 for reading.
    protected $fillable = [
        'created_by', //created_by
        'student_id', //student_id
        'cycle_id', //cycle_id
        'teacher_id', //teacher_id
        'column_a', //RecordType
        'column_b', //SSID
        'column_c', //StudentLastName
        'column_d', //StudentFirstName
        'column_e', //StudentMiddleName
        'column_f', //DateofBirth
        'column_g', //Gender
        'column_h', //Blank1
        'column_i', //Blank2
        'column_j', //CALPADSGrade
        'column_k', //GradeAssessed
        'column_l', //CALPADSDistrictCode
        'column_m', //CALPADSDistrictName
        'column_n', //CALPADSSchoolCode
        'column_o', //CALPADSSchoolName
        'column_p', //CALPADSCharterCode
        'column_q', //CALPADSCharterSchoolIndicator
        'column_r', //SPEDAcctDist
        'column_s', //Section504Status
        'column_t', //PrimaryDisabilityType
        'column_u', //PrimaryDisabilityforTesting
        'column_v', //CALPADSIDEAIndicator
        'column_w', //IDEAIndicatorforTesting
        'column_x', //MigrantStatus
        'column_y', //ELStatus
        'column_z', //ELEntryDate
        'column_aa', //RFEPDate
        'column_ab', //FirstEntryDateInUSSchool
        'column_ac', //EnrollmentEffectiveDate
        'column_ad', //ELAS
        'column_ae', //CEDSLanguageCode
        'column_af', //CALPADSPrimaryLanguage
        'column_ag', //MilitaryStatus
        'column_ah', //FosterStatus
        'column_ai', //HomelessStatus
        'column_aj', //EconomicDisadvantageStatus
        'column_ak', //EconomicDisadvantageTesting
        'column_al', //CALPADSNPSSchoolFlag
        'column_am', //HispanicorLatino
        'column_an', //AmericanIndianorAlaskaNative
        'column_ao', //Asian
        'column_ap', //HawaiianOrOtherPacificIslander
        'column_aq', //Filipino
        'column_ar', //BlackorAfricanAmerican
        'column_as', //White
        'column_at', //TwoorMoreRaces
        'column_au', //ReportingEthnicity
        'column_av', //ParentEducationLevel
        'column_aw', //Blank3
        'column_ax', //OpportunityID1
        'column_ay', //OpportunityTestingStatus1
        'column_az', //OpportunityID2
        'column_ba', //OpportunityTestingStatus2
        'column_bb', //OpportunityID3
        'column_bc', //OpportunityTestingStatus3
        'column_bd', //OpportunityID4
        'column_be', //OpportunityTestingStatus4
        'column_bf', //TestRegistrationID
        'column_bg', //TestedDistrictName1
        'column_bh', //TestedDistrictCode1
        'column_bi', //TestedSchoolName1
        'column_bj', //TestedSchoolCode1
        'column_bk', //TestedCharterSchoolIndicator1
        'column_bl', //TestedCharterCode1
        'column_bm', //TestedSchoolNPSFlag1
        'column_bn', //PaperTestCompletionDate
        'column_bo', //TestedDistrictName2
        'column_bp', //TestedDistrictCode2
        'column_bq', //TestedSchoolName2
        'column_br', //TestedSchoolCode2
        'column_bs', //TestedCharterSchoolIndicator2
        'column_bt', //TestedCharterCode2
        'column_bu', //TestedSchoolNPSFlag2
        'column_bv', //TestedDistrictName3
        'column_bw', //TestedDistrictCode3
        'column_bx', //TestedSchoolName3
        'column_by', //TestedSchoolCode3
        'column_bz', //TestedCharterSchoolIndicator3
        'column_ca', //TestedCharterCode3
        'column_cb', //TestedSchoolNPSFlag3
        'column_cc', //TestedDistrictName4
        'column_cd', //TestedDistrictCode4
        'column_ce', //TestedSchoolName4
        'column_cf', //TestedSchoolCode4
        'column_cg', //TestedCharterSchoolIndicator4
        'column_ch', //TestedCharterCode4
        'column_ci', //TestedSchoolNPSFlag4
        'column_cj', //TestStartDate1
        'column_ck', //TestCompletedDate1
        'column_cl', //TestStartDate2
        'column_cm', //TestCompletedDate2
        'column_cn', //TestStartDate3
        'column_co', //TestCompletedDate3
        'column_cp', //TestStartDate4
        'column_cq', //TestCompletedDate4
        'column_cr', //FinalTestedDistrictName
        'column_cs', //FinalTestedDistrictCode
        'column_ct', //FinalTestedSchoolName
        'column_cu', //FinalTestedSchoolCode
        'column_cv', //FinalTestedCharterSchoolIndicator
        'column_cw', //FinalTestedCharterCode
        'column_cx', //FinalTestedSchoolNPSFlag
        'column_cy', //FinalTestCompletedDate
        'column_cz', //SchoolStartDateTestWindow1
        'column_da', //SchoolEndDateTestWindow1
        'column_db', //SchoolStartDateTestWindow2
        'column_dc', //SchoolEndDateTestWindow2
        'column_dd', //SchoolStartDateTestWindow3
        'column_de', //SchoolEndDateTestWindow3
        'column_df', //SchoolStartDateTestWindow4
        'column_dg', //SchoolEndDateTestWindow4
        'column_dh', //StudentExitCode
        'column_di', //StudentExitWithdrawalDate
        'column_dj', //StudentRemovedCALPADSFileDate
        'column_dk', //ELASCorrectionCode
        'column_dl', //CASTCurrentYearFlag
        'column_dm', //CASTParticipatedHighSchoolGrade
        'column_dn', //CASTParticipatedNPSflag
        'column_do', //CASTParticipatedDistrictofAccountability
        'column_dp', //CASTLastScienceClassFlag
        'column_dq', //ConditionCode
        'column_dr', //Attemptedness
        'column_ds', //ScoreStatus
        'column_dt', //UnlistedResourcesConstructChange
        'column_du', //TestMode
        'column_dv', //IncludeIndicator
        'column_dw', //RemoteTester1
        'column_dx', //RemoteTester2
        'column_dy', //RemoteTester3
        'column_dz', //RemoteTester4
        'column_ea', //SSREligible
        'column_eb', //ScoreAvailableDate
        'column_ec', //LexileorQuantileMeasure
        'column_ed', //GrowthScore
        'column_ee', //Blank4
        'column_ef', //RawScore1
        'column_eg', //RawScore2
        'column_eh', //RawScore3
        'column_ei', //RawScore4
        'column_ej', //Blank5
        'column_ek', //Blank6
        'column_el', //Blank7
        'column_em', //Blank8
        'column_en', //Blank9
        'column_eo', //Blank10
        'column_ep', //Blank11
        'column_eq', //Blank12
        'column_er', //ScaleScore
        'column_es', //StandardErrorMeasurement
        'column_et', //SmarterScaleScoresErrorBandsMin
        'column_eu', //SmarterScaleScoresErrorBandsMax
        'column_ev', //AchievementLevels
        'column_ew', //Domain1Level
        'column_ex', //Domain2Level
        'column_ey', //Domain3Level
        'column_ez', //Genre
        'column_fa', //WERPOR
        'column_fb', //WERDEVEEL
        'column_fc', //WERCOV
        'column_fd', //WERPORConditionCode
        'column_fe', //WERDEVEELConditionCode
        'column_ff', //WERCOVConditionCode
        'column_fg', //EAP
        'column_fh', //ItemsAttempted1
        'column_fi', //ItemsAttempted2
        'column_fj', //ItemsAttempted3
        'column_fk', //ItemsAttempted4
        'column_fl', //AccommodationsIndicator
        'column_fm', //DesignatedSupportIndicator
        'column_fn', //EAAmericanSignLanguage1
        'column_fo', //EAAmericanSignLanguage2
        'column_fp', //EAAudioTransScript1
        'column_fq', //EAAudioTransScript2
        'column_fr', //EABraille1
        'column_fs', //EABraille2
        'column_ft', //EAClosedCaptioning1
        'column_fu', //EAClosedCaptioning2
        'column_fv', //EASpeeachtoText1
        'column_fw', //EASpeeachtoText2
        'column_fx', //EATexttoSpeech1
        'column_fy', //EATexttoSpeech2
        'column_fz', //NEA100NumberTable1
        'column_ga', //NEA100NumberTable2
        'column_gb', //NEAAbacus1
        'column_gc', //NEAAbacus2
        'column_gd', //NEAAbacus3
        'column_ge', //NEAAbacus4
        'column_gf', //NEASupportsforAltAssessments1
        'column_gg', //NEASupportsforAltAssessments2
        'column_gh', //NEASupportsforAltAssessments3
        'column_gi', //NEASupportsforAltAssessments4
        'column_gj', //NEAAltResponseOptions1
        'column_gk', //NEAAltResponseOptions2
        'column_gl', //NEAAltResponseOptions3
        'column_gm', //NEAAltResponseOptions4
        'column_gn', //NEABraillePaper
        'column_go', //NEACalculator1
        'column_gp', //NEACalculator2
        'column_gq', //NEALargePrintSpecialPaper
        'column_gr', //NEAMultiplicationTable1
        'column_gs', //NEAMultiplicationTable2
        'column_gt', //NEAPrintonDemand1
        'column_gu', //NEAPrintonDemand2
        'column_gv', //NEAPrintonDemand3
        'column_gw', //NEAPrintonDemand4
        'column_gx', //NEAReadAloudPassages1
        'column_gy', //NEAScribe1
        'column_gz', //NEAScribe2
        'column_ha', //NEASpeechtoText1
        'column_hb', //NEASpeechtoText2
        'column_hc', //NEAUnlistedResources1
        'column_hd', //NEAUnlistedResources2
        'column_he', //NEAUnlistedResources3
        'column_hf', //NEAUnlistedResources4
        'column_hg', //NEAWordPrediction1
        'column_hh', //NEAWordPrediction2
        'column_hi', //EDSColorContrast1
        'column_hj', //EDSColorContrast2
        'column_hk', //EDSColorContrast3
        'column_hl', //EDSColorContrast4
        'column_hm', //EDSMasking1
        'column_hn', //EDSMasking2
        'column_ho', //EDSMasking3
        'column_hp', //EDSMasking4
        'column_hq', //EDSMousePointer1
        'column_hr', //EDSMousePointer2
        'column_hs', //EDSMousePointer3
        'column_ht', //EDSMousePointer4
        'column_hu', //EDSPermissiveMode1
        'column_hv', //EDSPermissiveMode2
        'column_hw', //EDSPermissiveMode3
        'column_hx', //EDSPermissiveMode4
        'column_hy', //EDSPrintSize1
        'column_hz', //EDSPrintSize2
        'column_ia', //EDSPrintSize3
        'column_ib', //EDSPrintSize4
        'column_ic', //EDSTranslatedTestDirections1
        'column_id', //EDSTranslatedTestDirections2
        'column_ie', //EDSStreamline1
        'column_if', //EDSStreamline2
        'column_ig', //EDSStreamline3
        'column_ih', //EDSStreamline4
        'column_ii', //EDSTexttoSpeech1
        'column_ij', //EDSTexttoSpeech2
        'column_ik', //EDSTranslations1
        'column_il', //EDSTranslations2
        'column_im', //EDSTurnoffUniversalTools1
        'column_in', //EDSTurnoffUniversalTools2
        'column_io', //EDSTurnoffUniversalTools3
        'column_ip', //EDSTurnoffUniversalTools4
        'column_iq', //NEDS100NumberTable1
        'column_ir', //NEDS100NumberTable2
        'column_is', //NEDS100NumberTable3
        'column_it', //NEDS100NumberTable4
        'column_iu', //NEDSAmplification1
        'column_iv', //NEDSAmplification2
        'column_iw', //NEDSAmplification3
        'column_ix', //NEDSAmplification4
        'column_iy', //NEDSBilingualDictionary1
        'column_iz', //NEDSBilingualDictionary2
        'column_ja', //NEDSCalculator1
        'column_jb', //NEDSColorContrast1
        'column_jc', //NEDSColorContrast2
        'column_jd', //NEDSColorContrast3
        'column_je', //NEDSColorContrast4
        'column_jf', //NEDSColorOverlay1
        'column_jg', //NEDSColorOverlay2
        'column_jh', //NEDSMagnification1
        'column_ji', //NEDSMagnification2
        'column_jj', //NEDSMagnification3
        'column_jk', //NEDSMagnification4
        'column_jl', //NEDSMedicalSupports1
        'column_jm', //NEDSMedicalSupports2
        'column_jn', //NEDSMedicalSupports3
        'column_jo', //NEDSMedicalSupports4
        'column_jp', //NEDSMultiplicationTable1
        'column_jq', //NEDSMultiplicationTable2
        'column_jr', //NEDSMultiplicationTable3
        'column_js', //NEDSMultiplicationTable4
        'column_jt', //NEDSNoiseBuffers1
        'column_ju', //NEDSNoiseBuffers2
        'column_jv', //NEDSNoiseBuffers3
        'column_jw', //NEDSNoiseBuffers4
        'column_jx', //NEDSReadAloudItems1
        'column_jy', //NEDSReadAloudItems2
        'column_jz', //NEDSReadAloudItems3
        'column_ka', //NEDSReadAloudItems4
        'column_kb', //NEDSReadAloudinSpanish1
        'column_kc', //NEDSReadAloudinSpanish2
        'column_kd', //NEDSScienceCharts1
        'column_ke', //NEDSScribeItems1
        'column_kf', //NEDSScribeItems2
        'column_kg', //NEDSScribeItems3
        'column_kh', //NEDSScribeItems4
        'column_ki', //NEDSSeparateSetting1
        'column_kj', //NEDSSeparateSetting2
        'column_kk', //NEDSSeparateSetting3
        'column_kl', //NEDSSeparateSetting4
        'column_km', //NEDSSimplifiedTestDirections1
        'column_kn', //NEDSSimplifiedTestDirections2
        'column_ko', //NEDSSimplifiedTestDirections3
        'column_kp', //NEDSSimplifiedTestDirections4
        'column_kq', //NEDSTranslatedTestDirections1
        'column_kr', //NEDSTranslatedTestDirections2
        'column_ks', //NEDSTranslationsPaper
        'column_kt', //SSREligibleMinus1
        'column_ku', //GradeAssessedMinus1
        'column_kv', //Blank13
        'column_kw', //SEMMinus1
        'column_kx', //ScaleScoreMinus1
        'column_ky', //AchievementLevelMinus1
        'column_kz', //ConditionCodeMinus1
        'column_la', //Blank14
        'column_lb', //Blank15
        'column_lc', //Blank16
        'column_ld', //Blank17
        'column_le', //Blank18
        'column_lf', //Blank19
        'column_lg', //Blank20
        'column_lh', //Blank21
        'column_li', //Blank22
        'column_lj', //Blank23
        'column_lk', //Blank24
        'column_ll', //SSREligibleMinus2
        'column_lm', //GradeAssessedMinus2
        'column_ln', //Blank25
        'column_lo', //SEMMinus2
        'column_lp', //ScaleScoreMinus2
        'column_lq', //AchievementLevelMinus2
        'column_lr', //ConditionCodeMinus2
        'column_ls', //Blank26
        'column_lt', //Blank27
        'column_lu', //Blank28
        'column_lv', //Blank29
        'column_lw', //Blank30
        'column_lx', //Blank31
        'column_ly', //Blank32
        'column_lz', //Blank33
        'column_ma', //Blank34
        'column_mb', //Blank35
        'column_mc', //Blank36
        'column_md', //SSREligibleMinus3
        'column_me', //GradeAssessedMinus3
        'column_mf', //Blank37
        'column_mg', //SEMMinus3
        'column_mh', //ScaleScoreMinus3
        'column_mi', //AchievementLevelMinus3
        'column_mj', //ConditionCodeMinus3
        'column_mk', //Blank38
        'column_ml', //Blank39
        'column_mm', //Blank40
        'column_mn', //Blank41
        'column_mo', //Blank42
        'column_mp', //Blank43
        'column_mq', //Blank44
        'column_mr', //Blank45
        'column_ms', //Blank46
        'column_mt', //Blank47
        'column_mu', //Blank48
        'column_mv', //Blank49
        'column_mw', //UIN
        'column_mx', //Blank50
        'column_my', //EndofRecord
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

    //The score is in the same column, however, the number code in column A will be 02 for math and 01 for reading.
    protected function getAllRecordsByStudentIDOnCycle($cycle,$studentID,$type=null) {
        $query = $this->where('cycle_id',$cycle->id)
                    ->where('student_id',$studentID);

        if ($type=="02") { // Math
            $query->where("column_a","02");
        } else if ($type=="01") { // Reading
            $query->where("column_a","01");
        }
        $rows = $query->get();
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
