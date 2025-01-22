<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFreckleMinutesRequest;
use App\Http\Requests\UpdateFreckleMinutesRequest;
use App\Models\FreckleMinutes;

class FreckleMinutesController extends Controller
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
     * @param  \App\Http\Requests\StoreFreckleMinutesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFreckleMinutesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FreckleMinutes  $freckleMinutes
     * @return \Illuminate\Http\Response
     */
    public function show(FreckleMinutes $freckleMinutes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FreckleMinutes  $freckleMinutes
     * @return \Illuminate\Http\Response
     */
    public function edit(FreckleMinutes $freckleMinutes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFreckleMinutesRequest  $request
     * @param  \App\Models\FreckleMinutes  $freckleMinutes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFreckleMinutesRequest $request, FreckleMinutes $freckleMinutes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FreckleMinutes  $freckleMinutes
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreckleMinutes $freckleMinutes)
    {
        //
    }
}
