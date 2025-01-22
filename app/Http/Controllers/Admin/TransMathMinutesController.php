<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreTransMathMinutesRequest;
use App\Http\Requests\UpdateTransMathMinutesRequest;
use App\Models\TransMathMinutes;

class TransMathMinutesController extends Controller
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
     * @param  \App\Http\Requests\StoreTransMathMinutesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransMathMinutesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransMathMinutes  $transMathMinutes
     * @return \Illuminate\Http\Response
     */
    public function show(TransMathMinutes $transMathMinutes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransMathMinutes  $transMathMinutes
     * @return \Illuminate\Http\Response
     */
    public function edit(TransMathMinutes $transMathMinutes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransMathMinutesRequest  $request
     * @param  \App\Models\TransMathMinutes  $transMathMinutes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransMathMinutesRequest $request, TransMathMinutes $transMathMinutes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransMathMinutes  $transMathMinutes
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransMathMinutes $transMathMinutes)
    {
        //
    }
}
