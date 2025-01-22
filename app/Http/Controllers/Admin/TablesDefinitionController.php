<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use App\Models\ConsolidateMapping;
use App\Models\Cycle;
use App\Models\Formula;
use App\Models\LastMapping;
use App\Models\MasterTables;
use App\Models\MultiTableFields;
use App\Models\Report;
use App\Models\TablesMapping;

class TablesDefinitionController extends Controller
{
    public function index() {
        LogActivity::addToLog('index');
        $cycle =  Cycle::getCurrentCycle();
        MasterTables::createMasterTables($cycle->id);
        $tables = MasterTables::where("cycle_id",$cycle->id)->get();
        return view('admin.tables-fields.index',compact('tables'));
    }

    public function cloneTables() {
        LogActivity::addToLog('clone-tables');
        $cyclesFrom = Cycle::where('date_to','<=',date("Y-m-d"))
                    ->get();
        $cyclesTo = Cycle::where('date_from','>',date("Y-m-d"))
                    ->get();
        $cycles = Cycle::get();
        return view('admin.tables-fields.clone-tables',compact('cycles'));
    }

    public function cloneTablesStore(Request $request) {
        set_time_limit(0);
        ini_set('memory_limit','-1');
        $this->validate($request, [
            'cycle_from' => 'required',
            'cycle_to' => 'required|different:cycle_from',
        ]);
        $clonedTables = MasterTables::cloneTablesIntoNewCycle($request->cycle_from, $request->cycle_to);
        $clonedFormulas = Formula::cloneFormulaIntoNewCycle($request->cycle_from, $request->cycle_to, $clonedTables);
        ConsolidateMapping::cloneConsolidateMappingIntoNewCycle($request->cycle_from, $request->cycle_to, $clonedTables, $clonedFormulas);
        Report::cloneReportsIntoNewCycle($request->cycle_from, $request->cycle_to, $clonedTables, $clonedFormulas);
        return redirect('/admin/table-def')->with('message','Tables clonned succesfully');
    }

    public function create() {
        LogActivity::addToLog('index');

        return view('admin.tables-fields.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'table_name' => 'required|max:55',
        ]);
        $cycle =  Cycle::getCurrentCycle();
        $data = [
            'cycle_id' => $cycle->id,
            'table_name' => $request->table_name,
            'created_by' => \Auth::user()->id,
        ];
        MasterTables::create($data);
        LogActivity::addToLog('store');
        return redirect('/admin/table-def')->with('message','Table created succesfully');
    }

    public function uploadFiles(Request $request) {
        //dd($request->all());
        $this->validate($request, [
            'file_to_upload' => 'required|mimes:csv|max:10000',
            'student_id_cell_name' => 'required|alpha',
            'teacher_id_cell_name' => 'required_if:is_teacher_table,1',
            'teacher_email_cell_name' => 'required_if:is_teacher_table,1',
            'teacher_first_name_cell_name' => 'required_if:is_teacher_table,1',
            'teacher_last_name_cell_name' => 'required_if:is_teacher_table,1',
            'teacher_student_id_cell_name' => 'required_if:is_teacher_table,1',
            'first_name_id_cell_name' => 'required_if:is_student_account_table,1',
            'last_name_id_cell_name' => 'required_if:is_student_account_table,1',
            'email_id_cell_name' => 'required_if:is_student_account_table,1',
            'dob_id_cell_name' => 'required_if:is_student_account_table,1',
            'password_id_cell_name' => 'required_if:is_student_account_table,1',
        ]);

        ini_set('post_max_size', '128M');
        ini_set('upload_max_filesize', '128M');
        if (!\Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        LogActivity::addToLog('Upload Files');
        $size = $request->file('file_to_upload')->getSize();
        $fileType = $request->file('file_to_upload')->getClientMimeType();

        if (!($fileType == 'text/csv' || $fileType == 'application/octet-stream')) {
            abort(
                response()->json(['message' => 'Invalid file type'], 405)
            );
        }
        $post_max_size = (ini_get('upload_max_filesize'));
        if ($size) {
            if ($size > 40000000 ) { // 2 MB
                abort(
                    response()->json(['message' => 'Invalid file size'], 403)
                );
            }
        }
        if ($request->hasFile('file_to_upload')) {
            //dd($request->all());
            LastMapping::createLastMapping($request);
            set_time_limit(0);
            ini_set('memory_limit','-1');
            $cycle =  Cycle::getCurrentCycle();
            MasterTables::where('id',$request->table_id)
                            ->where('cycle_id',$cycle->id)
                            ->update(['process_status'=>4]);
            MultiTableFields::loadDataIntoFile($request);

        }
        return response()->json(['message' => 'File Upload Completed'], 200);

    }

    public function edit($id) {
        $table = MasterTables::where("id",$id)->first();
        if ($table) {
            LogActivity::addToLog('edit');
            return view('admin.tables-fields.edit',compact('table'));
        } else {
            return redirect('/admin/table-def')->with('error-message','Wrong Table');
        }

    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'table_name' => 'required|max:55',
        ]);

        $data = [
            'table_name' => $request->table_name,
        ];
        MasterTables::where("id",$id)->update($data);
        LogActivity::addToLog('update');
        return redirect('/admin/table-def')->with('message','Table updated succesfully');
    }

    public function getLastMapping(Request $request) {
        $lastMapping = LastMapping::getLastMapping($request->tableId);
        return response()->json($lastMapping, 200);
    }

}

