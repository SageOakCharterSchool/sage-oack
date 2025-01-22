<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceMathRequest;
use App\Http\Requests\UpdateAttendanceMathRequest;
use App\Models\AttendanceMath;

class AttendanceMathController extends Controller
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
     * @param  \App\Http\Requests\StoreAttendanceMathRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceMathRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendanceMath  $attendanceMath
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceMath $attendanceMath)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AttendanceMath  $attendanceMath
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceMath $attendanceMath)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendanceMathRequest  $request
     * @param  \App\Models\AttendanceMath  $attendanceMath
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceMathRequest $request, AttendanceMath $attendanceMath)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceMath  $attendanceMath
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceMath $attendanceMath)
    {
        //
    }
}
