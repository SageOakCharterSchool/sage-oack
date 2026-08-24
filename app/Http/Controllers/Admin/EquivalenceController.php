<?php

namespace App\Http\Controllers\Admin;

use App\Models\Equivalences;
use App\Http\Requests\EquivalenceRequest;
use App\Http\Controllers\Controller;

/**
 * Class EquivalenceController
 * @package App\Http\Controllers
 */
class EquivalenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equivalences = Equivalences::paginate();

        return view('equivalence.index', compact('equivalences'))
            ->with('i', (request()->input('page', 1) - 1) * $equivalences->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $equivalence = new Equivalences();
        return view('equivalence.create', compact('equivalence'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquivalenceRequest $request)
    {
        Equivalences::create($request->validated());

        return redirect()->route('equivalences.index')
            ->with('success', 'Equivalence created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $equivalence = Equivalences::find($id);

        return view('equivalence.show', compact('equivalence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $equivalence = Equivalences::find($id);

        return view('equivalence.edit', compact('equivalence'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquivalenceRequest $request, Equivalences $equivalence)
    {
        $equivalence->update($request->validated());

        return redirect()->route('equivalences.index')
            ->with('success', 'Equivalence updated successfully');
    }

    public function destroy($id)
    {
        Equivalences::find($id)->delete();

        return redirect()->route('equivalences.index')
            ->with('success', 'Equivalence deleted successfully');
    }
}
