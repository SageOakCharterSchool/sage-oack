<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use App\Models\BatchReports;
use App\Models\ConsolidateColor;
use App\Models\ConsolidateGeneration;
use App\Models\ConsolidateMapping;
use App\Models\Cycle;
use App\Models\FileUploads;
use App\Models\Formula;
use App\Models\LastMapping;
use App\Models\MasterTables;
use App\Models\MultiTableFields;
use App\Models\Report;
use App\Models\StudentAccounts;
use App\Models\TableAlias;
use App\Models\TablesMapping;
use App\Models\TeacherStudent;
use App\Rules\UniqueTableAlias;

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
        $consolidateGeneration = ConsolidateGeneration::checkstatus();
        $erroredTable = BatchReports::isErroredTable();
        return view('admin.tables-fields.clone-tables',compact('cycles','consolidateGeneration','erroredTable'));
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
        $cloneConsolidateColors = ConsolidateColor::cloneColorsIntoNewCycle($request->cycle_from, $request->cycle_to);
        ConsolidateMapping::cloneConsolidateMappingIntoNewCycle($request->cycle_from, $request->cycle_to, $clonedTables, $clonedFormulas);
        Report::cloneReportsIntoNewCycle($request->cycle_from, $request->cycle_to, $clonedTables, $clonedFormulas);
        MultiTableFields::backupTableWithNewCycle($request->cycle_from, $request->cycle_to);
        return redirect('/admin/table-def')->with('message','Tables clonned succesfully');
    }

    public function resetTablesInfo(Request $request) {
        LogActivity::addToLog('reset-tables');
        set_time_limit(0);
        ini_set('memory_limit','-1');
        $this->validate($request, [
            'reset_tables' => 'required',
        ]);
        MasterTables::resetTablesInfo();
        return redirect('/admin/table-def/clone-tables')->with('message','Tables reseted succesfully');

    }

    public function resetConsolidated(Request $request) {
        LogActivity::addToLog('reset-consolidated');
        set_time_limit(0);
        ini_set('memory_limit','-1');
        $this->validate($request, [
            'reset_consolidated' => 'required',
        ]);
        ConsolidateGeneration::resetTablesInfo();
        return redirect('/admin/table-def/clone-tables')->with('message','Reset consolidated succesfully');

    }
    public function resetErrorTable(Request $request) {
        LogActivity::addToLog('reset-error-tables');
        set_time_limit(0);
        ini_set('memory_limit','-1');
        $this->validate($request, [
            'reset_errored_table' => 'required_without:remove_errored_table',
            'remove_errored_table' => 'required_without:reset_errored_table',
            'reset_table_id' => 'required',
        ]);
        if (!BatchReports::checkIfTableIsInError($request->reset_table_id)) {
            return redirect('/admin/table-def/clone-tables')->with('error','That Table seems is not in error');
        }
        $cycle =  Cycle::getCurrentCycle();
        if ($request->reset_errored_table == 1) {
            LogActivity::addToLog('reset-error-tables');
            BatchReports::resetTableErrored($request->reset_table_id,$cycle->id);
        }
        if ($request->remove_errored_table == 1) {
            LogActivity::addToLog('remove-error-tables');
            BatchReports::removeTableErrored($request->reset_table_id,$cycle->id);
        }
        return redirect('/admin/table-def/clone-tables')->with('message','Reset error table succesfully');

    }

    public function create() {
        LogActivity::addToLog('index');

        return view('admin.tables-fields.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'table_name' => ['required','max:55',new UniqueTableAlias($request->table_name)],
        ]);
        $cycle =  Cycle::getCurrentCycle();
        $data = [
            'cycle_id' => $cycle->id,
            'table_name' => $request->table_name,
            'table_alias' => $request->table_name,
            'created_by' => \Auth::user()->id,
        ];
        MasterTables::create($data);
        TableAlias::create([
            'table_name' => $request->table_name,
            'table_alias' => $request->table_name,
            'created_by' => \Auth::user()->id,
        ]);
        LogActivity::addToLog('store');
        return redirect('/admin/table-def')->with('message','Table created succesfully');
    }

    public function uploadFiles(Request $request) {

        //dd($request->all());
        //dd($request->all(),$request->file());
        $this->validate($request, [
            'file_to_upload' => 'required|mimetypes:text/plain,text/csv|max:10000',
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
            'table_alias' => 'required|max:55',
        ]);

        $data = [
            'table_alias' => $request->table_alias,
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

