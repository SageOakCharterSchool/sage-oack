<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CycleFormRequest;
use App\Models\Cycle;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;

class CycleController extends Controller
{
    public function index() {
        LogActivity::addToLog('Show cycles');
        return view('admin.cycles.index');
    }

    public function create() {
        LogActivity::addToLog('Create cycles');
        return view('admin.cycles.create');
    }

    public function store(CycleFormRequest $request) {
        LogActivity::addToLog('Store cycle');
        $validateData = $request->validated();

        $cycle = new Cycle;
        $cycle->date_from = $validateData['date_from'];
        $cycle->date_to = $validateData['date_to'];
        $cycle->cycle_name = $validateData['cycle_name'];
        $cycle->created_by = Auth::id();
        $cycle->save();

        return redirect('admin/cycle')->with('message','Cycle Added Succesfully');

    }

    public function edit(Cycle $cycle) {
        LogActivity::addToLog('edit cycle');
        return view('admin.cycles.edit',['cycle' => $cycle]);
    }

    public function update(CycleFormRequest $request, $cycleId) {
        LogActivity::addToLog('update cycle');
        $cycle = Cycle::findOrFail($cycleId);

        $validateData = $request->validated();

        $cycle->date_from = $validateData['date_from'];
        $cycle->date_to = $validateData['date_to'];
        $cycle->cycle_name = $validateData['cycle_name'];
        $cycle->created_by = Auth::id();
        $cycle->update();

        return redirect('admin/cycle')->with('message','Cycle Updated Succesfully');

    }



}
