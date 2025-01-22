<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityLogsRequest;
use App\Http\Requests\UpdateActivityLogsRequest;
use App\Models\ActivityLogs;

class ActivityLogsController extends Controller
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
     * @param  \App\Http\Requests\StoreActivityLogsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivityLogsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActivityLogs  $activityLogs
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLogs $activityLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActivityLogs  $activityLogs
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityLogs $activityLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActivityLogsRequest  $request
     * @param  \App\Models\ActivityLogs  $activityLogs
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActivityLogsRequest $request, ActivityLogs $activityLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivityLogs  $activityLogs
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityLogs $activityLogs)
    {
        //
    }
}
