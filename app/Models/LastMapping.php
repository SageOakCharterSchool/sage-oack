<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastMapping extends Model
{
    use HasFactory;

    protected $table = "last_mappings";

    protected $fillable = [
        'table_id',
        'table_name',
        'last_mapping',
        'created_by',
    ];

    protected function getLastMapping($tableId) {
        $lastMapping = $this->where('table_id',$tableId)
                        ->latest()->first();
        if ($lastMapping) {
            return explode(",",$lastMapping->last_mapping);
        }
        return false;
    }

    protected function createLastMapping($request) {
        $table1 = MasterTables::getTableId('teacher_students');
        $table2 = MasterTables::getTableId('student_accounts');
        if ($request->table_id == $table1->id) {
            $tmp = [
                "student_id_cell_name" => str_replace('"','',$request->student_id_cell_name),
                "teacher_id_cell_name" => str_replace('"','',$request->teacher_id_cell_name),
                "teacher_email_cell_name" => str_replace('"','',$request->teacher_email_cell_name),
                "teacher_first_name_cell_name" => str_replace('"','',$request->teacher_first_name_cell_name),
                "teacher_last_name_cell_name" => str_replace('"','',$request->teacher_last_name_cell_name),
            ];
        } else if ($request->table_id == $table2->id) {
            $tmp = [
                "student_id_cell_name" => str_replace('"','',$request->student_id_cell_name),
                "email_id_cell_name" => str_replace('"','',$request->email_id_cell_name),
                "first_name_id_cell_name" => str_replace('"','',$request->first_name_id_cell_name),
                "last_name_id_cell_name" => str_replace('"','',$request->last_name_id_cell_name),
                "dob_id_cell_name" => str_replace('"','',$request->dob_id_cell_name),
                "password_id_cell_name" => str_replace('"','',$request->password_id_cell_name),
                "program_id_cell_name" => str_replace('"','',$request->program_id_cell_name),
                "grade_id_cell_name" => str_replace('"','',$request->grade_id_cell_name),
                "sis_id_cell_name" => str_replace('"','',$request->sis_id_cell_name),
            ];
        } else {
            $tmp = [
                "student_id_cell_name" => $request->student_id_cell_name,
            ];
        }
        $data = [
            'table_id' => $request->table_id ,
            'table_name' => $request->table_name ,
            'last_mapping' => json_encode($tmp),
            'created_by' => \Auth::user()->id,
        ];
        $this->create($data);
    }

}
