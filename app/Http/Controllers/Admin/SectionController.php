<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Http\Requests\SectionRequest;

/**
 * Class SectionController
 * @package App\Http\Controllers
 */
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::paginate();

        return view('section.index', compact('sections'))
            ->with('i', (request()->input('page', 1) - 1) * $sections->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $section = new Section();
        return view('section.create', compact('section'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {
        Section::create($request->validated());

        return redirect()->route('sections.index')
            ->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $section = Section::find($id);

        return view('section.show', compact('section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $section = Section::find($id);

        return view('section.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        return redirect()->route('sections.index')
            ->with('success', 'Section updated successfully');
    }

    public function destroy($id)
    {
        Section::find($id)->delete();

        return redirect()->route('sections.index')
            ->with('success', 'Section deleted successfully');
    }
}
