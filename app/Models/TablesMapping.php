<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class TablesMapping extends Model
{
    use HasFactory;
    protected $table = 'tables_mappings';
    protected $fillable = [
        'cycle_id',
        'table_id',
        'column',
        'column_title',
        'is_student_id',
        'is_student_email',
        'is_teacher_id',
        'is_teacher_email',
        'is_teacher_first_name',
        'is_teacher_last_name',
        'is_teacher_student_id',
        'is_first_name',
        'is_last_name',
        'is_program_id',
        'is_grade_id',
        'is_sis_id',
        'is_dob',
        'is_password',
        'created_by',
    ];

    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    protected function getFieldsForTable($cycleId, $tableId)
    {
        $fields = [];
        $rows = $this->where("cycle_id", $cycleId)
            ->where("table_id", $tableId)
            ->orderBy("id")
            ->get();
        $col = 0;
        $isStudent = null;
        $isTeacher = null;
        $isTeacherFirstName = null;
        $isTeacherLastName = null;
        $isTeacherEmail = null;
        $isStudentEmail = null;
        $isTeacherStudentId = null;
        $isFirstName = null;
        $isProgram = null;
        $isGrade = null;
        $isSIS = null;
        $isLastName = null;
        $isDOB = null;
        $isPassword = null;
        foreach ($rows as $row) {
            //Log::info($row);
            if ($isStudent == null) {
                ($row->is_student_id == 1) ? $isStudent = $row->column : $isStudent = null;
            }
            if ($isTeacher == null) {
                ($row->is_teacher_id == 1) ? $isTeacher = $row->column : $isTeacher = null;
            }
            if ($isTeacherFirstName == null) {
                ($row->is_teacher_first_name == 1) ? $isTeacherFirstName = $row->column : $isTeacherFirstName = null;
            }
            if ($isTeacherLastName == null) {
                ($row->is_teacher_last_name == 1) ? $isTeacherLastName = $row->column : $isTeacherLastName = null;
            }
            if ($isTeacherEmail == null) {
                ($row->is_teacher_email == 1) ? $isTeacherEmail = $row->column : $isTeacherEmail = null;
            }
            if ($isStudentEmail == null) {
                ($row->is_student_email == 1) ? $isStudentEmail = $row->column : $isStudentEmail = null;
            }
            if ($isTeacherStudentId == null) {
                ($row->is_teacher_student_id == 1) ? $isTeacherStudentId = $row->column : $isTeacherStudentId = null;
            }
            if ($isFirstName == null) {
                ($row->is_first_name == 1) ? $isFirstName = $row->column : $isFirstName = null;
            }
            if ($isLastName == null) {
                ($row->is_last_name == 1) ? $isLastName = $row->column : $isLastName = null;
            }
            if ($isDOB == null) {
                ($row->is_dob == 1) ? $isDOB = $row->column : $isDOB = null;
            }
            if ($isGrade == null) {
                ($row->is_grade_id == 1) ? $isGrade = $row->column : $isGrade = null;
            }
            if ($isProgram == null) {
                ($row->is_program_id == 1) ? $isProgram = $row->column : $isProgram = null;
            }
            if ($isSIS == null) {
                ($row->is_sis_id == 1) ? $isSIS = $row->column : $isSIS = null;
            }
            if ($isPassword == null) {
                ($row->is_password == 1) ? $isPassword = $row->column : $isPassword = null;
            }
            $fields[$row->column] = [
                'fieldId' => $row->id,
                'column' => $row->column,
                'colNumber' => $col++,
            ];
        }
        return compact(
            'fields',
            'isStudent',
            'isTeacher',
            'isFirstName',
            'isLastName',
            'isDOB',
            'isPassword',
            'isGrade',
            'isProgram',
            'isSIS',
            'isTeacherFirstName',
            'isTeacherLastName',
            'isTeacherEmail',
            'isStudentEmail',
            'isTeacherStudentId'
        );
    }

    protected function cloneFieldsIntoClonedTable($cycleFrom, $cycleTo, $tableId, $newTableId)
    {
        $clonedFields = [];
        $rows = $this->where("cycle_id", (int)$cycleFrom)
            ->where("table_id", (int)$tableId)
            ->orderBy("id")
            ->get();
        //dd($cycleFrom,$cycleTo,$tableId,$rows);
        foreach ($rows as $row) {
            $newField = $row->replicate();
            $newField->cycle_id = (int)$cycleTo;
            $newField->table_id = (int)$newTableId;
            $newField->created_by = \Auth::user()->id;
            $newField->save();
        }
    }


    protected function buildAlphaColumns(): array
    {
        $startColumn = 'A';
        $endColumn = 'ZZ';
        ++$endColumn;
        $columnArray = [];
        for ($column = $startColumn; $column != $endColumn; ++$column) {
            $columnArray[] = $column;
        }
        return $columnArray;
    }

    protected function buildFieldsFromFirstRow($cycleId, $tableId, $firstRow, $request)
    {
        $this->where("cycle_id", $cycleId)
            ->where("table_id", $tableId)
            ->delete();
        $alphaColName = $this->buildAlphaColumns();

        //dd($alphaColName);
        $fieldsForThisTableFromUpload = [];
        //dd($request->all(),$request->student_id_cell_nam);
        foreach ($firstRow as $k => $row) {
            $data = [
                'cycle_id' => $cycleId,
                'table_id' => $tableId,
                'column' => "Column_" . $alphaColName[$k],
                'column_title' => $row,
                'is_student_id' => ($alphaColName[$k] == strtoupper($request->student_id_cell_name)) ? 1 : 0,
                'is_teacher_email' => ($alphaColName[$k] == strtoupper($request->teacher_email_cell_name)) ? 1 : 0,
                'is_teacher_id' => ($alphaColName[$k] == strtoupper($request->teacher_id_cell_name)) ? 1 : 0,
                'is_teacher_first_name' => ($alphaColName[$k] == strtoupper($request->teacher_first_name_cell_name)) ? 1 : 0,
                'is_teacher_last_name' => ($alphaColName[$k] == strtoupper($request->teacher_last_name_cell_name)) ? 1 : 0,
                'is_teacher_student_id' => ($alphaColName[$k] == strtoupper($request->teacher_student_id_cell_name)) ? 1 : 0,
                'is_student_email' => ($alphaColName[$k] == strtoupper($request->email_id_cell_name)) ? 1 : 0,
                'is_first_name' => ($alphaColName[$k] == strtoupper($request->first_name_id_cell_name)) ? 1 : 0,
                'is_last_name' => ($alphaColName[$k] == strtoupper($request->last_name_id_cell_name)) ? 1 : 0,
                'is_dob' => ($alphaColName[$k] == strtoupper($request->dob_id_cell_name)) ? 1 : 0,
                'is_password' => ($alphaColName[$k] == strtoupper($request->password_id_cell_name)) ? 1 : 0,
                'is_grade_id' => ($alphaColName[$k] == strtoupper($request->grade_id_cell_name)) ? 1 : 0,
                'is_sis_id' => ($alphaColName[$k] == strtoupper($request->sis_id_cell_name)) ? 1 : 0,
                'is_program_id' => ($alphaColName[$k] == strtoupper($request->program_id_cell_name)) ? 1 : 0,
                'created_by' => \Auth::user()->id,
            ];
            //Log::info($data);
            TablesMapping::create($data);
        }
        //dd($request->all());
        $fieldsForThisTableFromUpload = $this->getFieldsForTable($cycleId, $tableId);
        return $fieldsForThisTableFromUpload;
    }
}
