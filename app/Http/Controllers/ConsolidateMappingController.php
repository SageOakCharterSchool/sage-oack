<?php

namespace App\Http\Controllers;

use App\Exports\ConsolidateExport;
use App\Models\ConsolidateMapping;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ConsolidateMappingRequest;
use App\Jobs\GenerateConsolidatedRecords;
use App\Jobs\GenerateExcelReport;
use App\Models\BatchReports;
use App\Models\ConsolidateColor;
use App\Models\ConsolidateGeneration;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\Cycle;
use App\Models\Formula;
use App\Models\Section;
use App\Models\TablesMapping;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Extension\Table\TableSection;
use PhpOffice\PhpSpreadsheet\IOFactory;



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
        $consolidateColorContent = ConsolidateColor::getAllColumnColors($cycle->id);
        //dd($consolidateColorContent);
        return view('consolidate-mapping.index', compact('consolidateMappings', 'consolidateGeneration','consolidateColorContent'))
            ->with('i', ($request->input('page', 1) - 1) * $consolidateMappings->perPage());
    }

    public function consolidatedGeneration()
    {

        $consolidateGeneration = ConsolidateGeneration::checkstatus();
        if ($consolidateGeneration->status <= 1) {
            ConsolidateGeneration::markGenerationAsInProcess(2);
            if (getenv("DISPATCH_JOBS") == 0) {
                GenerateConsolidatedRecords::dispatch();
                //$job = new GenerateConsolidatedRecords();
                //$job->handle();
            } else {
                //GenerateConsolidatedRecords::dispatch();
                $cycle = Cycle::getCurrentCycle();
                $data = [
                    'cycle_id' => $cycle->id,
                    'section_id' => null,
                    'student_id' => null,
                    'report_id' => null,
                    'created_by' => \Auth::user()->id,
                    'status' => 1,
                    'started_at' => null,
                    'completed_at' => null,
                    'job_type' => 2,
                ];
                BatchReports::create($data);
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
    public function create()
    {
        $cycle = Cycle::getCurrentCycle();
        $consolidateMapping = new ConsolidateMapping();
        $sql = "SELECT concat(master_tables.id,'->',tables_mappings.column) as map_id , concat(master_tables.table_alias, '-> ',tables_mappings.column, ' -> ' , tables_mappings.column_title) as field_name FROM tables_mappings join master_tables ON master_tables.id = tables_mappings.table_id
            where master_tables.cycle_id = ?
            ORDER BY master_tables.table_name, tables_mappings.id ";
        $fieldsToSelect = \DB::select($sql, [$cycle->id]);
        $formulasToUse = Formula::getFormulasToSelect($cycle->id);
        $sectionsToUse = Section::getSectionsToSelect();
        $isCreate = true;
        //dd($fieldsToSelect);
        return view('consolidate-mapping.create', compact('consolidateMapping', 'fieldsToSelect', 'cycle', 'formulasToUse','sectionsToUse','isCreate'));
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
        $data["section_id"] = $request->section_id;
        ConsolidateMapping::create($data);

        return Redirect::route('consolidate-mappings.index')
            ->with('success', 'ConsolidateMapping created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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
    public function edit($id)
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
        $sectionsToUse = Section::getSectionsToSelect();
        $isCreate = false;
        //dd($fieldsToSelect);
        //dd($formulasToUse);

        return view('consolidate-mapping.edit', compact('consolidateMapping', 'fieldsToSelect', 'cycle', 'formulasToUse','sectionsToUse','isCreate'));
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

        $data["section_id"] = $request->section_id;
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



    public function consolidatedViewCSV(Request $request, $cycleId=null, $sectionId=null, $overrideCycle = null)
    {

        $this->consolidatedView($request, $cycleId, $sectionId,$overrideCycle = null, 'CSV');

    }
    public function consolidatedViewExcel(Request $request, $cycleId=null, $sectionId=null, $overrideCycle = null)
    {
        $this->consolidatedView($request, $cycleId, $sectionId,$overrideCycle = null, 'EXCEL');
        return redirect('/admin/consolidate-view')
                        ->with('message', 'Consolidated EXCEL Generation submitted to queue and will be processes in a moment, You should receive an email with this report shortly');
    }

    public function consolidatedView(Request $request, $cycleId=null, $sectionId=null, $overrideCycle = null, $exportFormat = null )
    {

        //dd($request, $cycleId, $sectionId, $overrideCycle, $exportFormat);
        if (!$sectionId) {
            $sectionId = 0;
        }
        if (!$cycleId) {
            $cycle = Cycle::getCurrentCycle();
            $cycleId = $cycle->id;
        } else {
            $cycle = Cycle::getCyclesById($cycleId);
        }
        if (!\Auth::user()->isAdmin()) {
            $cycle = Cycle::getCurrentCycle();
            $cycleId = $cycle->id;
        }
        //dd($cycleId,$sectionId);
        $return = ConsolidateMapping::generateReport($request,$sectionId, $cycleId,  $overrideCycle, $exportFormat);
        //dd($return);
        if (!isset($return['rows'])) {
            session()->flash('error-message', $return['status']);
            return redirect("/admin/consolidate-mappings");
        }
        $rows = $return['rows'];
        $consolidatedBasicFields = $return['consolidatedBasicFields'];
        $consolidatedFields = $return['consolidatedFields'];
        $cycles = $return['cycles'];
        $sections = $return['sections'];
        $reportsList = $return['reportsList'];
        $consolidateColors = ConsolidateColor::getAllColumnColors($cycleId);


        if ($rows->isEmpty()) {
            session()->flash('error-message', 'No Data for that cycle ');
        }

        if (!$exportFormat) {
            //dd($sections);
            return view('consolidate-mapping.view-consolidated', compact('consolidatedFields', 'consolidatedBasicFields','rows', 'cycles','overrideCycle','sections','sectionId','exportFormat','reportsList','cycle','consolidateColors'));
        } elseif ($exportFormat == "CSV") {
            $csvRows = ConsolidateMapping::generateCSV($rows, $consolidatedFields,$consolidatedBasicFields,$cycles,$sections,$sectionId);

            // Set PHP headers for CSV output.
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=consolidated_report_' . date("Y_m_d") . '.csv');
            $output = fopen('php://output', 'w');

            foreach ($csvRows as $data_item) {
                fputcsv($output, $data_item);
            }
            fclose($output);
        } elseif ($exportFormat == "EXCEL") {

            //'status', // 1 = in queue / 2 = in process / 3 = completed
            $data = [
                'cycle_id' => $cycleId,
                'section_id' => $sectionId,
                'student_id' => null,
                'report_id' => null,
                'created_by' => \Auth::user()->id,
                'status' => 1,
                'started_at' => null,
                'completed_at' => null,
                'job_type' => 1,
            ];
            BatchReports::create($data);






        }
    }
}
