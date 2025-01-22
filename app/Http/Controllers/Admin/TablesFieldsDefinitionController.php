<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use App\Models\Cycle;
use App\Models\MasterTables;
use App\Models\TablesMapping;
use App\Rules\UniqueColumnInTable;

class TablesFieldsDefinitionController extends Controller
{
    public function index($tableId) {
        LogActivity::addToLog('index');
        $cycle =  Cycle::getCurrentCycle();

        $table = MasterTables::where("cycle_id",$cycle->id)
                    ->where('id',$tableId)
                    ->first();
        if (!$table) {
            return redirect('/admin/table-def')->with('error-message','Wrong Table');
        }
        $tableFields = TablesMapping::where("cycle_id",$cycle->id)
                    ->where('table_id',$tableId)
                    ->orderBy('id')
                    ->get();
        return view('admin.fields-definition.index',compact('tableFields','table'));
    }

    public function create($tableId) {
        $cycle =  Cycle::getCurrentCycle();
        $table = MasterTables::where("cycle_id",$cycle->id)
                    ->where('id',$tableId)
                    ->first();
        if (!$table) {
            return redirect('/admin/table-def')->with('error-message','Wrong Table');
        }
        LogActivity::addToLog('index');
        return view('admin.fields-definition.create',compact('table'));
    }

    public function store(Request $request, $tableId) {
        $cycle =  Cycle::getCurrentCycle();
        $this->validate($request, [
            'column' => ['required','max:25',new UniqueColumnInTable($tableId,$cycle->id)],
            'column_title' => 'required|max:155',
            'is_student_id' => 'required',
        ]);

        $data = [
            'cycle_id' => $cycle->id,
            'table_id' => $tableId,
            'cycle_id' => $cycle->id,
            'column' => $request->column,
            'column_title' => $request->column_title,
            'is_student_id' => $request->is_student_id,
            'created_by' => \Auth::user()->id,
        ];
        TablesMapping::create($data);
        LogActivity::addToLog('store');

        return redirect('/admin/field-def/' . $tableId . '/create')->with('message','Field created succesfully');
    }

    public function edit($tableId,$fieldId) {
        $cycle =  Cycle::getCurrentCycle();
        $table = MasterTables::where("id",$tableId)->first();
        if ($table) {
            $tableFields = TablesMapping::where("cycle_id",$cycle->id)
                    ->where('table_id',$tableId)
                    ->where('id',$fieldId)
                    ->first();
            if (!$tableFields) {
                return redirect('admin/field-def/'. $tableId .'/fields')->with('error-message','Wrong Table');
            }
            LogActivity::addToLog('edit');
            return view('admin.fields-definition.edit',compact('table','tableFields'));
        } else {
            return redirect('/admin/table-def')->with('error-message','Wrong Table');
        }
    }

    public function update(Request $request, $tableId, $id) {
        $cycle =  Cycle::getCurrentCycle();
        $this->validate($request, [
            'column' => ['required','max:25',new UniqueColumnInTable($tableId,$cycle->id,$id)],
            'column_title' => 'required|max:155',
            'is_student_id' => 'required',
        ]);

        $data = [
            'column' => $request->column,
            'column_title' => $request->column_title,
            'is_student_id' => $request->is_student_id,
        ];
        TablesMapping::where("id",$id)->update($data);
        LogActivity::addToLog('update');
        return redirect('admin/field-def/' . $tableId . '/fields')->with('message','Field updated succesfully');
    }

    public function delete(Request $request) {
        $cycle =  Cycle::getCurrentCycle();
        $tableFields = TablesMapping::where("cycle_id",$cycle->id)
                        ->where('table_id',$request->tableId)
                        ->where('id',$request->fieldId)
                        ->delete();
        return redirect('admin/field-def/' . $request->tableId . '/fields')->with('message','Field deleted succesfully');
    }

    public function consolidateMapping() {
        return view('admin.consolidate-map.index');
    }

}
