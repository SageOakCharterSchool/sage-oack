<?php

$myValues = [
    'tables' => [
        'teacher_students',
        'student_accounts',
        'student_lists',
        //'attendances',
        // 'consolidateds',
        'math_lists',
        'i_ready_math_boys',
        'i_ready_reading_boy_s',
        'easy_cbm_falls',
        'i_ready_math_mid_years',
        'i_ready_reading_mid_years',
        'i_ready_math_eoy_s',
        'i_ready_reading_eoy_s',
        'attendance_elas',
        'attendance_maths',
        'read180_minutes',
        'v_math_minutes',
        'i_ready_reading_minutes',
        'i_ready_math_minutes',
        'caaspps',
        'elstudents',
        'sst_reports',
        'brainpops',
        'tutor',
        /////////////////////
        'easy_cbm_progmons',
        'freckle_minutes',
        'math180_minutes',
        'star_fall_maths',
        'star_fall_readings',
        'star_mid_year_maths',
        'star_mid_year_readings',
        'star_eoy_maths',
        'star_eoy_readings',
        'trans_math_minutes',
    ],
    'tablesAlias' => [
        'teacher_students' => 'Teacher Student',
        'student_accounts' => 'Student Accounts',
        'student_lists' => 'ELA Intervention List',
        'math_lists' => 'MATH Intervention List',
        'i_ready_math_boys' => 'iReady Math BOY',
        'i_ready_reading_boy_s' => 'iReady Reading BOY',
        'easy_cbm_falls' => 'easyCBM Fall',
        'i_ready_math_mid_years' => 'iReady Math Mid Year',
        'i_ready_reading_mid_years' => 'iReady Reading Mid Year',
        'i_ready_math_eoy_s' => 'iReady Math EOY',
        'i_ready_reading_eoy_s' => 'iReady Reading EOY',
        'attendance_elas' => 'Reading Class Attendance ',
        'attendance_maths' => 'Math Class Attendance ',
        'read180_minutes' => 'Reading Class Minutes',
        'v_math_minutes' => 'Math Class Minutes',
        'i_ready_reading_minutes' => 'iReady Reading Minutes',
        'i_ready_math_minutes' => 'iReady Math Minutes',
        'caaspps' => 'CAASPP',
        'elstudents' => 'EL Students',
        'sst_reports' => 'SST Reports',
        'brainpops' => 'Brainpop EL Minutes',
        'tutor' => 'Tutor.com Sessions',
        'easy_cbm_progmons' => 'easyCBM Progress Monitoring',
        'freckle_minutes' => 'Freckle Minutes',
        'math180_minutes' => 'Math 180 Minutes',
        'star_fall_maths' => 'STAR Math BOY',
        'star_fall_readings' => 'STAR Reading BOY',
        'star_mid_year_maths' => 'STAR Math Mid Year',
        'star_mid_year_readings' => 'STAR Reading Mid Year',
        'star_eoy_maths' => 'STAR Math EOY',
        'star_eoy_readings' => 'STAR Reading EOY',
        'trans_math_minutes' => 'Transmath Minutes',
    ],
    'noSID' => []

];
$encryptedFields['student_lists'] = [
    'column_a',
    'column_b',
    'column_g',
    'column_k',
];
$encryptedFields['consolidateds'] = [
    'column_b',
    'column_c',
    'column_g',
];
$encryptedFields['sheet15s'] = [
    'column_a',
    'column_b',
    'column_c',
];
$encryptedFields['math_lists'] = [
    'column_a',
    'column_b',
    'column_g',
];
$encryptedFields['attendance_maths'] = [
    'column_b',
    'column_c',
];
$encryptedFields['attendance_elas'] = [
    'column_b',
    'column_c',
];
$encryptedFields['freckle_minutes'] = [
    'column_a',
];


$encryptedFields['star_fall_maths'] = [
    'column_b',
];
$encryptedFields['star_fall_readings'] = [
    'column_b',
];
$encryptedFields['star_mid_year_maths'] = [
    'column_b',
];
$encryptedFields['star_mid_year_readings'] = [
    'column_b',
];
$encryptedFields['easy_cbm_falls'] = [
    'column_a',
    'column_b',
];
$encryptedFields['easy_cbm_progmons'] = [
    'column_a',
    'column_b',
];
$encryptedFields['read180_minutes'] = [
    'column_a',
    'column_b',
];
$encryptedFields['math180_minutes'] = [
    'column_a',
    'column_b',
];
$encryptedFields['v_math_minutes'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_math_boys'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_reading_boy_s'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_math_mid_years'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_reading_mid_years'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_math_eoy_s'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_reading_eoy_s'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_math_minutes'] = [
    'column_a',
    'column_b',
];
$encryptedFields['i_ready_reading_minutes'] = [
    'column_a',
    'column_b',
];
$encryptedFields['caaspps'] = [
    'column_c',
    'column_d',
    'column_e',
];
$encryptedFields['elstudents'] = [
    'column_e',
    'column_f',
    'column_s',
    'column_t',
];
$encryptedFields['sst_reports'] = [
    'column_a',
];
$encryptedFields['student_accounts'] = [
    'column_a',
    'column_b',
    'column_e',
];
$encryptedFields['brainpops'] = [
    'column_a',
    'column_c',
];
$myValues['encryptedFields'] = $encryptedFields;
$myValues['multi_table_fields'] = 18;

$myValues['siteOperations'] = [
    '{+}',
    '{-}',
    '{*}',
    '{/}',
    //'{^}',
    '{self:teacher_id}',
    '{self:student_id}',
    '{self:cycle_id}',
    '{checkStudent}:{place student_id from the table you want to check}',
    '{remove:"Independent Study - "}:{place column name to return here}',
    '{remove:"Independent Student - "}:{place column name to return here}',
    '{columnACADEquivalences}:{place column name to evaluate}',
    '{getCaasppMath01}:{place column name to evaluate}:{place column name to return here}',
    '{getCaasppReading02}:{place column name to evaluate}:{place column name to return here}',
    '{getMultipleValues}:{place column name to return here}',
    '{getEquivalences}:{place column name to evaluate}',
    '{self}:{place column name to evaluate}',
];

return $myValues;
