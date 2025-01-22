<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\FormulaRequest;
use App\Models\ConsolidateMapping;
use App\Models\Cycle;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class FormulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cycle = Cycle::getCurrentCycle();
        $formulas = Formula::where('cycle_id',$cycle->id)
                        ->orderBy('id')
                        ->paginate();

        return view('formula.index', compact('formulas'))
            ->with('i', ($request->input('page', 1) - 1) * $formulas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {

        //dd($values);
        //die();
        $formula = new Formula();
        $siteVariables = Formula::buildSiteVariables();
        $siteFormulas = Formula::buildSiteFormulas();

        $siteOperations = config('constants.siteOperations');
        //dd($siteVariables,$siteOperations);
        $cycle = Cycle::getCurrentCycle();
        return view('formula.create', compact('formula','cycle','siteOperations', 'siteVariables','siteFormulas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormulaRequest $request): RedirectResponse
    {
        $formula = Formula::create($request->validated());
        $sql = "update formulas set formula = '" . addslashes($request->formula) . "' where id = " . $formula->id;
        \DB::select($sql);

        return Redirect::route('formulas.index')
            ->with('success', 'Formula created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cycle = Cycle::getCurrentCycle();
        $formula = Formula::where('id',$id)
                    ->where('cycle_id',$cycle->id)
                    ->first();

        return view('formula.show', compact('formula'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $siteVariables = Formula::buildSiteVariables();

        $siteOperations = $siteOperations = config('constants.siteOperations');

        $cycle = Cycle::getCurrentCycle();
        $formula = Formula::where('id',$id)
                    ->where('cycle_id',$cycle->id)
                    ->first();

        return view('formula.edit', compact('formula','cycle','siteOperations', 'siteVariables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormulaRequest $request, Formula $formula): RedirectResponse
    {
        $data = $request->validated();
        $formula->update($data);
        $sql = "update formulas set formula = '" . addslashes($request->formula) . "' where id = " . $request->formula_id;
        \DB::select($sql);

        return Redirect::route('formulas.index')
            ->with('success', 'Formula updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Formula::find($id)->delete();

        return Redirect::route('formulas.index')
            ->with('success', 'Formula deleted successfully');
    }
}
