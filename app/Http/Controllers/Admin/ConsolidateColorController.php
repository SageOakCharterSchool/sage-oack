<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsolidateColor;
use App\Http\Requests\ConsolidateColorRequest;
use App\Models\ConsolidateMapping;
use App\Models\Cycle;
use App\Models\MasterTables;
use Illuminate\Http\Request;

/**
 * Class ConsolidateColorController
 * @package App\Http\Controllers
 */
class ConsolidateColorController extends Controller
{
    private $columnToFilter;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cycle = Cycle::getCurrentCycle();
        $this->columnToFilter = $request->column ?? null;
        if (!$this->columnToFilter) {
            $masterTableColors = ConsolidateColor::where('cycle_id', $cycle->id)
                        ->orderBy('column_name')
                        ->paginate();
        } else {
            $masterTableColors = ConsolidateColor::where('cycle_id', $cycle->id)
                            ->where('column_name',$this->columnToFilter)
                            ->orderBy('column_name')
                            ->paginate();
        }
        $tmp = ConsolidateMapping::getOnlyConsolidatedTableFieldsWithColumn($this->columnToFilter);
        $tmpVariables = [];
        foreach ($tmp as $row) {
            $tmpVariables[$row->column_name] = $row->field_name;
        }
        $columnToFilter = $this->columnToFilter;
        //dd($columnToFilter,$masterTableColors,$tmpVariables);
        return view('consolidate-color.index', compact('masterTableColors','tmpVariables','columnToFilter'))
            ->with('i', (request()->input('page', 1) - 1) * $masterTableColors->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($columnToFilter=null)
    {

        $cycle = Cycle::getCurrentCycle();
        //$tableList = MasterTables::buildTablesList();
        $tmpVariables = ConsolidateMapping::getOnlyConsolidatedTableFieldsWithColumn($columnToFilter);
        //dd($tmpVariables);
        $masterTableColor = new ConsolidateColor();
        $form_status = "A";
        return view('consolidate-color.create', compact('masterTableColor','cycle','tmpVariables','form_status','columnToFilter'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsolidateColorRequest $request)
    {
        ConsolidateColor::create($request->validated());

        return redirect()->route('consolidate-colors.index')
            ->with('success', 'ConsolidateColor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cycle = Cycle::getCurrentCycle();
        $masterTableColor = ConsolidateColor::find($id);
        $tmpVariables = ConsolidateMapping::getOnlyConsolidatedTableFieldsWithColumn();
        return view('consolidate-color.show', compact('masterTableColor','cycle','tmpVariables'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $columnToFilter = $this->columnToFilter ?? null;
        //dd($columnToFilter);
        $cycle = Cycle::getCurrentCycle();
        $masterTableColor = ConsolidateColor::find($id);
        $tmpVariables = ConsolidateMapping::getOnlyConsolidatedTableFieldsWithColumn();
        $form_status = "E";
        return view('consolidate-color.edit', compact('masterTableColor','cycle','tmpVariables','form_status','columnToFilter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConsolidateColorRequest $request, ConsolidateColor $masterTableColor)
    {
        //dd($request->all(),$request->validated(),$masterTableColor);
        ConsolidateColor::where('id',$request->id)->update($request->validated());
        //dd($request->validated(),$masterTableColor);

        return redirect()->route('consolidate-colors.index')
            ->with('success', 'ConsolidateColor updated successfully');
    }

    public function destroy($id)
    {
        ConsolidateColor::find($id)->delete();

        return redirect()->route('consolidate-colors.index')
            ->with('success', 'ConsolidateColor deleted successfully');
    }
}
