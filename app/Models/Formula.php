<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Schema;
use App\Models\Equivalences;


use function PHPUnit\Framework\isEmpty;

/**
 * Class Formula
 *
 * @property $id
 * @property $cycle_id
 * @property $formula_name
 * @property $formula_description
 * @property $formula
 * @property $created_by
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Formula extends Model
{

    protected $perPage = 200;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'formulas';
    protected $fillable = [
        'cycle_id',
        'formula_name',
        'formula_description',
        'formula',
        'is_system',
        'created_by'
    ];

    protected function getFormulasToSelect($cycleId)
    {
        return $this->where('cycle_id', $cycleId)->pluck('formula_name', 'id');
    }

    protected function getFormulaName($formulaId)
    {
        $cycle = Cycle::getCurrentCycle();

        return $this->where('cycle_id', $cycle->id)
            ->where('id', $formulaId)
            ->first();

        //dd($rows);
    }

    protected function formulaParsing($formulaId, $formulaInfo, $row, $cycle, $currentRow, $consolidatedRow, $field_column_name, $equivalences)
    {
        //Log::info("Formula Parsing: " . $formulaInfo->id);
        //var_dump($formulaInfo->id);
        //dd($formulaInfo);
        if ($formulaInfo->formula == "{self:cycle_id}") {
            return $cycle->id;
        }
        if ($formulaInfo->formula == "{self:teacher_id}") {
            return $row->teacher_id;
        }
        if ($formulaInfo->formula == "{self:student_id}") {
            return $row->student_id;
        }
        if ($formulaInfo->formula_name == "Teacher Name") {
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            //dd($values);

            $side1 = preg_split('/(~)/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $side2 = preg_split('/(~)/', $values[3], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $table2Field2 = explode("|", $side2[2]); // field/table
            //
            $tmp = explode('->', $values[1]);
            $column1 = trim($tmp[1]);
            $tmp = explode('->', $values[4]);
            $column2 = trim($tmp[1]);

            $tablesMappings = TablesMapping::where("cycle_id", $cycle->id)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->first();
            $field1Id = $field2Id = 0;
            if ($tablesMappings) {
                $field1Id = $tablesMappings->id;
            }
            $tablesMappings = TablesMapping::where("cycle_id", $cycle->id)
                ->where('table_id', $table2Field2[1])
                ->where('column', $column2)
                ->first();
            if ($tablesMappings) {
                $field2Id = $tablesMappings->id;
            }


            //dd($tmp,$columnA,$columnB);
            $firstName = "";
            $lastName = "";
            $result = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $row->student_id)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->first();
            if ($result) {
                $firstName = $result->field_value;
            }
            $result = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $row->student_id)
                ->where('table_id', $table2Field2[1])
                ->where('column', $column2)
                ->first();
            if ($result) {
                $lastName = $result->field_value;
            }

            return $firstName . " " . $lastName;
        }
        if ($formulaInfo->formula_name == "Get Program Name") {
            /**
             * {remove:"Independent Study - "}:999|2~]{Student Accounts-> Column_H -> (Enrollments1) Program}
             *      array:3 [▼ // app\Models\Formula.php:100
             *          0 => "{remove:"Independent Study - "}"
             *          1 => ":[~999|2~]"
             *          2 => "{Student Accounts-> Column_H -> (Enrollments1) Program}"
             *
             */
            //
            //$formula = '{remove:"Independent Study - "}:999|2~]{Student Accounts-> Column_H -> (Enrollments1) Program}';
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            $side1 = preg_split('/(~)/', $values[1], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            $table1Field1 = explode("|", $side1[2]); // field/table

            //
            $tmp = explode('->', $values[2]);
            $column1 = trim($tmp[1]);

            //dd($table1Field1[1],$column1);
            $tablesMappings = TablesMapping::where("cycle_id", $cycle->id)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->first();
            $field1Id = 0;
            $value = "";
            if ($tablesMappings) {
                $field1Id = $tablesMappings->id;
            }
            $result = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $row->student_id)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->first();
            //dd($result);
            if ($result) {
                $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                preg_match('/"(.*?)\"/s', $tmp0[0], $matches1);
                //dd($result->field_value,$matches1[1]);
                $value = str_replace($matches1[1], "", $result->field_value);
            }
            return $value;
        }

        $processFormula = 0;
        $validators = ['getCaasppMath01'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);
        if (
            $processFormula == 1
        ) {
            // this formula is based on multiple records
            //{getCaasppMath01}:[~999|18~]{CAASPP-> Column_A -> RecordType}:[~999|18~]{CAASPP-> Column_EV -> AchievementLevels}

            $formula = self::replaceChars($formulaInfo->formula);
            //$tableId =  MasterTables::getTableId('caaspps')->id;
            //dd($tableId);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp = explode('->', $values[2]);
            //Log::info("Formula " . $formulaInfo->id,$values);
            $column = trim($tmp[1]);
            $side1 = preg_split('/(~)/', $values[1], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $table1Field1 = explode("|", $side1[2]); // field/table
            //dd($formulaInfo, $column,$values,$side1,$table1Field1);
            $tableId =  $table1Field1[1];
            //dd($tableId);
            $multipleRowNumbers = MultiTableFields::getAllTheRowsForRecordThatHasMultipleRecords($cycle->id, $tableId, $row->student_id, $column);
            //dd($multipleRowNumbers);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            foreach ($multipleRowNumbers as $multipleRowNumber) {

                $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, $multipleRowNumber->row_number, $consolidatedRow);
                $value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[3]), trim($values[4]), $cycle, $row, $currentRow, $multipleRowNumber->row_number, $consolidatedRow);
                //dd($values, $value1, $value3, $row->student_id);
                if ($value1 == -876) {
                    return "";
                }
                if ($value1 == "01" || $value1 == "1") {
                    return $value3;
                    //dd($value1);
                    //return $value1;
                }
            }
            return null;
        }

        $processFormula = 0;
        $validators = ['getCaasppReading02'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);

        if (
            $processFormula == 1
        ) {

            //{getCaasppMath01}:[~999|18~]{CAASPP-> Column_A -> RecordType}:[~999|18~]{CAASPP-> Column_EV -> AchievementLevels}
            $formula = self::replaceChars($formulaInfo->formula);
            //Log::info("Formula..: " . $formulaInfo->id . " Student Id " . $row->student_id);
            //$tableId =  MasterTables::getTableId('caaspps')->id;
            //dd($formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp = explode('->', $values[2]);
            $column = trim($tmp[1]);
            $side1 = preg_split('/(~)/', $values[1], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $table1Field1 = explode("|", $side1[2]); // field/table
            //dd($formulaInfo, $column,$values,$side1,$table1Field1);
            $tableId =  $table1Field1[1];

            $multipleRowNumbers = MultiTableFields::getAllTheRowsForRecordThatHasMultipleRecords($cycle->id, $tableId, $row->student_id, $column);
            //dd($formulaInfo, $column);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            foreach ($multipleRowNumbers as $multipleRowNumber) {
                $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, $multipleRowNumber->row_number, $consolidatedRow);
                $value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[3]), trim($values[4]), $cycle, $row, $currentRow, $multipleRowNumber->row_number, $consolidatedRow);
                //dd($values,$value1, $value3,$row->student_id,$tmp);
                if ($value1 == -876) {
                    return "";
                }
                if ($value1 == "02" || $value1 == "2") {
                    //return $value1;
                    return $value3;
                }
            }
            return null;
        }

        $processFormula = 0;
        $validators = ['getEquivalences'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);

        if (
            $processFormula == 1
        ) {

            //{getEquivalences}:999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);

            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, null, $consolidatedRow);
            //$equivalences = Consolidate3::columnACADEquivalences();

            //var_dump($values,$formulaInfo->id, $value1);
            //echo "<br>";
            //dd($values,$equivalences,$value1);
            $equivalences = null;
            $return = 0;
            if ($value1 == -876 || $value1 == 0) {
                $return = "";
            } else {
                $equivalences = Equivalences::where('equivalence', $value1)->first();
                //dd($equivalences);
                if (!$equivalences) {
                    $return = "";
                } else {
                    $return = $equivalences->value;
                    //$tempTableName2 = "consolidate3s_attributes_" . $cycle->id;
                    //$tempTableModel2 = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName2);
                    // $dataAttributes = [
                    //     'cycle_id' => $cycle->id,
                    //     'student_id' => $row->student_id,
                    //     'consolidate_id' => $consolidatedRow->id,
                    //     'column_name' => $field_column_name,
                    //     'formula_id' => $formulaInfo->id,
                    //     'equivalence_id' => $equivalences->id,
                    //     'field_value' => $equivalences->equivalence,
                    //     'attribute_1' => $equivalences->color,
                    //     'attribute_2' => null,
                    //     'attribute_3' => null,
                    //     'attribute_4' => null,
                    //     'attribute_5' => null,
                    // ];
                    // $tempTableModel2->create($dataAttributes);
                }
            }

            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return $return;
        }
        if (
            $formulaInfo->formula_name == "Get iReady Math BOY Growth Equivalence" ||
            $formulaInfo->formula_name == "Get iReady Reading BOY Growth Equivalence" ||
            $formulaInfo->formula_name == "Get easyCBM Fall Growth Equivalence" ||
            $formulaInfo->formula_name == "Get iReady Math Mid Year Growth Equivalence" ||
            $formulaInfo->formula_name == "Get iReady Reading Mid Year Growth Equivalence" ||
            $formulaInfo->formula_name == "Get iReady Math EOY Growth Equivalence" ||
            $formulaInfo->formula_name == "Get iReady Reading EOY Growth Equivalence"
        ) {
            //{getEquivalences}:999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);

            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, null, $consolidatedRow);
            //$equivalences = Consolidate3::columnACADEquivalences();
            //dd($values,$equivalences,$value1);

            $return = 0;
            if ($value1 == -876) {
                $return = "";
            } else if (isset($equivalences[$value1])) {
                $return = $equivalences[$value1];
            } else {
                return "";
            }
            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return $return;
        }
        //dd($formulaInfo);
        //dd(strtolower($formulaInfo->formula_name));
        $processFormula = 0;
        //preg_match('/(subtract)(substract)(add)(multiply)(dividedby)/', strtolower($formulaInfo->formula_name), $matches, PREG_OFFSET_CAPTURE);
        $validators = ['substract', 'subtract', 'add', 'multiply', 'dividedby'];
        $processFormula = $this->validatePreg(strtolower($formulaInfo->formula_name), $validators);
        //dd($validators,$formulaInfo,$processFormula);
        if ($processFormula == 0) {
            $processFormula = $this->validatePreg(strtolower($formulaInfo->formula), $validators);
        }
        if (
            $processFormula == 1
        ) {
            //{getEquivalences}:[~999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}

            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            //dd($values,$tmp0);
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[0]), trim($values[1]), $cycle, $row, $currentRow, null, $consolidatedRow);
            $value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[3]), trim($values[4]), $cycle, $row, $currentRow, null, $consolidatedRow);

            //dd($formulaInfo->id,$value1,$value3);
            //dd($currentRow,$formulaInfo,$formula,$values,$value1,$value3);

            //$equivalences = Consolidate3::columnACADEquivalences();
            if ($value1 == -876 || $value3 == -876) {
                $return = "";
            } else {
                $return = $this->performFormulaOperation($values, $value1, $value3, $currentRow, $formulaInfo);
            }
            //dd($value1, $value3,$return);
            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return $return;
        }
        $processFormula = 0;
        $validators = ['concatenate'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);

        //preg_match('/(concatenate)/', strtolower($formulaInfo->formula_name), $matches, PREG_OFFSET_CAPTURE);
        if (
            $processFormula == 1
        ) {
            //{getEquivalences}:[~999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            //dd($values,$tmp0);
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, null, $consolidatedRow);
            $value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[4]), trim($values[5]), $cycle, $row, $currentRow, null, $consolidatedRow);

            //var_dump($formulaInfo->id,$value1,$value3);
            //dd($currentRow,$formulaInfo,$formula,$values,$value1,$value3);

            $return = $value1 . " " . $value3;
            //dd($value1, $value3,$return);
            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return $return;
        }

        $processFormula = 0;
        $validators = ['getMultipleValues'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);

        if (
            $processFormula == 1
        ) {
            //{getEquivalences}:[~999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            //dd($values,$tmp0);
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, null, $consolidatedRow);
            //$value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[4]), trim($values[5]), $cycle, $row, $currentRow, null, $consolidatedRow);
            $value3 = "";
            //var_dump($formulaInfo->id,$value1,$value3);
            //dd($currentRow,$formulaInfo,$formula,$values,$value1,$value3);

            $return = $value1 . " " . $value3;
            //dd($value1, $value3,$return);
            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return $return;
        }

        $validators = ['evaluateStudentAccountColumnK'];
        //dd($validators);
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);
        //preg_match('/(concatenate)/', strtolower($formulaInfo->formula_name), $matches, PREG_OFFSET_CAPTURE);
        if (
            $processFormula == 1
        ) {
            //{getEquivalences}:[~999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            //dd($values,$tmp0);
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, null, $consolidatedRow);

            //dd($formulaInfo->id,$value1);
            //dd($currentRow,$formulaInfo,$formula,$values,$value1,$value3);
            if ($value1 == -876) {
                return "";
            }
            if (strtolower($value1) == strtolower("English or American Sign Language Only")) {
                return "";
            } else {
                return $value1;
            }
            //dd($value1, $value3,$return);
            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return "";
        }

        $validators = ['evaluateStudentAccountColumnN'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);
        //preg_match('/(concatenate)/', strtolower($formulaInfo->formula_name), $matches, PREG_OFFSET_CAPTURE);
        if (
            $processFormula == 1
        ) {
            //{getEquivalences}:[~999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            //dd($values,$tmp0);
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, null, $consolidatedRow);

            //var_dump($formulaInfo->id,$value1,$value3);
            //dd($currentRow,$formulaInfo,$formula,$values,$value1,$value3);
            if ($value1 == -876) {
                return "";
            }
            if (strtolower($value1) == strtolower("Sheline-Biernat")) {
                $return = "Y";
            } else {
                $return = $value1;
            }
            //dd($value1, $value3,$return);
            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return $return;
        }

        // check if the student exists on any table
        //if ($formulaInfo->formula == "{self:student_id}") {
        if ($formulaInfo->formula_name == "check if student exists") {
            //{getCaasppMath01}:[~999|18~]{CAASPP-> Column_A -> RecordType}:[~999|18~]{CAASPP-> Column_EV -> AchievementLevels}
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            //dd($values,$tmp0);
            $value1 = $this->resolveGetStudent($formulaInfo, trim($values[2]), $cycle, $row);
            //$value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[3]), trim($values[4]), $cycle, $row, $currentRow);
            //dd($values,$tmp0,$value1);
            //dd($values,$value1, $formulaInfo,$row->student_id);
            if ($value1 == "Y" || $value1 == "") {
                return $value1;
            }
            return null;
        }
    }

    protected function performFormulaOperation($values, $value1, $value2, $currentRow, $formulaInfo)
    {
        //dd($values,$value1,$value2);

        // if (!(is_numeric($value1) && is_numeric($value2))) {
        //     Log::info($formulaInfo->formula_name . ' Student id: ' . $currentRow['student_id'] . " -> No numeric values for operation ");
        //     return 0;
        // }
        if (trim($values[2]) == "{+}") {
            return $value1 + $value2;
        } else if (trim($values[2]) == "{-}") {
            return $value1 - $value2;
        } else if (trim($values[2]) == "{*}") {
            return $value1 * $value2;
        } else if (trim($values[2]) == "{/}") {
            if ($value2 != 0) {
                return $value1 / $value2;
            } else {
                return 0;
            }
        } else {
            Log::info($formulaInfo->formula_name + ' Studemt id: ' + $currentRow['student_id'] . " -> No valid operation ");
            return -998;
        }
    }

    protected function getConsolidatedValues($formulaName, $studentId, $cycle)
    {
        if (
            $formulaName == "Get CAASPP Math" ||
            $formulaName == "Get CAASPP Reading"
        ) {
            //{getEquivalences}:999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}

            $formulaInfo = Formula::where("cycle_id", $cycle->id)
                ->where('formula_name', $formulaName)
                ->first();
            if (!$formulaInfo) {
                return null;
            }
            $formula = self::replaceChars($formulaInfo->formula);
            $formulaArray = explode(";", $formula);

            // get the value of the formula base
            $values = preg_split('/({[^}]*})/', $formulaArray[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            preg_match('/->(.*?)->/', $formula, $matches);
            if (isset($matches[1])) {
                $tempTableName = "consolidated_cycle_" . $cycle->id;
                if (!Schema::hasTable($tempTableName)) {
                    session()->flash('error-message', 'No Data for that cycle ');
                    return redirect("/admin/consolidate-view");
                }
                $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
                $consolidatedRow = $tempTableModel->where("student_id", $studentId)->first();
                //dd($consolidatedRow,$matches[1]);
                $result = null;
                if ($consolidatedRow) {
                    $result = $consolidatedRow->{trim($matches[1])};
                    $result = preg_replace('/[^0-9a-zA-Z_]/', "", $result);
                }
            }
            //dd($formulaArray);
            unset($formulaArray[0]);
            foreach ($formulaArray as $formula) {

                if (trim($formula) == "") {
                    continue;
                }
                $formula = trim($formula);
                preg_match('/==(.*?)then/', $formula, $matches);
                if (isset($matches[1])) {
                    $valueToCompare = preg_replace('/[^0-9a-zA-Z_]/', "", $matches[1]);
                    if ($result == $valueToCompare) {
                        return trim(substr($formula, strpos($formula, 'then') + 4));
                    }
                }
            }
            return null;
        }
    }

    static public function replaceChars($string)
    {
        $string = str_replace("&lt;", "<", $string);
        $string = str_replace("&gt;", ">", $string);
        $string = str_replace("&nbsp;", "", $string);
        return $string;
    }

    /**
     * getting :[~999|18~]
     * returning: value
     */
    protected function resolveTwoPartsFormula($formulaInfo, $value1, $value2, $cycle, $row, $currentRow, $rowNumber = false, $consolidatedRow)
    {
        if (!$currentRow) {
            Log::info('Error in formula... ' . $formulaInfo . " Student Id:----");
            return -996;
        }
        if ($currentRow['student_id'] == 0) {
            Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
            return -995;
        }
        $values = preg_split('/({[^}]*})/', $value2, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side1 = preg_split('/(~)/', $value1, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        //dd($formulaInfo->formula,str_contains($formulaInfo->formula,'{getEquivalences}'));

        $validators = ['getEquivalences'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);

        if ($return == 1) {
            if (!isset($side1[2])) {
                return 0; // no formulad defined
            }
            //var_dump($formulaInfo->id,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //dd($tmp,$table1Field1[1]);
            $column1 = trim($tmp[1]);
            $studId = $row->student_id;
            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $result = $query->first();
            //dd($result);
            $value = -876;
            if ($result) {
                $value = $result->field_value;
            }
            //dd($value);
            return $value;
        }
        $validators = ['getCaasppMath01'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);
        //dd($validators,$formulaInfo->formula,$return);
        if ($return == 1) {

            if (!isset($side1[2])) {
                return 0; // no formulad defined
            }
            //var_dump($formulaInfo->id,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //var_dump($tmp,$table1Field1[1]);
            $column1 = trim($tmp[1]);
            $studId = $row->student_id;

            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            //->where(trim($column1), '01');
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $result = $query->first();
            // if ($column1 != 'Column_A') {
            //     dd($studId,$table1Field1[1],$column1,$rowNumber,$result);
            // }
            //dd($result);
            $value = -876;
            if ($result) {
                $value = $result->field_value;
            }
            //dd($value);
            return $value;
        }

        $validators = ['getCaasppReading02'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);

        if ($return == 1) {

            if (!isset($side1[2])) {
                return 0; // no formulad defined
            }
            //var_dump($formulaInfo->id,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //dd($tmp,$table1Field1[1]);
            $column1 = trim($tmp[1]);
            $studId = $row->student_id;
            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            //->where(trim($table1Field1[1]), '02');
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $result = $query->first();
            //dd($result);
            $value = -876;
            if ($result) {
                $value = $result->field_value;
            }
            //dd($value);
            return $value;
        }

        $validators = ['evaluateStudentAccountColumnK'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);
        //dd($return);
        if ($return == 1) {

            if (!isset($side1[2])) {
                return 0; // no formulad defined
            }
            //var_dump($formulaInfo->id,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //dd($tmp,$table1Field1[1]);
            $column1 = trim($tmp[1]);
            $studId = $row->student_id;
            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            //->where(trim($table1Field1[1]), '02');
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $result = $query->first();
            //dd($result);
            $value = -876;
            if ($result) {
                $value = $result->field_value;
            }
            //dd($value);
            return $value;
        }


        $validators = ['evaluateStudentAccountColumnN'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);
        //dd($return);
        if ($return == 1) {

            if (!isset($side1[2])) {
                return 0; // no formulad defined
            }
            //var_dump($formulaInfo->id,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //dd($tmp,$table1Field1[1]);
            $column1 = trim($tmp[1]);
            $studId = $row->student_id;
            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            //->where(trim($table1Field1[1]), '02');
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $result = $query->first();
            //dd($result);
            $value = -876;
            if ($result) {
                $value = $result->field_value;
            }
            //dd($value);
            return $value;
        }

        $validators = ['{Consolidated'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);

        if ($return == 1) {

            //var_dump($formulaInfo->formula_name,$values,$value1,$value2);
            // if (!isset($side1[2])) {
            //     Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
            //     return -992;
            // }
            //dd($formulaInfo,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //var_dump($tmp);
            //dd($table1Field1,$currentRow,$values,$side1);
            //var_dump($formulaInfo->formula_name,$values,$side1,$currentRow['student_id']);
            if ($table1Field1[1] == 999) {
                $consolidatedFields = $this->getConsolidatedFields($cycle);
                $consolidatedFieldsFlipped = array_flip($consolidatedFields);
                //dd($consolidatedFieldsFlipped,$tmp);
                if (isset($consolidatedFieldsFlipped[trim($tmp[1])])) {
                    $tempTableName = "consolidated_cycle_" . $cycle->id;
                    if (!Schema::hasTable($tempTableName)) {
                        return -876;
                    }
                    //$tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
                    //var_dump($currentRow);
                    //dd($currentRow);

                    //$consolidatedRow = $tempTableModel->where("student_id", $currentRow['student_id'])->first();
                    //dd($consolidatedRow);
                    if (isset($consolidatedRow[$currentRow['student_id']])) {
                        //if ($consolidatedRow) {
                        if (!isset($consolidatedRow[$currentRow['student_id']][trim($tmp[1])])) {
                            return -876;
                        }
                        // if (is_null($consolidatedRow->{trim($tmp[1])})) {
                        //     return -876;
                        // }
                        //return (float)$consolidatedRow->{trim($tmp[1])};
                        if ($consolidatedRow[$currentRow['student_id']][trim($tmp[1])] == "") {
                            return -876;
                        }
                        return (float)$consolidatedRow[$currentRow['student_id']][trim($tmp[1])];
                    } else {
                        return -876;
                    }
                    //var_dump($consolidatedRow->{trim($tmp[1])});
                    //dd($consolidatedRow[$consolidatedFieldsFlipped[trim($tmp[1])]]);
                }
                return null;
            }

            //dd($values,$side1,$table1Field1,$tmp[1]);
            //dd($tmp);
            if (!isset($tmp[1])) {
                Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
                return -993;
            }
            $column1 = trim($tmp[1]);
            $tablesMappings = TablesMapping::where("cycle_id", $cycle->id)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->first();
            $field1Id = 0;
            if ($tablesMappings) {
                $field1Id = $tablesMappings->id;
            } else {
                if (empty($matches)) {
                    Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
                    return -997;
                }
            }
            $value = "";
            $studId = $row->student_id;
            //$studId = 1351902554;

            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $result = $query->first();
            if ($result) {
                $value = $result->field_value;
            }
            return $value;
        }

        $validators = ['{concatenate}'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);

        if ($return == 1) {

            //var_dump($formulaInfo->formula_name,$values,$value1,$value2);
            // if (!isset($side1[2])) {
            //     Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
            //     return -992;
            // }
            //dd($formulaInfo,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //var_dump($tmp);
            //dd($table1Field1,$currentRow,$values,$side1);
            //var_dump($formulaInfo->formula_name,$values,$side1,$currentRow['student_id']);
            if ($table1Field1[1] == 999) {
                $consolidatedFields = $this->getConsolidatedFields($cycle);
                $consolidatedFieldsFlipped = array_flip($consolidatedFields);
                //dd($consolidatedFieldsFlipped,$tmp);

                if (isset($consolidatedFieldsFlipped[trim($tmp[1])])) {
                    $tempTableName = "consolidated_cycle_" . $cycle->id;
                    if (!Schema::hasTable($tempTableName)) {
                        return -876;
                    }
                    $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
                    //var_dump($currentRow);
                    //dd($currentRow);
                    //$consolidatedRow = $tempTableModel->where("student_id", $currentRow['student_id'])->first();
                    //dd($consolidatedRow);

                    if (isset($consolidatedRow[$currentRow['student_id']])) {
                        //if ($consolidatedRow) {
                        if (!isset($consolidatedRow[$currentRow['student_id']][trim($tmp[1])])) {
                            return -876;
                        }

                        //return (float)$consolidatedRow->{trim($tmp[1])};
                        return $consolidatedRow[$currentRow['student_id']][trim($tmp[1])];
                    } else {
                        return -876;
                    }
                    //var_dump($consolidatedRow->{trim($tmp[1])});
                    //dd($consolidatedRow[$consolidatedFieldsFlipped[trim($tmp[1])]]);
                }
                return null;
            }

            //dd($values,$side1,$table1Field1,$tmp[1]);
            //dd($tmp);
            if (!isset($tmp[1])) {
                Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
                return -993;
            }
            $column1 = trim($tmp[1]);
            $tablesMappings = TablesMapping::where("cycle_id", $cycle->id)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->first();
            $field1Id = 0;
            if ($tablesMappings) {
                $field1Id = $tablesMappings->id;
            } else {
                if (empty($matches)) {
                    Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
                    return -997;
                }
            }
            $value = "";
            $studId = $row->student_id;
            //$studId = 1351902554;

            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $result = $query->first();
            if ($result) {
                $value = $result->field_value;
            }
            return $value;
        }

        $validators = ['{getMultipleValues}'];
        $return = Formula::validatePreg($formulaInfo->formula, $validators);

        if ($return == 1) {

            //var_dump($formulaInfo->formula_name,$values,$value1,$value2);
            // if (!isset($side1[2])) {
            //     Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
            //     return -992;
            // }
            //dd($formulaInfo,$side1);
            $table1Field1 = explode("|", $side1[2]); // field/table
            $values[0] = str_replace("{", "", $values[0]);
            $values[0] = str_replace("}", "", $values[0]);
            $tmp = explode('->', $values[0]);
            //var_dump($tmp);
            //dd($table1Field1,$currentRow,$values,$side1);
            //var_dump($formulaInfo->formula_name,$values,$side1,$currentRow['student_id']);
            if ($table1Field1[1] == 999) {
                $consolidatedFields = $this->getConsolidatedFields($cycle);
                $consolidatedFieldsFlipped = array_flip($consolidatedFields);
                //dd($consolidatedFieldsFlipped,$tmp);

                if (isset($consolidatedFieldsFlipped[trim($tmp[1])])) {
                    $tempTableName = "consolidated_cycle_" . $cycle->id;
                    if (!Schema::hasTable($tempTableName)) {
                        return -876;
                    }
                    $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
                    //var_dump($currentRow);
                    //dd($currentRow);
                    //$consolidatedRow = $tempTableModel->where("student_id", $currentRow['student_id'])->first();
                    //dd($consolidatedRow);

                    if (isset($consolidatedRow[$currentRow['student_id']])) {
                        //if ($consolidatedRow) {
                        if (!isset($consolidatedRow[$currentRow['student_id']][trim($tmp[1])])) {
                            return -876;
                        }

                        //return (float)$consolidatedRow->{trim($tmp[1])};
                        return $consolidatedRow[$currentRow['student_id']][trim($tmp[1])];
                    } else {
                        return -876;
                    }
                    //var_dump($consolidatedRow->{trim($tmp[1])});
                    //dd($consolidatedRow[$consolidatedFieldsFlipped[trim($tmp[1])]]);
                }
                return null;
            }

            //dd($values,$side1,$table1Field1,$tmp[1]);
            //dd($tmp);
            if (!isset($tmp[1])) {
                Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
                return -993;
            }
            $column1 = trim($tmp[1]);
            $tablesMappings = TablesMapping::where("cycle_id", $cycle->id)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->first();
            $field1Id = 0;
            if ($tablesMappings) {
                $field1Id = $tablesMappings->id;
            } else {
                if (empty($matches)) {
                    Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
                    return -997;
                }
            }
            $value = "";
            $studId = $row->student_id;
            //$studId = 1351902554;

            $query = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('student_id', $studId)
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1);
            if ($rowNumber) {
                $query->where('row_number', $rowNumber);
            }
            $results = $query->get();
            //dd($result);
            $value = "";
            foreach ($results as $result) {
                $value .= $result->field_value . "<br>\r\n";
            }
            //dd($value);
            return $value;
        }
    }

    protected function resolveGetStudent($formulaInfo, $value1,  $cycle, $row)
    {
        //dd($row);
        //dd($formulaInfo,$value1, );
        $values = preg_split('/({[^}]*})/', $value1, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side1 = preg_split('/(~)/', $value1, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        //dd($values,$side1);
        if (!isset($side1[2])) {
            Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
            return -992;
        }
        $table1Field1 = explode("|", $side1[2]); // field/table
        //dd($formulaInfo,$table1Field1);
        //var_dump($formulaInfo->formula_name,$values,$side1,$currentRow['student_id']);
        // if ($table1Field1[1] == 999) {
        //     $consolidatedFields = $this->getConsolidatedFields($cycle);
        //     if (isset($currentRow[$consolidatedFields[$keys1[0]]])) {
        //         return $currentRow[$consolidatedFields[$keys1[0]]];
        //     }
        //     return null;
        // }
        $tmp = explode('->', $values[0]);
        //dd($tmp);
        //dd($values,$side1,$table1Field1,$tmp[1]);
        if (!isset($tmp[1])) {
            Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
            return -993;
        }
        $column1 = trim($tmp[1]);

        $tablesMappings = TablesMapping::where("cycle_id", $cycle->id)
            ->where('table_id', $table1Field1[1])
            ->where('column', $column1)
            ->first();
        $field1Id = 0;
        if ($tablesMappings) {
            $field1Id = $tablesMappings->id;
        } else {
            if (empty($matches)) {
                Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
                return -997;
            }
        }
        $value = "";
        $result = MultiTableFields::where("cycle_id", $cycle->id)
            ->where('table_id', $table1Field1[1])
            ->where('column', $column1)
            ->where('student_id', $row->student_id)
            ->first();
        //dd($result,$column1,$row->student_id);
        if ($result) {
            $value = "Y";
        }

        return $value;
        //dd($value1, $value2, $values, $side1, $table1Field1, $tmp, $column1, $field1Id, $row, $value);
    }

    protected function buildSiteVariables(): array
    {
        $cycle = Cycle::getCurrentCycle();
        $tmpVariables = ConsolidateMapping::getTableFields();
        $siteVariables = [];
        foreach ($tmpVariables as $row) {
            //$siteVariables[] = "[~" . $row->id . "|" . $row->table_id . "~]{" . $row->field_name . "}";
            $siteVariables[] = "[~999|" . $row->table_id . "~]{" . $row->field_name . "}";
        }
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)->orderBy('screen_sort')->get();
        foreach ($fields as $row) {
            //$siteVariables[] = "[~" . $row->id . "|999~]{Consolidated -> " . $row->column_name . " -> " . $row->column_description .  "}";
            $siteVariables[] = "[~999|999~]{Consolidated -> " . $row->column_name . " -> " . $row->column_description .  "}";
        }
        return $siteVariables;
    }
    protected function buildSiteTables(): array
    {
        $cycle = Cycle::getCurrentCycle();
        $tmpVariables = MasterTables::where('cycle_id', $cycle->id)
            ->orderBy('table_alias')
            ->get();
        $siteTables = [];
        foreach ($tmpVariables as $row) {
            //$siteVariables[] = "[~" . $row->id . "|" . $row->table_id . "~]{" . $row->field_name . "}";
            $siteTables[] = "|" . $row->id . "~]{" . $row->table_alias . "}";
        }
        return $siteTables;
    }

    protected function buildSiteFormulas(): array
    {
        $cycle = Cycle::getCurrentCycle();
        $tmpFormulas = Formula::where("cycle_id", $cycle->id)->get();
        $siteFormulas = [];
        foreach ($tmpFormulas as $row) {
            //$siteVariables[] = "[~" . $row->id . "|" . $row->table_id . "~]{" . $row->field_name . "}";
            $siteFormulas[] = "[formula|~" . $row->id . "~]{" . $row->formula_name . "}";
        }
        return $siteFormulas;
    }

    protected function getConsolidatedFields($cycle)
    {
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)->orderBy('screen_sort')->get();
        foreach ($fields as $row) {
            $siteVariables[$row->id] = $row->column_name;
        }
        return $siteVariables;
    }

    protected function getConsolidatedFieldsWithDescription($cycle)
    {
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)->orderBy('screen_sort')->get();

        if ($fields->isEmpty()) {
            return [];
        }
        foreach ($fields as $row) {
            if ($row->column_description == 'id' || $row->column_description == 'teacher_id' || $row->column_description == 'cycle_id') {
                continue;
            }
            $siteVariables[$row->id] = [$row->column_name, $row->column_description, $row->section_id];
        }
        return $siteVariables;
    }

    protected function getConsolidatedBasicFieldsWithDescription($cycle)
    {
        $basicFields = [
            'column_d',
            'column_e',
            'column_f',
        ];
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)
            ->whereIn('column_name', $basicFields)
            ->orderBy('screen_sort')
            ->get();

        if ($fields->isEmpty()) {
            return [];
        }
        foreach ($fields as $row) {
            if ($row->column_description == 'id' || $row->column_description == 'teacher_id' || $row->column_description == 'cycle_id') {
                continue;
            }
            $siteVariables[$row->id] = [$row->column_name, $row->column_description, $row->section_id];
        }
        return $siteVariables;
    }

    protected function cloneFormulaIntoNewCycle($cycleFrom, $cycleTo, $clonedTables)
    {
        $clonedFormulas = [];
        $this->where("cycle_id", $cycleTo)->delete(); // remove all formulas for new cycle
        $formulas = $this->where("cycle_id", $cycleFrom)
            ->get();
        foreach ($formulas as $formula) {
            $newFormula = $formula->replicate();
            $newFormula->cycle_id = $cycleTo;
            $tmpFormula = $formula->formula;
            foreach ($clonedTables as $oldTable => $newTable) {
                $tmpFormula = str_replace("[~999|" . $oldTable . "~]", "[~999|" . $newTable . "~]", $tmpFormula);
            }
            $newFormula->formula = $tmpFormula;
            $newFormula->save();
            $clonedFormulas[$formula->id] = $newFormula->id;
        }
        return $clonedFormulas;
    }

    protected function validatePreg($formulaString, array $validators)
    {
        //dd($formulaInfo,$validators);

        foreach ($validators as $validator) {
            //var_dump('/(' . $validator .')/',$formulaString);
            preg_match_all('/(' . $validator . ')/', ($formulaString), $matches, PREG_SET_ORDER, 0);
            //dd($matches);
            if (!empty($matches)) {
                return 1;
            }
        }
        return 0;
    }


    protected function parseForumula($formulaInfo, $cycle, $equivalences, $students)
    {
        //dd($formulaInfo);
        if ($formulaInfo->formula == "{self:cycle_id}") {
            $this->parseCycleId($formulaInfo, $cycle, $equivalences, $students);
        }
        if ($formulaInfo->formula == "{self:teacher_id}") {
            $this->parseTeacherId($formulaInfo, $cycle, $equivalences, $students);
        }
        if ($formulaInfo->formula == "{self:student_id}") {
            $this->parseStudentId($formulaInfo, $cycle, $equivalences, $students);
        }
        if ($formulaInfo->formula_name == "Teacher Name") {
            $formula = $this->parseTeacherName($formulaInfo, $cycle, $equivalences, $students);
        }
        if ($formulaInfo->formula_name == "Get Program Name") {
            $formula = $this->parseProgramName($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = ['getCaasppMath01'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);
        if ($processFormula == 1) {
            $formula = $this->parseCaasppMathReading01($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = ['getCaasppReading02'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);
        if ($processFormula == 1) {
            $formula = $this->parseCaasppMathReading01($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = ['getEquivalences'];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);
        if ($processFormula == 1) {
            $formula = $this->parseGetEquivalences($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = [
            "Get iReady Math BOY Growth Equivalence",
            "Get iReady Reading BOY Growth Equivalence",
            "Get easyCBM Fall Growth Equivalence",
            "Get iReady Math Mid Year Growth Equivalence",
            "Get iReady Reading Mid Year Growth Equivalence",
            "Get iReady Math EOY Growth Equivalence",
            "Get iReady Reading EOY Growth Equivalence",
        ];
        $processFormula = $this->validatePreg($formulaInfo->formula, $validators);
        if ($processFormula == 1) {
            $formula = $this->parseGrowthEquivalence($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = ['substract', 'subtract', 'add', 'multiply', 'dividedby'];
        $processFormula = $this->validatePreg(strtolower($formulaInfo->formula_name), $validators);
        if ($processFormula == 1) {
            $formula = $this->parseMathOperations($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = ['concatenate'];
        $processFormula = $this->validatePreg(strtolower($formulaInfo->formula_name), $validators);
        if ($processFormula == 1) {
            $formula = $this->parseConcatenate($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = ['getMultipleValues'];
        $processFormula = $this->validatePreg(strtolower($formulaInfo->formula_name), $validators);
        if ($processFormula == 1) {
            $formula = $this->parseMultipleValues($formulaInfo, $cycle, $equivalences, $students);
        }
        $validators = ['evaluateStudentAccountColumnK'];
        $processFormula = $this->validatePreg(strtolower($formulaInfo->formula_name), $validators);
        if ($processFormula == 1) {
            $formula = $this->parseEvaluateStudent($formulaInfo, $cycle, $equivalences, $students, "Column_K");
        }
        $validators = ['evaluateStudentAccountColumnN'];
        $processFormula = $this->validatePreg(strtolower($formulaInfo->formula_name), $validators);
        if ($processFormula == 1) {
            $formula = $this->parseEvaluateStudent($formulaInfo, $cycle, $equivalences, $students, "Column_N");
        }
        $validators = ['check if student exists'];
        $processFormula = $this->validatePreg(strtolower($formulaInfo->formula_name), $validators);
        if ($processFormula == 1) {
            $formula = $this->parseCheckStudentExists($formulaInfo, $cycle, $equivalences, $students, "Column_N");
        }


    }
    protected function parseCycleId($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle) {
            $data = [];
            foreach ($students as $studentRow) {
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $cycle->id,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }
    protected function parseTeacherId($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle) {
            $data = [];
            foreach ($students as $studentRow) {
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $studentRow->teacher_id,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }
    protected function parseStudentId($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle) {
            $data = [];
            foreach ($students as $studentRow) {
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $studentRow->student_id,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }


    protected function parseProgramName($formulaInfo, $cycle, $equivalences, $studentRows)
    {

        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $side1 = preg_split('/(~)/', $values[1], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table

        $tmp = explode('->', $values[2]);
        $column1 = trim($tmp[1] ?? "");

        $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        preg_match('/"(.*?)\"/s', $tmp0[0], $matches1);

        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $column1, $matches1) {
            $data = [];

            foreach ($students as $studentRow) {

                $value = $this::findValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }

    protected function parseTeacherName($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $side1 = preg_split('/(~)/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side2 = preg_split('/(~)/', $values[3], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table
        $table2Field2 = explode("|", $side2[2]); // field/table

        $tmp = explode('->', $values[1]);
        $column1 = trim($tmp[1] ?? "");
        $tmp = explode('->', $values[4]);
        $column2 = trim($tmp[1] ?? "");

        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $table2Field2, $column1, $column2) {
            $data = [];

            foreach ($students as $studentRow) {
                $firstName = "";
                $lastName = "";
                $firstName = $this::findValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                $lastName = $this::findValue($table2Field2[1], $column2, $cycle->id, $studentRow->student_id);

                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $firstName . " " . $lastName,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }


    protected function parseCaasppMathReading01($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side1 = preg_split('/(~)/', $values[1], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side2 = preg_split('/(~)/', $values[3], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table
        $table2Field2 = explode("|", $side2[2]); // field/table

        $tmp = explode('->', $values[2]);
        $column1 = trim($tmp[1] ?? "");
        $tmp = explode('->', $values[4]);
        $column2 = trim($tmp[1] ?? "");
        //dd($formula,$values,$side1,$side2,$table1Field1,$table2Field2,$column1,$column2);

        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $table2Field2, $column1, $column2) {
            $data = [];
            foreach ($students as $studentRow) {
                $results = MultiTableFields::where("cycle_id", $cycle->id)
                    ->where('student_id', $studentRow->student_id)
                    ->where('table_id', $table1Field1[1])
                    ->where('column', $column1)
                    ->get();
                //dd($results);
                foreach ($results as $multipleRowNumber) {
                    if ($multipleRowNumber->field_value == "01" || $multipleRowNumber->field_value == "1") {
                        $value3 = $this::findValue($table2Field2[1], $column2, $cycle->id, $studentRow->student_id);
                        $data[] = [
                            'cycle_id' => $cycle->id,
                            'formula_id' => $formulaInfo->id,
                            'student_id' => $studentRow->student_id,
                            'formula_result' => $value3,
                            'created_by' => \Auth::user()->id ?? 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ];
                        break;
                    }
                }
            }
            FormulaParsed::insert($data);
        });
    }

    protected function parseGetEquivalences($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side1 = preg_split('/(~)/', $values[1], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table

        $tmp = explode('->', $values[2]);
        $column1 = trim($tmp[1] ?? "");

        //dd($formula, $values, $side1, $table1Field1, $column1, $equivalences);

        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $column1) {
            $data = [];
            foreach ($students as $studentRow) {
                $value3 = $this::findValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value3,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }

    protected function parseGrowthEquivalence($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side1 = preg_split('/(~)/', $values[1], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table

        $tmp = explode('->', $values[2]);
        $column1 = trim($tmp[1] ?? "");

        //dd($formula, $values, $side1, $table1Field1, $column1, $equivalences);

        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $column1) {
            $data = [];
            foreach ($students as $studentRow) {
                $value3 = $this::findValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value3,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }

    protected function parseMathOperations($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $side1 = preg_split('/(~)/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side2 = preg_split('/(~)/', $values[3], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table
        $table2Field2 = explode("|", $side2[2]); // field/table

        $tmp = explode('->', $values[1]);
        $column1 = trim($tmp[1] ?? "");
        $tmp = explode('->', $values[4]);
        $column2 = trim($tmp[1] ?? "");
        //dd($formula, $values, $values[2],$side1, $side2, $table1Field1, $table2Field2, $column1, $column2, $equivalences);
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $table2Field2, $column1, $column2) {
            $data = [];
            foreach ($students as $studentRow) {

                $value1 = $this::findValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                $value2 = $this::findValue($table2Field2[1], $column2, $cycle->id, $studentRow->student_id);
                if ($value1=="") {
                    $value1 = 0;
                }
                if ($value2=="") {
                    $value2 = 0;
                }
                $value3 = 0;
                if (trim($values[2]) == "{+}") {
                    $value3 = $value1 + $value2;
                } else if (trim($values[2]) == "{-}") {
                    $value3 = $value1 - $value2;
                } else if (trim($values[2]) == "{*}") {
                    $value3 = $value1 * $value2;
                } else if (trim($values[2]) == "{/}") {
                    if ($value2 != 0) {
                        $value3 = $value1 / $value2;
                    } else {
                        $value3 =  0;
                    }
                } else {
                    Log::info($formulaInfo->formula_name + ' Studemt id: ' + $studentRow->student_id . " -> No valid operation ");
                    $value3 = 0;
                }
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value3,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }

    protected function findValue($tableId, $column, $cycleId, $studentId)
    {
        $value = 0;
        $tempTableName = "consolidated_cycle_" . $cycleId;
        if (!Schema::hasTable($tempTableName)) {
            session()->flash('error-message', 'No Data for that cycle ');
            return redirect("/admin/consolidate-view");
        }
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);

        if ($tableId == 999) {
            $result = $tempTableModel::where("cycle_id", $cycleId)
                ->where('student_id', $studentId)
                ->first();
            if ($result) {
                $value = $result->{$column};
            }
        } else {
            $result = MultiTableFields::where("cycle_id", $cycleId)
                ->where('student_id', $studentId)
                ->where('table_id', $tableId)
                ->where('column', $column)
                ->first();
            if ($result) {
                $value = $result->field_value;
            }
        }
        return $value;
    }

    protected function findMultipleValue($tableId, $column, $cycleId, $studentId)
    {
        $value = 0;
        $tempTableName = "consolidated_cycle_" . $cycleId;
        if (!Schema::hasTable($tempTableName)) {
            session()->flash('error-message', 'No Data for that cycle ');
            return redirect("/admin/consolidate-view");
        }
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);

        if ($tableId == 999) {
            $results = $tempTableModel::where("cycle_id", $cycleId)
                ->where('student_id', $studentId)
                ->get();
            foreach ($results as $result) {
                $value .= $result->{$column} . " ";
            }
        } else {
            $results = MultiTableFields::where("cycle_id", $cycleId)
                ->where('student_id', $studentId)
                ->where('table_id', $tableId)
                ->where('column', $column)
                ->get();
            foreach ($results as $result) {
                $value .= $result->field_value . " ";
            }
        }
        return $value;
    }

    protected function parseConcatenate($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        //{concatenate}:[~999|286~]{MATH Intervention List-&gt; Column_R -&gt; Notes}{&amp;}[~999|285~]{ELA Intervention List-&gt; Column_R -&gt; Notes}
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $side1 = preg_split('/(~)/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $side2 = preg_split('/(~)/', $values[3], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table
        $table2Field2 = explode("|", $side2[2]); // field/table

        $tmp = explode('->', $values[1]);
        $column1 = trim($tmp[1] ?? "");
        $tmp = explode('->', $values[4]);
        $column2 = trim($tmp[1] ?? "");
        //dd($formula, $values, $values[2],$side1, $side2, $table1Field1, $table2Field2, $column1, $column2, $equivalences);
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $table2Field2, $column1, $column2) {
            $data = [];
            foreach ($students as $studentRow) {
                $value1 = $this::findValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                $value2 = $this::findValue($table2Field2[1], $column2, $cycle->id, $studentRow->student_id);
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value1 . " " . $value3,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }
    protected function parseMultipleValues($formulaInfo, $cycle, $equivalences, $studentRows)
    {
        //{getMultipleValues}:[~999|302~]{SST Reports-&gt; Column_C -&gt; Type of SST}
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $side1 = preg_split('/(~)/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table

        $tmp = explode('->', $values[1]);
        $column1 = trim($tmp[1] ?? "");
        //dd($formula, $values, $values[2],$side1, $side2, $table1Field1, $table2Field2, $column1, $column2, $equivalences);
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $column1) {
            $data = [];
            foreach ($students as $studentRow) {
                $value1 = $this::findMultipleValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value1 ,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }


    protected function parseEvaluateStudent($formulaInfo, $cycle, $equivalences, $studentRows,$columnEvaluated)
    {
        //{evaluateStudentAccountColumnK}:[~999|378~]{Student Accounts-&gt; Column_K -&gt; (Students1) EL Acquisition Status}
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $side1 = preg_split('/(~)/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table

        $tmp = explode('->', $values[1]);
        $column1 = trim($tmp[1] ?? "");
        //dd($formula, $values, $values[2],$side1, $side2, $table1Field1, $table2Field2, $column1, $column2, $equivalences);
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $column1,$columnEvaluated) {
            $data = [];
            foreach ($students as $studentRow) {
                $value1 = $this::findMultipleValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                if ($columnEvaluated=="Column_K") {
                    if (strtolower($value1) == strtolower("English or American Sign Language Only")) {
                        $value1 = "";
                    }
                }
                if ($columnEvaluated=="Column_N") {
                    if (strtolower($value1) == strtolower("Sheline-Biernat")) {
                        $value1 = "";
                    }
                }
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value1 ,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }

    protected function parseCheckStudentExists($formulaInfo, $cycle, $equivalences, $studentRows,$columnEvaluated)
    {
        //{checkStudent}:{[~999|395~]{EL Students-&gt; Column_A -&gt; column_a}}
        $formula = self::replaceChars($formulaInfo->formula);
        $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $side1 = preg_split('/(~)/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $table1Field1 = explode("|", $side1[2]); // field/table

        $tmp = explode('->', $values[1]);
        $column1 = trim($tmp[1] ?? "");
        //dd($formula, $values, $values[2],$side1, $side2, $table1Field1, $table2Field2, $column1, $column2, $equivalences);
        $studentRows->chunk(3000, function ($students) use ($formulaInfo, $cycle, $values, $table1Field1, $column1,$columnEvaluated) {
            $data = [];
            foreach ($students as $studentRow) {
                $value1 = $this::findValue($table1Field1[1], $column1, $cycle->id, $studentRow->student_id);
                if ($value1) {
                    $value1 = "Y";
                } else {
                    $value1 = "";
                }
                $data[] = [
                    'cycle_id' => $cycle->id,
                    'formula_id' => $formulaInfo->id,
                    'student_id' => $studentRow->student_id,
                    'formula_result' => $value1 ,
                    'created_by' => \Auth::user()->id ?? 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            FormulaParsed::insert($data);
        });
    }
}
