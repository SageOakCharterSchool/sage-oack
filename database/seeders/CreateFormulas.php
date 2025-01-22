<?php

namespace Database\Seeders;

use App\Models\ConsolidateMapping;
use App\Models\Formula;
use App\Models\MultiTableFields;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateFormulas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sql = "
        INSERT INTO `formulas` (`id`, `cycle_id`, `formula_name`, `formula_description`, `formula`, `created_by`, `created_at`, `updated_at`, `is_system`) VALUES
	(1, 9, 'Get Program Name', 'student accounts Column H-- Please omit \"independent study -\"', '{remove:\"Independent Study - \"}:[~999|2~]{Student Accounts-&gt; Column_H -&gt; (Enrollments1) Program}', 1, '2024-10-02 12:10:54', '2024-10-04 15:08:41', 1),
	(2, 9, 'Teacher Name', 'Get Teacher name', '[~999|1~]{Teacher Student-&gt; Column_A -&gt; (Staff1) First Name}{+}[~999|1~]{Teacher Student-&gt; Column_B -&gt; (Staff1) Last Name}', 1, '2024-10-02 12:21:22', '2024-10-04 15:09:52', 1),
	(3, 9, 'Get CAASPP Math', 'Get CAASPP column 01', '{getCaasppMath01}:[~999|18~]{CAASPP-&gt; Column_A -&gt; RecordType}:[~999|18~]{CAASPP-&gt; Column_EV -&gt; AchievementLevels}', 1, '2024-10-03 20:54:41', '2024-10-04 17:43:44', 1),
	(4, 9, 'Get CAASPP Reading', 'Get CAASPP column 02', '{getCaasppMath01}:[~999|18~]{CAASPP-&gt; Column_A -&gt; RecordType}:[~999|18~]{CAASPP-&gt; Column_EV -&gt; AchievementLevels}', 1, '2024-10-03 20:55:35', '2024-10-04 17:43:56', 1),
	(5, 9, 'Teacher Id', 'get the value of teacher id from the main source (studen accounts,math_list)', '{self:teacher_id}', 1, '2024-10-04 13:03:00', '2024-10-04 13:03:00', 1),
	(6, 9, 'Student Id', 'get the value of student id from the main source (studen accounts,math_list)', '{self:student_id}', 1, '2024-10-04 13:03:28', '2024-10-04 13:03:28', 1),
	(7, 9, 'Cycle Id', 'get the value of cycle id from the main source (studen accounts,math_list)', '{self:cycle_id}', 1, '2024-10-04 15:41:33', '2024-10-04 15:41:33', 0),
	(8, 9, 'Get iReady Math BOY Growth Equivalence', 'Get iReady Math BOY Growth Equivalence', '{getEquivalences}:[~999|5~]{iReady Math BOY-&gt; Column_AF -&gt; Overall Placement}', 1, '2024-10-04 19:15:12', '2024-10-05 10:15:06', 1),
	(9, 9, 'Get iReady Reading BOY Growth Equivalence', 'Get iReady Reading BOY Growth Equivalence', '{getEquivalences}:[~999|6~]{iReady Reading BOY-&gt; Column_AF -&gt; Overall Placement}', 1, '2024-10-04 19:16:14', '2024-10-04 19:16:14', 1),
	(45, 9, 'Substract column S-M', 'Substract column S-M', '[~999|999~]{Consolidated -&gt; column_S -&gt; iReady Math Points Fall}{-}[~999|999~]{Consolidated -&gt; column_M -&gt; ELA Intervention Recommendation}', 1, '2024-10-04 20:13:40', '2024-10-04 20:13:40', 0),
	(10, 9, 'Get easyCBM Fall Growth Equivalence', 'Get easyCBM Fall Growth Equivalence', '{getEquivalences}:{place column name to evaluate}', 1, '2024-10-04 19:16:14', '2024-10-05 10:25:24', 1),
	(11, 9, 'Get iReady Math Mid Year Growth Equivalence', 'Get iReady Math Mid Year Growth Equivalence', '{getEquivalences}:{place column name to evaluate}', 1, '2024-10-04 19:16:14', '2024-10-05 10:25:35', 1),
	(12, 9, 'Get iReady Reading Mid Year Growth Equivalence', 'Get iReady Reading Mid Year Growth Equivalence', '{getEquivalences}:{place column name to evaluate}', 1, '2024-10-04 19:16:14', '2024-10-05 10:25:52', 1),
	(13, 9, 'Get iReady Math EOY Growth Equivalence', 'Get iReady Math EOY Growth Equivalence', '{getEquivalences}:{place column name to evaluate}', 1, '2024-10-04 19:16:14', '2024-10-05 10:26:05', 1),
	(14, 9, 'Get iReady Reading EOY Growth Equivalence', 'Get iReady Reading EOY Growth Equivalence', '{getEquivalences}:{place column name to evaluate}', 1, '2024-10-04 19:16:14', '2024-10-05 10:26:15', 1);

        ";
         MultiTableFields::truncate();
        Formula::truncate();
    $result = \DB::statement($sql);
        ConsolidateMapping::truncate();
    $sql = "
INSERT INTO `consolidate_mappings` (`id`, `cycle_id`, `screen_sort`, `column_name`, `column_description`, `table_source`, `field_source`, `is_formulated`, `formula_id`, `created_by`, `created_at`, `updated_at`) VALUES
	(1, 9, 10, 'column_A', 'id', 999, '999->None', 0, NULL, 1, NULL, '2024-10-12 20:00:52'),
	(2, 9, 20, 'column_B', 'teacher_id', NULL, NULL, 0, 5, 1, NULL, '2024-10-04 15:39:47'),
	(3, 9, 30, 'column_C', 'cycle_id', NULL, NULL, 0, 7, 1, NULL, '2024-10-04 15:41:42'),
	(4, 9, 40, 'column_D', 'Student ID', NULL, NULL, 0, 6, 1, NULL, '2024-10-04 15:41:49'),
	(5, 9, 50, 'column_E', 'Student Last Name', 2, '2->Column_B', 0, NULL, 1, NULL, '2024-10-12 19:37:47'),
	(6, 9, 60, 'column_F', 'Student First Name', 2, '2->Column_A', 0, NULL, 1, NULL, '2024-10-12 19:55:11'),
	(7, 9, 70, 'column_G', 'Grade', 2, '2->Column_D', 0, NULL, 1, NULL, '2024-10-12 19:55:39'),
	(8, 9, 80, 'column_H', 'SIS', 2, '2->Column_I', 0, NULL, 1, NULL, '2024-10-12 19:55:53'),
	(9, 9, 90, 'column_I', 'Program', NULL, NULL, 0, 1, 1, NULL, '2024-10-04 15:49:15'),
	(10, 9, 100, 'column_J', 'Teacher Name', NULL, NULL, 0, 2, 1, NULL, '2024-10-04 15:49:32'),
	(11, 9, 110, 'column_K', 'Qualifying Subject 1', 3, '3->Column_F', 0, NULL, 1, NULL, '2024-10-12 19:56:15'),
	(12, 9, 120, 'column_L', 'Qualifying Subject 2', 4, '4->Column_F', 0, NULL, 1, NULL, '2024-10-12 20:47:03'),
	(13, 9, 130, 'column_M', 'ELA Intervention Recommendation', 3, '3->Column_J', 0, NULL, 1, NULL, '2024-10-12 19:56:28'),
	(14, 9, 140, 'column_N', 'ELA Intervention Selected', 3, '3->Column_O', 0, NULL, 1, NULL, '2024-10-12 19:56:42'),
	(15, 9, 150, 'column_O', 'Math Intervention Recommendation', 0, '0', 0, NULL, 1, NULL, NULL),
	(16, 9, 160, 'column_P', 'MATH Intervention Selected', 0, '0', 0, NULL, 1, NULL, NULL),
	(17, 9, 170, 'column_Q', 'CAASPP Math', NULL, NULL, 0, 3, 1, NULL, '2024-10-04 15:51:30'),
	(18, 9, 180, 'column_R', 'CAASPP Reading', NULL, NULL, 0, 4, 1, NULL, '2024-10-04 15:51:39'),
	(19, 9, 190, 'column_S', 'iReady Math Points Fall', 0, '0', 0, NULL, 1, NULL, NULL),
	(20, 9, 200, 'column_T', 'iReady Relative Placement Math Fall', 0, '0', 0, NULL, 1, NULL, NULL),
	(21, 9, 210, 'column_U', 'iReady Level Math Fall', NULL, NULL, 0, 8, 1, NULL, '2024-10-04 19:23:21'),
	(22, 9, 220, 'column_V', 'iReady Reading Points Fall', 0, '0', 0, NULL, 1, NULL, NULL),
	(23, 9, 230, 'column_W', 'iReady Relative Placement Reading Fall', 0, '0', 0, NULL, 1, NULL, NULL),
	(24, 9, 240, 'column_X', 'iReady Level Reading Fall', 0, '0', 0, NULL, 1, NULL, NULL),
	(25, 9, 250, 'column_Y', 'iReady Math Points Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(26, 9, 260, 'column_Z', 'iReady Relative Placement Math Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(27, 9, 270, 'column_AA', 'iReady Level Math Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(28, 9, 280, 'column_AB', 'iReady Reading Points Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(29, 9, 290, 'column_AC', 'iReady Relative Placement Reading Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(30, 9, 300, 'column_AD', 'iReady Level Reading Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(31, 9, 310, 'column_AE', 'iReady Math Points End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(32, 9, 320, 'column_AF', 'iReady Relative Placement Math End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(33, 9, 330, 'column_AG', 'iReady Level Math End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(34, 9, 340, 'column_AH', 'iReady Reading Points End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(35, 9, 350, 'column_AI', 'iReady Relative Placement Reading End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(36, 9, 360, 'column_AJ', 'iReady Level Reading End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(37, 9, 370, 'column_AK', 'iReady Growth Points Math Mid Year', NULL, NULL, 0, 10, 1, NULL, '2024-10-04 20:18:14'),
	(38, 9, 380, 'column_AL', 'iReady Levels Math Growth Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(39, 9, 390, 'column_AM', 'iReady Growth Points Reading Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(40, 9, 400, 'column_AN', 'iReady Levels Reading Growth Mid Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(41, 9, 410, 'column_AO', 'iReady Growth Points Math End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(42, 9, 420, 'column_AP', 'IReady Levels Math Growth End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(43, 9, 430, 'column_AQ', 'iReady Growth Points Reading End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(44, 9, 440, 'column_AR', 'IReady Levels Reading Growth End of Year', 0, '0', 0, NULL, 1, NULL, NULL),
	(45, 9, 450, 'column_AS', 'easyCBM Reading Risk', 0, '0', 0, NULL, 1, NULL, NULL),
	(46, 9, 460, 'column_AT', 'easyCBM Math Risk', 0, '0', 0, NULL, 1, NULL, NULL),
	(47, 9, 470, 'column_AU', 'Intervention Math Class Attendance', 0, '0', 0, NULL, 1, NULL, NULL),
	(48, 9, 480, 'column_AV', 'Intervention Reading Class Attendance', 0, '0', 0, NULL, 1, NULL, NULL),
	(49, 9, 490, 'column_AW', 'iReady Minutes Math', 0, '0', 0, NULL, 1, NULL, NULL),
	(50, 9, 500, 'column_AX', 'iReady Minutes Reading', 0, '0', 0, NULL, 1, NULL, NULL),
	(51, 9, 510, 'column_AY', 'Reading Class Minutes', 0, '0', 0, NULL, 1, NULL, NULL),
	(52, 9, 520, 'column_AZ', 'Math Class Minutes', 0, '0', 0, NULL, 1, NULL, NULL),
	(53, 9, 530, 'column_BA', 'Tutor.com Sessions', 0, '0', 0, NULL, 1, NULL, NULL),
	(54, 9, 540, 'column_BB', 'Class Info', 0, '0', 0, NULL, 1, NULL, NULL),
	(55, 9, 550, 'column_BC', 'Notes', 0, '0', 0, NULL, 1, NULL, NULL),
	(56, 9, 560, 'column_BD', 'SST', 0, '0', 0, NULL, 1, NULL, NULL),
	(57, 9, 570, 'column_BE', 'sped', 0, '0', 0, NULL, 1, NULL, NULL),
	(58, 9, 580, 'column_BF', 'ELD', 0, '0', 0, NULL, 1, NULL, NULL),
	(194, 10, 450, 'column_AS', 'easyCBM Reading Risk', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(193, 10, 440, 'column_AR', 'IReady Levels Reading Growth End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(192, 10, 430, 'column_AQ', 'iReady Growth Points Reading End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(191, 10, 420, 'column_AP', 'IReady Levels Math Growth End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(190, 10, 410, 'column_AO', 'iReady Growth Points Math End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(189, 10, 400, 'column_AN', 'iReady Levels Reading Growth Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(188, 10, 390, 'column_AM', 'iReady Growth Points Reading Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(187, 10, 380, 'column_AL', 'iReady Levels Math Growth Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(186, 10, 370, 'column_AK', 'iReady Growth Points Math Mid Year', NULL, NULL, 0, 116, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(185, 10, 360, 'column_AJ', 'iReady Level Reading End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(184, 10, 350, 'column_AI', 'iReady Relative Placement Reading End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(183, 10, 340, 'column_AH', 'iReady Reading Points End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(182, 10, 330, 'column_AG', 'iReady Level Math End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(181, 10, 320, 'column_AF', 'iReady Relative Placement Math End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(180, 10, 310, 'column_AE', 'iReady Math Points End of Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(179, 10, 300, 'column_AD', 'iReady Level Reading Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(178, 10, 290, 'column_AC', 'iReady Relative Placement Reading Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(177, 10, 280, 'column_AB', 'iReady Reading Points Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(176, 10, 270, 'column_AA', 'iReady Level Math Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(175, 10, 260, 'column_Z', 'iReady Relative Placement Math Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(174, 10, 250, 'column_Y', 'iReady Math Points Mid Year', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(173, 10, 240, 'column_X', 'iReady Level Reading Fall', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(172, 10, 230, 'column_W', 'iReady Relative Placement Reading Fall', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(171, 10, 220, 'column_V', 'iReady Reading Points Fall', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(170, 10, 210, 'column_U', 'iReady Level Math Fall', NULL, NULL, 0, 113, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(169, 10, 200, 'column_T', 'iReady Relative Placement Math Fall', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(168, 10, 190, 'column_S', 'iReady Math Points Fall', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(167, 10, 180, 'column_R', 'CAASPP Reading', NULL, NULL, 0, 109, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(166, 10, 170, 'column_Q', 'CAASPP Math', NULL, NULL, 0, 108, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(165, 10, 160, 'column_P', 'MATH Intervention Selected', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(164, 10, 150, 'column_O', 'Math Intervention Recommendation', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(163, 10, 140, 'column_N', 'ELA Intervention Selected', 163, '163->Column_O', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(162, 10, 130, 'column_M', 'ELA Intervention Recommendation', 163, '163->Column_J', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(161, 10, 120, 'column_L', 'Qualifying Subject 2', 164, '164->Column_F', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(160, 10, 110, 'column_K', 'Qualifying Subject 1', 163, '163->Column_F', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(159, 10, 100, 'column_J', 'Teacher Name', NULL, NULL, 0, 107, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(158, 10, 90, 'column_I', 'Program', NULL, NULL, 0, 106, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(157, 10, 80, 'column_H', 'SIS', 162, '162->Column_I', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(156, 10, 70, 'column_G', 'Grade', 162, '162->Column_D', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(155, 10, 60, 'column_F', 'Student First Name', 162, '162->Column_A', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(154, 10, 50, 'column_E', 'Student Last Name', 162, '162->Column_B', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(153, 10, 40, 'column_D', 'Student ID', NULL, NULL, 0, 111, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(152, 10, 30, 'column_C', 'cycle_id', NULL, NULL, 0, 112, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(151, 10, 20, 'column_B', 'teacher_id', NULL, NULL, 0, 110, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(150, 10, 10, 'column_A', 'id', 999, '999->None', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(195, 10, 460, 'column_AT', 'easyCBM Math Risk', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(196, 10, 470, 'column_AU', 'Intervention Math Class Attendance', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(197, 10, 480, 'column_AV', 'Intervention Reading Class Attendance', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(198, 10, 490, 'column_AW', 'iReady Minutes Math', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(199, 10, 500, 'column_AX', 'iReady Minutes Reading', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(200, 10, 510, 'column_AY', 'Reading Class Minutes', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(201, 10, 520, 'column_AZ', 'Math Class Minutes', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(202, 10, 530, 'column_BA', 'Tutor.com Sessions', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(203, 10, 540, 'column_BB', 'Class Info', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(204, 10, 550, 'column_BC', 'Notes', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(205, 10, 560, 'column_BD', 'SST', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(206, 10, 570, 'column_BE', 'sped', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19'),
	(207, 10, 580, 'column_BF', 'ELD', 0, '0', 0, NULL, 1, '2024-10-12 20:47:19', '2024-10-12 20:47:19');
";
$result = \DB::statement($sql);
    }
}
