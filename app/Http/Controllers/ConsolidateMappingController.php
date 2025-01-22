<?php

namespace App\Http\Controllers;

use App\Models\ConsolidateMapping;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ConsolidateMappingRequest;
use App\Jobs\GenerateConsolidatedRecords;
use App\Models\ConsolidateGeneration;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Cycle;
use App\Models\Formula;
use App\Models\TablesMapping;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Schema;


class ConsolidateMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!\Auth::user()->isAdmin()) {
            session()->flash('error-message', 'Wrong options');
            return redirect("/home");
        }
        $cycle = Cycle::getCurrentCycle();
        $consolidateMappings = ConsolidateMapping::where('cycle_id', $cycle->id)
            ->orderBy('screen_sort')
            ->paginate();
        $consolidateGeneration = ConsolidateGeneration::checkstatus();
        return view('consolidate-mapping.index', compact('consolidateMappings', 'consolidateGeneration'))
            ->with('i', ($request->input('page', 1) - 1) * $consolidateMappings->perPage());
    }

    public function consolidatedGeneration()
    {

        $consolidateGeneration = ConsolidateGeneration::checkstatus();
        if ($consolidateGeneration->status <= 1) {
            ConsolidateGeneration::markGenerationAsInProcess(2);
            if (getenv("DISPATCH_JOBS") == 0) {
                $job = new GenerateConsolidatedRecords();
                $job->handle();
            } else {
                GenerateConsolidatedRecords::dispatch();
            }
            return Redirect::route('consolidate-mappings.index')
                ->with('success', 'Consolidated Generation submitted to queue and will be processes in a moment.');
        } else {
            return Redirect::route('consolidate-mappings.index')
                ->with('error', 'Consolidated Generation is in process, you can not submitted until is completed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cycle = Cycle::getCurrentCycle();
        $consolidateMapping = new ConsolidateMapping();
        $sql = "SELECT concat(master_tables.id,'->',tables_mappings.column) as map_id , concat(master_tables.table_alias, '-> ',tables_mappings.column, ' -> ' , tables_mappings.column_title) as field_name FROM tables_mappings join master_tables ON master_tables.id = tables_mappings.table_id
            where master_tables.cycle_id = ?
            ORDER BY master_tables.table_name, tables_mappings.id ";
        $fieldsToSelect = \DB::select($sql, [$cycle->id]);
        $formulasToUse = Formula::getFormulasToSelect($cycle->id);
        //dd($fieldsToSelect);
        return view('consolidate-mapping.create', compact('consolidateMapping', 'fieldsToSelect', 'cycle', 'formulasToUse'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsolidateMappingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->field_source != "") {
            $fieldInfo = explode("->", $request->field_source);
            if ($fieldInfo) {
                $data['table_source'] = $fieldInfo[0];
                $data['formula_id'] = null;
            }
        }
        if ($request->formula_id != "") {
            $data['table_source'] = null;
            $data['field_source'] = null;
        }
        $data["created_at"] =  \Carbon\Carbon::now(); # new \Datetime()
        $data["updated_at"] = \Carbon\Carbon::now(); # new \Datetime()
        ConsolidateMapping::create($data);

        return Redirect::route('consolidate-mappings.index')
            ->with('success', 'ConsolidateMapping created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cycle = Cycle::getCurrentCycle();
        $consolidateMapping = ConsolidateMapping::where('id', $id)
            ->where('cycle_id', $cycle->id)
            ->first();

        return view('consolidate-mapping.show', compact('consolidateMapping'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cycle = Cycle::getCurrentCycle();
        $consolidateMapping = ConsolidateMapping::where('id', $id)
            ->where('cycle_id', $cycle->id)
            ->first();
        $sql = "SELECT  concat(master_tables.id,'->',tables_mappings.column) as map_id , tables_mappings.id, concat(master_tables.table_alias, '-> ',tables_mappings.column, ' -> ' , tables_mappings.column_title) as field_name FROM tables_mappings join master_tables ON master_tables.id = tables_mappings.table_id
            where master_tables.cycle_id = ?
            ORDER BY master_tables.table_name, tables_mappings.id ";
        $fieldsToSelect = \DB::select($sql, [$cycle->id]);
        $formulasToUse = Formula::getFormulasToSelect($cycle->id);
        //dd($fieldsToSelect);
        //dd($formulasToUse);

        return view('consolidate-mapping.edit', compact('consolidateMapping', 'fieldsToSelect', 'cycle', 'formulasToUse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConsolidateMappingRequest $request, ConsolidateMapping $consolidateMapping): RedirectResponse
    {
        $data = $request->validated();
        //dd($request->all());
        if ($request->field_source != "") {
            $fieldInfo = explode("->", $request->field_source);
            if ($fieldInfo) {
                $data['table_source'] = $fieldInfo[0];
                $data['formula_id'] = null;
            }
        }
        if ($request->formula_id != "") {
            $data['table_source'] = null;
            $data['field_source'] = null;
        }

        $consolidateMapping->update($data);

        return Redirect::route('consolidate-mappings.index')
            ->with('success', 'ConsolidateMapping updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ConsolidateMapping::find($id)->delete();

        return Redirect::route('consolidate-mappings.index')
            ->with('success', 'ConsolidateMapping deleted successfully');
    }



    public function consolidatedViewCSV(Request $request, $overrideCycle = null)
    {
        $this->consolidatedView($request, $overrideCycle = null, 'Y');
    }
    public function consolidatedView(Request $request, $overrideCycle = null, $isCSV = null)
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        if (!\Auth::user()->isAdmin()) {
            $overrideCycle = null;
        }

        $cycles = Cycle::getAllCycles();
        $consolidateGeneration = ConsolidateGeneration::checkstatus();
        //dd($consolidateGeneration);
        if ($consolidateGeneration->status > 1) {
            return Redirect::route('consolidate-mappings.index')
                ->with('error', 'Consolidated Generation in process.. please wait unitl completion.');
        }
        if (!$overrideCycle) {
            $cycle = Cycle::getCurrentCycle();
        } else {
            $cycle = Cycle::where('id', $overrideCycle)->first();
        }

        if (!$cycle) {
            session()->flash('error-message', 'This cycle doesnt exists');
            return redirect("/admin/consolidate-view");
        }
        $consolidatedFields = Formula::getConsolidatedFieldsWithDescription($cycle);
        //dd($consolidatedFields);


        $tempTableName = "consolidated_cycle_" . $cycle->id;
        //dd($tempTableName);
        if (!Schema::hasTable($tempTableName)) {
            ConsolidateMapping::buildDynamicModel();
            //$tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
            session()->flash('error-message', 'No Data for that cycle ');
            //return redirect("/admin/consolidate-mappings");
            //return redirect("/admin/consolidate-view");
        }
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);
        $tempTableModel->where("student_id", 0)->delete();
        if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
            $rows = $tempTableModel::where('cycle_id', $cycle->id)
                ->where('student_id', '<>', '');
        } else {
            $rows = $tempTableModel::where('cycle_id', $cycle->id)
                ->where('student_id', '<>', '')
                ->where('teacher_id', \Auth::user()->getTeacherId());
        }
        if ($request->has('search')) {
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
            if ($isCSV != 'Y') {
                $rows = $rows->paginate(50);
            } else {
                //$rows = $rows->take(10)->get();
                $rows = $rows->get();
            }
        }
        if ($rows->isEmpty()) {
            session()->flash('error-message', 'No Data for that cycle ');
        }
        //dd($rows);
        //dd($consolidatedFields);
        if ($isCSV != 'Y') {
            return view('consolidate-mapping.view-consolidated', compact('consolidatedFields', 'rows', 'cycles','overrideCycle'));
        } else {
            $fielMapping = [];
            $csvRows = [];
            $i = 1;
            foreach ($consolidatedFields as $consolidatedField) {
                $fielMapping[$consolidatedField[1]] = $consolidatedField[0];
                $csvRows[$i][] = $consolidatedField[1];
            }
            $i++;
            foreach ($rows as $row) {
                //dd($consolidatedFields);
                foreach ($consolidatedFields as $consolidatedField) {
                    $tmp = str_replace("\r","",$row[$consolidatedField[0]]);
                    $tmp = str_replace("\n","",$tmp);
                    $csvRows[$i][] = $tmp;
                }
                $i++;
            }

            // Set PHP headers for CSV output.
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=consolidated_report_' . date("Y_m_d") . '.csv');
            $output = fopen('php://output', 'w');
            // Write headers to CSV file.
            //dd($csvRows);
            foreach ($csvRows as $data_item) {
                //dd($data_item);
                fputcsv($output, $data_item);
            }
            fclose($output);

        }
    }
}
