<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ReportRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Cycle;
use App\Models\ConsolidateMapping;

class BuildReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cycle = Cycle::getCurrentCycle();
        $reports = Report::where('cycle_id', $cycle->id)
            ->paginate();

        return view('report.index', compact('reports'))
            ->with('i', ($request->input('page', 1) - 1) * $reports->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $report = new Report();
        $cycle = Cycle::getCurrentCycle();
        $report = Report::where('cycle_id', $cycle->id)
                    ->first();
        if ($report) {
            return Redirect::route('build-reports.index')
                ->with('error', 'Already have a report for this cycle');
        }
        $tmpVariables = ConsolidateMapping::getOnlyConsolidatedTableFields();
        $siteVariables = [];
        foreach ($tmpVariables as $row) {
            $consolidatedVariables[] = "{" . $row->field_name . "}";
        }

        return view('report.create', compact('report', 'cycle', 'consolidatedVariables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportRequest $request): RedirectResponse
    {
        //dd($request->all());
        $rep = Report::create($request->validated());
        $sql = "update reports set report = '" . addslashes($request->report) . "' where id = " . $rep->id;
        \DB::select($sql);

        return Redirect::route('build-reports.index')
            ->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cycle = Cycle::getCurrentCycle();
        $report = Report::where('id', $id)
            ->where('cycle_id', $cycle->id)
            ->first();
        return view('report.show', compact('report', 'cycle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cycle = Cycle::getCurrentCycle();
        $report = Report::where('id', $id)
            ->where('cycle_id', $cycle->id)
            ->first();

        $tmpVariables = ConsolidateMapping::getOnlyConsolidatedTableFields();
        $siteVariables = [];
        foreach ($tmpVariables as $row) {
            $consolidatedVariables[] = "{" . $row->field_name . "}";
        }
        return view('report.edit', compact('report', 'cycle','consolidatedVariables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReportRequest $request, Report $report): RedirectResponse
    {
        //dd($request->all(),$report);
        //dd($report);
        $report->update($request->validated());
        $sql = "update reports set
        report_name = '" . addslashes($request->report_name) . "'
        ,report_description = '" . addslashes($request->report_description) . "'
        ,report = '" . addslashes($request->report) . "' where id = " . $request->rep_id;
        //dd($sql);
        \DB::select($sql);

        return Redirect::route('build-reports.index')
            ->with('success', 'Report updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        $cycle = Cycle::getCurrentCycle();
        Report::where('id', $id)
            ->where('cycle_id', $cycle->id)
            ->delete();

        return Redirect::route('build-reports.index')
            ->with('success', 'Report deleted successfully');
    }
}
