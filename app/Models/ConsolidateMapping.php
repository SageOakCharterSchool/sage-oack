<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Log;
use App\Models\Equivalences;

class ConsolidateMapping extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'consolidate_mappings';
    protected $perPage = 300;
    protected $fillable = [
        'cycle_id',
        'screen_sort',
        'column_name',
        'column_description',
        'table_source',
        'field_source',
        'is_formulated',
        'formula_id',
        'section_id',
        'created_by',
    ];
    public $tablesUsed = [], $formulasUsed = [], $fieldsUsed = [];

    static function getFieldSource($fieldId)
    {
        //dd($fieldId);
        $cycle = Cycle::getCurrentCycle();
        $tmp = explode("->", $fieldId);
        $tableId = $tmp[0] ?? 0;
        $column = $tmp[1] ?? '';
        if ($column == "None") {
            return $column;
        }
        $sql = "SELECT tables_mappings.id, concat(master_tables.table_alias, '-> ',tables_mappings.column, ' -> ' , tables_mappings.column_title) as field_name FROM tables_mappings join master_tables ON master_tables.id = tables_mappings.table_id
            where tables_mappings.table_id = ? and tables_mappings.column = ? and tables_mappings.cycle_id = ?
            ORDER BY master_tables.table_name, tables_mappings.id ";
        $rows = \DB::select($sql, [$tableId, $column, $cycle->id]);
        if (!empty($rows)) {
            //dd($rows);
        }
        return (empty($rows) ? "" : $rows[0]->field_name);
        //dd($rows);
    }

    protected function getTableFields()
    {
        $cycle = Cycle::getCurrentCycle();
        $sql = "SELECT tables_mappings.id,tables_mappings.table_id, concat(master_tables.table_alias, '-> ',tables_mappings.column, ' -> ' , tables_mappings.column_title) as field_name FROM tables_mappings join master_tables ON master_tables.id = tables_mappings.table_id
            where tables_mappings.cycle_id = ?
            ORDER BY master_tables.table_alias ";
        //ORDER BY master_tables.table_name, tables_mappings.id";
        $fieldsToSelect = \DB::select($sql, [$cycle->id]);
        return $fieldsToSelect;
    }

    protected function getOnlyConsolidatedTableFields()
    {
        $cycle = Cycle::getCurrentCycle();

        $sql = "SELECT concat('Consolidated -> ', column_name, ' -> ', column_description) as field_name FROM
            consolidate_mappings
            where consolidate_mappings.cycle_id = ?
            ORDER BY consolidate_mappings.id";
        $fieldsToSelect = \DB::select($sql, [$cycle->id]);
        return $fieldsToSelect;
    }

    protected function getOnlyConsolidatedTableFieldsWithColumn($column = null)
    {
        $cycle = Cycle::getCurrentCycle();
        if (!$column) {
            $sql = "SELECT column_name, concat('Consolidated -> ', column_name, ' -> ', column_description) as field_name FROM
                consolidate_mappings
                where consolidate_mappings.cycle_id = ?
                ORDER BY consolidate_mappings.id";
            $fieldsToSelect = \DB::select($sql, [$cycle->id]);
        } else {
            $sql = "SELECT column_name, concat('Consolidated -> ', column_name, ' -> ', column_description) as field_name FROM
                consolidate_mappings
                where consolidate_mappings.cycle_id = ?
                and  consolidate_mappings.column_name = ?
                ORDER BY consolidate_mappings.id";
            $fieldsToSelect = \DB::select($sql, [$cycle->id, $column]);
        }
        //dd($fieldsToSelect);
        return $fieldsToSelect;
    }

    protected function buildDynamicModel()
    {
        $cycle = Cycle::getCurrentCycle();
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)->orderBy('screen_sort')->get();
        // step 1: create temporary table
        $tempTableName = "consolidated_cycle_" . $cycle->id;
        $this->tablesUsed = [];
        $this->formulasUsed = [];
        Schema::dropIfExists($tempTableName);

        $result = Schema::create($tempTableName, function (Blueprint $table) use ($fields) {
            $table->id();
            $table->integer('cycle_id')->index()->comment('cycle id');;
            $table->integer('teacher_id')->index()->comment('teacher id');;
            $table->string('student_id', 55)->index()->comment('student id');
            foreach ($fields as $field) {
                $table->mediumText($field->column_name)->comment($field->column_description)->nullable();
                if ($field->table_source) {
                    $fieldInfo = explode("->", $field->field_source);
                    $this->tablesUsed[$field->table_source][] = $fieldInfo[1];
                    //dd($row,$tablesUsed);
                } else if ($field->formula_id) {
                    $this->formulasUsed[$field->formula_id] = $field->formula_id;
                }
            }
            $table->timestamps();
            $table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
        });
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
    }


    protected function buildConsolidated()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $cycle = Cycle::getCurrentCycle();
        $fields = ConsolidateMapping::where("cycle_id", $cycle->id)->orderBy('screen_sort')->get();
        $equivalences = Consolidate3::columnACADEquivalences();
        // step 1: create temporary table
        $consolidatedRow = [];
        $tempTableName = "consolidated_cycle_" . $cycle->id;

        $this->tablesUsed = [];
        $this->formulasUsed = [];
        $valuesFromConsolidated3 = Consolidate3::generateCycleTables($cycle, $fields);
        //dd($valuesFromConsolidated3);
        $this->tablesUsed = $valuesFromConsolidated3['tablesUsed'];
        $this->formulasUsed = $valuesFromConsolidated3['formulasUsed'];
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        Consolidate3sAttributes::generateCycleTables($cycle);
        $tablesInfo = [];
        foreach ($this->tablesUsed as $tableId => $fieldId) {
            $tablesInfo[$tableId] = MasterTables::where("id", $tableId)->first();
        }
        //
        $formulasInfo = [];
        foreach ($this->formulasUsed as $formulaId) {
            $formulasInfo[$formulaId] = Formula::where("id", $formulaId)->first();
        }
        //dd($formulasInfo);
        //dd($this->tablesUsed, $this->formulasUsed);
        // step 3: cycle thru student_account table
        $table = MasterTables::getTableId();
        if (!$table) {
            return;
        }
        $query = MultiTableFields::select('teacher_id', 'student_id')
            ->where("cycle_id", $cycle->id)
            ->where('table_id', $table->id)
            ->where('student_id', '>', 0)
            //->where('student_id', 9732038591)
            //->where('student_id', 7720295488)
            ->groupBy('teacher_id', 'student_id');
        if (getenv("APP_ENV") == "PROD") {
            $studentAccountRecords = $query->get();
        } else {
            //$studentAccountRecords  = $query->take(1000)->get();
            $studentAccountRecords = $query->get();
        }
        //dd(count($studentAccountRecords));
        $loops = 1;
        foreach ($studentAccountRecords as $studentAccountRecord) {
            $data = [];
            $data['cycle_id'] = $cycle->id;
            $data['teacher_id'] = $studentAccountRecord->teacher_id;
            $data['student_id'] = $studentAccountRecord->student_id;
            if (!isset($consolidatedRow[$studentAccountRecord->student_id])) {
                $consolidatedRow[$studentAccountRecord->student_id] = $data;
            }
            //dd($consolidatedRow);
            // $consolidatedRow = $tempTableModel->where("cycle_id", $cycle->id)
            //     ->where('student_id', $studentAccountRecord->student_id)
            //     ->first();
            foreach ($fields as $field) {
                $data[$field->column_name] = null;
                if ($field->field_source && $field->field_source != 0) {
                    $fieldInfo = explode("->", $field->field_source);
                    $column = "";
                    if (isset($fieldInfo[1])) {
                        $column = $fieldInfo[1];
                    }
                    //var_dump($fieldInfo,$column);
                    $values = MultiTableFields::where("cycle_id", $cycle->id)
                        ->where('student_id', $studentAccountRecord->student_id)
                        ->where("table_id", $field->table_source)
                        ->where("column", $column)
                        ->get();
                    foreach ($values as $value) {
                        //dd($value);
                        $data[$field->column_name] .= $value->field_value . "\r";
                        if (!isset($consolidatedRow[$studentAccountRecord->student_id])) {
                            $consolidatedRow[$studentAccountRecord->student_id] = $data;
                        } else {
                            $consolidatedRow[$studentAccountRecord->student_id][$field->column_name] = $value->field_value . "";
                        }
                        // if (!$consolidatedRow) {
                        //     $consolidatedRow = $tempTableModel->create($data);
                        // } else {
                        //     $consolidatedRow->{$field->column_name} .= $value->field_value . "\r";
                        //     $consolidatedRow->save();
                        // }
                    }
                }
                //var_dump($formulasInfo);
                if ($field->formula_id) {

                    // if (!$consolidatedRow) {
                    //     $consolidatedRow = $tempTableModel->create($data);
                    // }
                    if (!isset($consolidatedRow[$studentAccountRecord->student_id])) {
                        $consolidatedRow[$studentAccountRecord->student_id] = $data;
                    }
                    $time1 = time();
                    //Log::info('Entering Formula' . $field->formula_id);
                    $result = Formula::formulaParsing($field->formula_id, $formulasInfo[$field->formula_id], $studentAccountRecord, $cycle, $data, $consolidatedRow, $field->column_name, $equivalences);
                    //$result = "";
                    $time2 = time();
                    //Log::info('Returning Formula on ' . ($time2 - $time1) . " ms");
                    //$result = null;
                    $consolidatedRow[$studentAccountRecord->student_id][$field->column_name] = $result . "";
                    //$data[$field->column_name] .= $result . "\r";
                    // $consolidatedRow->{$field->column_name} .= $result . "\r";
                    // $consolidatedRow->save();
                }
            }

            //$tempTableModel->save();
            if ($loops % 100 == 0) {
                Log::info("Processing $loops of " . count($studentAccountRecords));
            }
            $loops++;
        }
        // Batch Insert
        $batchToInsert = [];
        $time1 = time();
        Log::info("Saving Batch rows to insert -> " . count($consolidatedRow));
        foreach ($consolidatedRow as $row) {
            //$batchToInsert[] = $row;
            $tempTableModel->insert($row);
        }

        //dd($consolidatedRow);
        //$tempTableModel->insert($batchToInsert);
        $time2 = time();
        Log::info("Saving Batch Completed on " . ($time2 - $time1) . "ms");
        //dd($rows);
    }

    protected function updateColumnA()
    {
        $time1 = time();
        Log::info("Update Column A start ");
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $cycle = Cycle::getCurrentCycle();
        $tempTableName = "consolidated_cycle_" . $cycle->id;
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $fields = $tempTableModel->where("cycle_id", $cycle->id)
            ->update(['column_A' => \DB::raw('`id`')]);
        $time2 = time();
        Log::info("Update Column A completed " . ($time2 - $time1) . "ms");
    }

    protected function cloneConsolidateMappingIntoNewCycle($cycleFrom, $cycleTo, $clonedTables, $clonedFormulas)
    {
        $this->where("cycle_id", $cycleTo)->delete(); // remove all formulas for new cycle
        $consolidateMappings = $this->where("cycle_id", $cycleFrom)
            ->get();
        foreach ($consolidateMappings as $consolidateMapping) {
            $newConsolidateMapping = $consolidateMapping->replicate();
            $newConsolidateMapping->cycle_id = $cycleTo;
            if ($consolidateMapping->table_source && $consolidateMapping->table_source > 0 && $consolidateMapping->table_source != 999) {
                $newConsolidateMapping->table_source = $clonedTables[$consolidateMapping->table_source];
                $tmp = explode("->", $consolidateMapping->field_source);
                $fieldSource = "";
                if (!isset($tmp[1])) {
                    Log::info("field source -> " . $consolidateMapping);
                    $fieldSource = "Error in clone";
                } else {
                    $fieldSource = $tmp[1];
                }
                $newConsolidateMapping->table_source = $clonedTables[$consolidateMapping->table_source];
                $newConsolidateMapping->field_source = $clonedTables[$consolidateMapping->table_source] . "->" . $fieldSource;
            }
            if ($consolidateMapping->formula_id) {
                $newConsolidateMapping->formula_id = $clonedFormulas[$consolidateMapping->formula_id];
            }
            $newConsolidateMapping->save();
        }
    }

    protected function generateReport($request, $sectionId = null, $cycleId, $overrideCycle = null, $exportFormat = null)
    {
        //dd($request, $sectionId, $overrideCycle, $exportFormat);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        if (!\Auth::user()->isAdmin()) {
            $overrideCycle = null;
            $cycle = Cycle::getCurrentCycle();
        } else {
            if (!$cycleId) {
                $cycle = Cycle::getCurrentCycle();
            } else {
                $cycle = Cycle::getCyclesById($cycleId);
            }
        }

        $sections = Section::getSectionsKeyedById();
        $cycles = Cycle::getAllCycles();
        $consolidateGeneration = ConsolidateGeneration::checkstatus();
        //dd($cycle);
        $reportsList = Report::getReportList($cycle);
        //dd($consolidateGeneration);
        if ($consolidateGeneration->status > 1) {
            return ['status' => 'Consolidated Generation in process.. please wait unitl completion.'];
            //return redirect("/admin/consolidate-view")->with('error', 'Consolidated Generation in process.. please wait unitl completion.');
            // return Redirect::route('consolidate-mappings.index')
            //     ->with('error', 'Consolidated Generation in process.. please wait unitl completion.');
        }

        if (!$cycle) {
            return ['status' => 'This cycle doesnt exists'];
            // session()->flash('error-message', 'This cycle doesnt exists');
            // return redirect("/admin/consolidate-view");
        }
        $consolidatedFields = Formula::getConsolidatedFieldsWithDescription($cycle);
        $consolidatedBasicFields = Formula::getConsolidatedBasicFieldsWithDescription($cycle);

        //dd($consolidatedBasicFields);


        $tempTableName = "consolidated_cycle_" . $cycle->id;
        //dd($tempTableName);
        if (!Schema::hasTable($tempTableName)) {
            ConsolidateMapping::buildDynamicModel();
            return ['status' => 'No Data for that cycle '];
            //$tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
            //session()->flash('error-message', 'No Data for that cycle ');
            //return redirect("/admin/consolidate-mappings");
            //return redirect("/admin/consolidate-view");
        }
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $tempTableModel->where("student_id", 0)->delete();

        if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
            $rows = $tempTableModel::where('cycle_id', $cycle->id)
                ->where('student_id', '<>', '');
        } else if (\Auth::user()->role_as == 4) {
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $mySpecialistStudents = SpecialistStudent::getAllSpecialistStudents(\Auth::user()->id, $myRequest);
            $myStudentsIds = [];
            foreach ($mySpecialistStudents as $mySpecialistStudent) {
                $myStudentsIds[] = $mySpecialistStudent->student_id;
            }
            //dd($mySpecialistStudents);
            $rows = $tempTableModel::where('cycle_id', $cycle->id)
                ->whereIn('student_id', $myStudentsIds);
        } else {
            $rows = $tempTableModel::where('cycle_id', $cycle->id)
                ->where('student_id', '<>', '')
                ->where('teacher_id', \Auth::user()->getTeacherId());
        }
        if ($request->has('search') && \Auth::user()->role_as != 4) {
            $rows = $rows->where(function ($query) use ($request) {
                $query->Where('student_id', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_A', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_B', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_C', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_D', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_E', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_F', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_G', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_H', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_I', 'like', '%' . $request->search . '%')
                    ->orWhere('Column_J', 'like', '%' . $request->search . '%');
            })
                ->orderBy('student_id')
                ->paginate(50);
        } else {
            //dd($exportFormat);
            if (!$exportFormat) {
                $rows = $rows->paginate(50);
            } else {
                if (getenv("APP_ENV") == "PROD") {
                    $rows = $rows->get();
                } else {
                    //$rows = $rows->take(10)->get();
                    $rows = $rows->get();
                }
            }
        }
        //dd($rows);
        return compact('rows', 'consolidatedFields', 'consolidatedBasicFields', 'cycles', 'sections', 'reportsList');
    }

    protected function generateCSV($rows, $consolidatedFields, $consolidatedBasicFields, $cycles, $sections, $sectionId)
    {
        $fielMapping = [];
        $csvRows = [];
        $i = 1;
        foreach ($consolidatedBasicFields as $consolidatedField) {
            $fielMapping[$consolidatedField[1]] = $consolidatedField[0];
            $csvRows[$i][] = $consolidatedField[1];
        }
        foreach ($consolidatedFields as $k => $consolidatedField) {
            if (!isset($consolidatedBasicFields[$k])) {
                if (!$sectionId || $sectionId == 0) {
                    $fielMapping[$consolidatedField[1]] = $consolidatedField[0];
                    $csvRows[$i][] = $consolidatedField[1];
                } else {
                    if (isset($sections[$consolidatedField[2]])) {
                        if ($sectionId == $consolidatedField[2]) {
                            $fielMapping[$consolidatedField[1]] = $consolidatedField[0];
                            $csvRows[$i][] = $consolidatedField[1];
                        }
                    }
                }
            }
        }
        $i++;
        foreach ($rows as $row) {
            foreach ($consolidatedBasicFields as $consolidatedField) {
                $tmp = str_replace("\r", "", $row[$consolidatedField[0]]);
                $tmp = str_replace("\n", "", $tmp);
                $csvRows[$i][] = $tmp;
            }
            //dd($consolidatedFields,$consolidatedBasicFields);
            //dd($sectionId);
            foreach ($consolidatedFields as $k => $consolidatedField) {
                if (!isset($consolidatedBasicFields[$k])) {
                    if (!$sectionId || $sectionId == 0) {
                        //dd($k,$consolidatedField);
                        $tmp = str_replace("\r", "", $row[$consolidatedField[0]]);
                        $tmp = str_replace("\n", "", $tmp);
                        $csvRows[$i][] = $tmp;
                    } else {
                        if (isset($sections[$consolidatedField[2]])) {
                            if ($sectionId == $consolidatedField[2]) {
                                $tmp = str_replace("\r", "", $row[$consolidatedField[0]]);
                                $tmp = str_replace("\n", "", $tmp);
                                $csvRows[$i][] = $tmp;
                            }
                        }
                    }
                }
            }
            $i++;
        }
        return $csvRows;
    }

    protected function calculateFormulasResult()
    {
        echo date("Y-m-d H:i:s");
        FormulaParsed::truncate();
        Log::info("Processsing Formula started" );
        $table = MasterTables::getTableId();
        if (!$table) {
            return;
        }
        $cycle = Cycle::getCurrentCycle();
        $equivalences = Consolidate3::columnACADEquivalences();
        Log::info("Processsing Formula : Get Equivalences ");
        $formulas = Formula::where("cycle_id", $cycle->id)
            //->where("id",270)
            ->get();
        Log::info("Processsing Formula : Get Formulas ");
        $students = MultiTableFields::select('student_id')
            ->where("cycle_id", $cycle->id)
            ->where('student_id', '>', 0)
            //->where('student_id', 1004707282)
            ->orderBy( 'student_id')
            ->groupBy( 'student_id');


        Log::info("Processsing Formula : Get MultiTable Records ");
        foreach ($formulas as $formulaInfo) {
            Log::info("Processsing Formula :" . $formulaInfo->id);
            Formula::parseForumula($formulaInfo,$cycle,$equivalences,$students);
        }
        echo "   ============  ";
        echo date("Y-m-d H:i:s");
        //dd("completed");
    }
}
