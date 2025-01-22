<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Log;

class ConsolidateMapping extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'consolidate_mappings';
    protected $perPage = 200;
    protected $fillable = [
        'cycle_id',
        'screen_sort',
        'column_name',
        'column_description',
        'table_source',
        'field_source',
        'is_formulated',
        'formula_id',
        'created_by',
    ];
    public $tablesUsed = [], $formulasUsed = [], $fieldsUsed = [];

    static function getFieldSource($fieldId)
    {
        //dd($fieldId);
        $cycle = Cycle::getCurrentCycle();
        $tmp = explode("->",$fieldId);
        $tableId = $tmp[0] ?? 0;
        $column = $tmp[1] ?? '';
        if ($column == "None") {
            return $column;
        }
        $sql = "SELECT tables_mappings.id, concat(master_tables.table_alias, '-> ',tables_mappings.column, ' -> ' , tables_mappings.column_title) as field_name FROM tables_mappings join master_tables ON master_tables.id = tables_mappings.table_id
            where tables_mappings.table_id = ? and tables_mappings.column = ? and tables_mappings.cycle_id = ?
            ORDER BY master_tables.table_name, tables_mappings.id ";
        $rows = \DB::select($sql, [$tableId,$column,$cycle->id]);
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
            ORDER BY master_tables.table_name, tables_mappings.id";
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

    protected function buildDynamicModel() {
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
                    $fieldInfo = explode("->",$field->field_source);
                    $this->tablesUsed[$field->table_source][] = $fieldInfo[1];
                    //dd($row,$tablesUsed);
                } else if ($field->formula_id) {
                    $this->formulasUsed[$field->formula_id] = $field->formula_id;
                }
            }
            $table->timestamps();
        });
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
    }


    protected function buildConsolidated()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
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
                    $fieldInfo = explode("->",$field->field_source);
                    $this->tablesUsed[$field->table_source][] = $fieldInfo[1];
                    //dd($row,$tablesUsed);
                } else if ($field->formula_id) {
                    $this->formulasUsed[$field->formula_id] = $field->formula_id;
                }
            }
            $table->timestamps();
        });
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $tablesInfo = [];
        foreach ($this->tablesUsed as $tableId => $fieldId) {
            $tablesInfo[$tableId] = MasterTables::where("id", $tableId)->first();
        }
        //
        $formulasInfo = [];
        foreach ($this->formulasUsed as $formulaId) {
            $formulasInfo[$formulaId] = Formula::where("id", $formulaId)->first();
        }
        //dd($this->tablesUsed, $this->formulasUsed);
        // step 3: cycle thru student_account table
        $table = MasterTables::getTableId();
        if (!$table) {
            return;
        }
        $studentAccountRecords = MultiTableFields::select('teacher_id', 'student_id')
            ->where("cycle_id", $cycle->id)
            ->where('table_id', $table->id)
            // ->where('student_id', 5377850897)
            ->groupBy('teacher_id', 'student_id')
            // ->take(50)
            ->get();
        foreach ($studentAccountRecords as $studentAccountRecord) {
            $data = [];
            $data['cycle_id'] = $cycle->id;
            $data['teacher_id'] = $studentAccountRecord->teacher_id;
            $data['student_id'] = $studentAccountRecord->student_id;
            foreach ($fields as $field) {
                $data[$field->column_name] = null;
                if ($field->field_source && $field->field_source != 0) {
                    $fieldInfo = explode("->",$field->field_source);
                    $column = "";
                    if (isset($fieldInfo[1])) {
                        $column = $fieldInfo[1];
                    }
                    $values = MultiTableFields::where("cycle_id", $cycle->id)
                        ->where('student_id', $studentAccountRecord->student_id)
                        ->where("table_id", $field->table_source)
                        ->where("column", $column)
                        ->get();
                    foreach ($values as $value) {
                        //dd($value);
                        $data[$field->column_name] .= $value->field_value . "\r";
                    }
                }
                if ($field->formula_id) {
                    $result = Formula::formulaParsing($field->formula_id,$formulasInfo[$field->formula_id],$studentAccountRecord,$cycle,$data);
                    $data[$field->column_name] .= $result . "\r";
                }
            }
            $tempTableModel->create($data);
            //$tempTableModel->save();
        }
        //dd($rows);
    }

    protected function updateColumnA() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $cycle = Cycle::getCurrentCycle();
        $tempTableName = "consolidated_cycle_" . $cycle->id;
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $fields = $tempTableModel->where("cycle_id", $cycle->id)
                    ->update(['column_A' => \DB::raw('`id`') ]);
    }

    protected function cloneConsolidateMappingIntoNewCycle($cycleFrom, $cycleTo,$clonedTables,$clonedFormulas) {
        $this->where("cycle_id",$cycleTo)->delete(); // remove all formulas for new cycle
        $consolidateMappings = $this->where("cycle_id",$cycleFrom)
                        ->get();
        foreach ($consolidateMappings as $consolidateMapping) {
            $newConsolidateMapping = $consolidateMapping->replicate();
            $newConsolidateMapping->cycle_id = $cycleTo;
            if ($consolidateMapping->table_source && $consolidateMapping->table_source > 0 && $consolidateMapping->table_source != 999 ) {
                $newConsolidateMapping->table_source = $clonedTables[$consolidateMapping->table_source];
                $tmp = explode("->",$consolidateMapping->field_source);
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
}
