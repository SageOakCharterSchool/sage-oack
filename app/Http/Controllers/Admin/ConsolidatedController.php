<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsolidatedRequest;
use App\Http\Requests\UpdateConsolidatedRequest;
use App\Models\Consolidated;

class ConsolidatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreConsolidatedRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConsolidatedRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consolidated  $consolidated
     * @return \Illuminate\Http\Response
     */
    public function show(Consolidated $consolidated)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Consolidated  $consolidated
     * @return \Illuminate\Http\Response
     */
    public function edit(Consolidated $consolidated)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConsolidatedRequest  $request
     * @param  \App\Models\Consolidated  $consolidated
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConsolidatedRequest $request, Consolidated $consolidated)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consolidated  $consolidated
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consolidated $consolidated)
    {
        //
    }
}
