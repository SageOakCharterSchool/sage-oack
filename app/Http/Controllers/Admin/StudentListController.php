<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreStudentListRequest;
use App\Http\Requests\UpdateStudentListRequest;
use App\Models\StudentList;

class StudentListController extends Controller
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
     * @param  \App\Http\Requests\StoreStudentListRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentListRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentList  $studentList
     * @return \Illuminate\Http\Response
     */
    public function show(StudentList $studentList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentList  $studentList
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentList $studentList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentListRequest  $request
     * @param  \App\Models\StudentList  $studentList
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentListRequest $request, StudentList $studentList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentList  $studentList
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentList $studentList)
    {
        //
    }
}
