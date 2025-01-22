<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Schema;


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

    protected function formulaParsing($formulaId, $formulaInfo, $row, $cycle, $currentRow)
    {
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
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->where('student_id', $row->student_id)
                ->first();
            if ($result) {
                $firstName = $result->field_value;
            }
            $result = MultiTableFields::where("cycle_id", $cycle->id)
                ->where('table_id', $table2Field2[1])
                ->where('column', $column2)
                ->where('student_id', $row->student_id)
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
                ->where('table_id', $table1Field1[1])
                ->where('column', $column1)
                ->where('student_id', $row->student_id)
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
        if ($formulaInfo->formula_name == "Get CAASPP Math") {
            // this formula is based on multiple records
            //{getCaasppMath01}:[~999|18~]{CAASPP-> Column_A -> RecordType}:[~999|18~]{CAASPP-> Column_EV -> AchievementLevels}
            $formula = self::replaceChars($formulaInfo->formula);
            $tableId =  MasterTables::getTableId('caaspps')->id;
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp = explode('->', $values[2]);
            $column = trim($tmp[1]);
            $multipleRowNumbers = MultiTableFields::getAllTheRowsForRecordThatHasMultipleRecords($cycle->id, $tableId, $row->student_id, $column);
            //dd($multipleRowNumbers);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            foreach ($multipleRowNumbers as $multipleRowNumber) {
                $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, $multipleRowNumber->row_number);
                $value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[3]), trim($values[4]), $cycle, $row, $currentRow, $multipleRowNumber->row_number);
                //dd($values, $value1, $value3, $row->student_id);
                if ($value1 == "01" || $value1 == "1") {
                    return $value3;
                }
            }
            return null;
        }
        if ($formulaInfo->formula_name == "Get CAASPP Reading") {
            //{getCaasppMath01}:[~999|18~]{CAASPP-> Column_A -> RecordType}:[~999|18~]{CAASPP-> Column_EV -> AchievementLevels}
            $formula = self::replaceChars($formulaInfo->formula);
            $tableId =  MasterTables::getTableId('caaspps')->id;
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp = explode('->', $values[2]);
            $column = trim($tmp[1]);
            $multipleRowNumbers = MultiTableFields::getAllTheRowsForRecordThatHasMultipleRecords($cycle->id, $tableId, $row->student_id, $column);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            foreach ($multipleRowNumbers as $multipleRowNumber) {
                $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow, $multipleRowNumber->row_number);
                $value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[3]), trim($values[4]), $cycle, $row, $currentRow, $multipleRowNumber->row_number);
                //dd($values,$value1, $value3,$row->student_id,$tmp);
                if ($value1 == "02" || $value1 == "2") {
                    return $value3;
                }
            }
            return null;
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
            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow);
            $equivalences = Consolidate3::columnACADEquivalences();
            //dd($values,$equivalences,$value1);

            $return = 0;
            if (isset($equivalences[$value1])) {
                $return = $equivalences[$value1];
            }
            //dd($values,$value1,$equivalences, $row->student_id,$return);
            return $return;
        }
        if (
            $formulaInfo->formula_name == "Color CAASPP Math" ||
            $formulaInfo->formula_name == "Color CAASPP Reading"
        ) {
            //{getEquivalences}:999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);
            $formulaArray = explode(";",$formula);

            // get the value of the formula base
            $values = preg_split('/({[^}]*})/', $formulaArray[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $formulaId = preg_split('/(~)/', $tmp0[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE)[2];
            $formulaInfo = Formula::where("id", $formulaId)->first();
            $result = trim(Formula::formulaParsing($formulaId, $formulaInfo, $row, $cycle, $currentRow));

            //dd($formulaInfo,$formulaArray,$result);
            unset($formulaArray[0]);
            foreach ($formulaArray as $formula) {
                if (trim($formula)=="") {
                    continue;
                }
                $formula = trim($formula);
                preg_match('/==(.*?)then/', $formula, $matches);
                if (isset($matches[1])) {
                    if ($result == $matches[1]) {
                        return trim(substr($formula, strpos($formula, 'then')+4));
                    }
                }
            }
            return null;
        }
        if (
            Str::contains(strtolower($formulaInfo->formula_name), 'substract') ||
            Str::contains(strtolower($formulaInfo->formula_name), 'add') ||
            Str::contains(strtolower($formulaInfo->formula_name), 'multiply') ||
            Str::contains(strtolower($formulaInfo->formula_name), 'dividedby')
        ) {
            //{getEquivalences}:[~999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}
            $formula = self::replaceChars($formulaInfo->formula);
            $values = preg_split('/({[^}]*})/', $formula, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $tmp0 = preg_split('/{(.*?)}/', $values[0], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            $value1 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[1]), trim($values[2]), $cycle, $row, $currentRow);
            $value3 = $this->resolveTwoPartsFormula($formulaInfo, trim($values[3]), trim($values[4]), $cycle, $row, $currentRow);
            //dd($currentRow,$formulaInfo,$formula,$values,$value1,$value3);

            $return = 0;
            if (isset($equivalences[$value1]) && isset($equivalences[$value1])) {
                $return = $this->performFormulaOperation($values, $value1, $value3, $currentRow, $formulaInfo);
            }
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
        if (!(is_numeric($value1) && is_numeric($value2))) {
            Log::info($formulaInfo->formula_name + ' Studemt id: ' + $currentRow['student_id'] . " -> No numeric values for operation ");
            return -999;
        }
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

    protected function getConsolidatedValues($formulaName, $studentId,$cycle) {
        if (
            $formulaName == "Color CAASPP Math" ||
            $formulaName == "Color CAASPP Reading"
        ) {
            //{getEquivalences}:999|5~]{iReady Math BOY-> Column_AF -> Overall Placement}

            $formulaInfo = Formula::where("cycle_id", $cycle->id)
                            ->where('formula_name',$formulaName)
                            ->first();
            if (!$formulaInfo) {
                return null;
            }
            $formula = self::replaceChars($formulaInfo->formula);
            $formulaArray = explode(";",$formula);

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
                    $result = preg_replace('/[^0-9a-zA-Z_]/',"",$result);

                }
            }
            //dd($formulaArray);
            unset($formulaArray[0]);
            foreach ($formulaArray as $formula) {

                if (trim($formula)=="") {
                    continue;
                }
                $formula = trim($formula);
                preg_match('/==(.*?)then/', $formula, $matches);
                if (isset($matches[1])) {
                    $valueToCompare = preg_replace('/[^0-9a-zA-Z_]/',"",$matches[1]);
                    if ($result == $valueToCompare) {
                        return trim(substr($formula, strpos($formula, 'then')+4));
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
        return $string;
    }

    /**
     * getting :[~999|18~]
     * returning: value
     */
    protected function resolveTwoPartsFormula($formulaInfo, $value1, $value2, $cycle, $row, $currentRow, $rowNumber = false)
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
        //var_dump($formulaInfo->formula_name,$values,$value1,$value2);
        if (!isset($side1[2])) {
            Log::info('Error in formula... ' . $formulaInfo . " Student Id:" . $currentRow['student_id']);
            return -992;
        }
        $table1Field1 = explode("|", $side1[2]); // field/table
        //var_dump($formulaInfo->formula_name,$values,$side1,$currentRow['student_id']);
        if ($table1Field1[1] == 999) {
            $consolidatedFields = $this->getConsolidatedFields($cycle);
            if (isset($currentRow[$consolidatedFields[$keys1[0]]])) {
                return $currentRow[$consolidatedFields[$keys1[0]]];
            }
            return null;
        }
        $tmp = explode('->', $values[0]);
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
        $studId = $row->student_id;
        //$studId = 1351902554;

        $query = MultiTableFields::where("cycle_id", $cycle->id)
            ->where('table_id', $table1Field1[1])
            ->where('column', $column1)
            ->where('student_id', $studId);
        if ($rowNumber) {
            $query->where('row_number', $rowNumber);
        }
        $result = $query->first();
        if ($result) {
            $value = $result->field_value;
        }
        return $value;
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
        dd($value1, $value2, $values, $side1, $table1Field1, $tmp, $column1, $field1Id, $row, $value);
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
            $siteVariables[$row->id] = [$row->column_name, $row->column_description];
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
}
