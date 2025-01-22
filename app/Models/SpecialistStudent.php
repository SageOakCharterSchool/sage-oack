<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistStudent extends Model
{
    protected $perPage = 20;
    protected $table = "specialist_students";
    protected $fillable = [
        'cycle_id',
        'specialist_id',
        'email',
        'name',
        'students_list',
        'student_id',
        'first_name',
        'last_name',
        'created_by',
    ];


    public static function getTableName()
    {
        return (new self())->getTable();
    }


    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    protected function getIdFromEmail()
    {
        $email = \Auth::user()->email;
        $specialistStudent = $this->where("email", $email)->first();
        if ($specialistStudent) {
            return $specialistStudent->specialist_id;
        }
        return false;
    }

    protected function getAllRecordsByStudentIDOnCycle($cycle, $studentID)
    {
        $rows = $this->where('cycle_id', $cycle->id)
            ->where('student_id', $studentID)->get();
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }

    protected function createSpecialist($request,$user) {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $names = explode(" ",$user->name);
        $data = [
            'cycle_id' => $cycle->id,
            'student_id' => $request->studentId,
            'specialist_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'first_name' => $names[0] ?? "",
            'last_name' => $names[1] ?? "",
            'created_by' => \Auth::user()->id,
        ];
        $this->create($data);
    }

    protected function checkIfSpecialistStudentHasRecordsOnCycle($cycle = null)
    {
        if (!$cycle) {
            $cycle = Cycle::getCurrentCycle();
            if (!$cycle) {
                return;
            }
        }
        $rows = $this->where('cycle_id', $cycle->id)->get();
        if ($rows->isNotEmpty()) {
            return true;
        }
        return false;
    }



    protected function getAllSpecialistStudents($teacherId, $request)
    {
        //dd($request->all(),$request->search);
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) { // super admins and managers

            if ($request->has('search')) {
                $myStudents = $this->select('st_ac.*', 'specialist_students.specialist_id', 'specialist_students.email', 'specialist_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                        $join->on('specialist_students.student_id', '=', 'st_ac.student_id');
                        $join->on('specialist_students.cycle_id', '=', 'st_ac.cycle_id');
                    })
                    ->whereNotNull('st_ac.student_id')
                    ->where('specialist_students.cycle_id', $cycle->id)
                    ->where('st_ac.student_id', 'like', '%' . $request->search . '%')
                    ->orWhere('st_ac.column_a', 'like', '%' . $request->search . '%')
                    ->orWhere('st_ac.column_b', 'like', '%' . $request->search . '%')
                    ->orderBy('st_ac.column_b')
                    ->paginate(50);
                //dd($myStudents);
            } else {

                $myStudents =  $this->select('st_ac.*', 'specialist_students.specialist_id', 'specialist_students.email', 'specialist_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                        $join->on('specialist_students.student_id', '=', 'st_ac.student_id');
                        $join->on('specialist_students.cycle_id', '=', 'st_ac.cycle_id');
                    })
                    ->where('specialist_students.cycle_id', $cycle->id)
                    ->whereNotNull('st_ac.student_id')
                    ->orderBy('st_ac.column_b')
                    ->paginate(50);
            }
        } else { // teachers

            if ($request->has('search')) {
                $myStudents =  $this->select('st_ac.*', 'specialist_students.specialist_id', 'specialist_students.email', 'specialist_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                        $join->on('specialist_students.student_id', '=', 'st_ac.student_id');
                        $join->on('specialist_students.cycle_id', '=', 'st_ac.cycle_id');
                    })
                    ->whereNotNull('st_ac.student_id')
                    ->where('specialist_students.specialist_id', $teacherId)
                    ->where('specialist_students.cycle_id', $cycle->id)
                    ->where(function ($query) use ($request) {
                        $query->where('st_ac.student_id', 'like', '%' . $request->search . '%')
                            ->orWhere('st_ac.column_a', 'like', '%' . $request->search . '%')
                            ->orWhere('st_ac.column_b', 'like', '%' . $request->search . '%');
                    })
                    ->orderBy('st_ac.column_b')
                    ->paginate(50);
            } else {
                $myStudents =  $this->select('st_ac.*', 'specialist_students.specialist_id', 'specialist_students.email', 'specialist_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                        $join->on('specialist_students.student_id', '=', 'st_ac.student_id');
                        $join->on('specialist_students.cycle_id', '=', 'st_ac.cycle_id');
                    })
                    ->whereNotNull('st_ac.student_id')
                    ->where('specialist_students.specialist_id', $teacherId)
                    ->where('specialist_students.cycle_id', $cycle->id)
                    ->orderBy('st_ac.column_b')
                    //->toSql();
                    ->paginate(50);
                //dd($myStudents,$teacherId,$cycle->id);
            }
        }
        return $myStudents;
    }

    static function getUserInfoFromId($specialistStudentId)
    {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $specialistStudent = SpecialistStudent::where('specialist_id', $specialistStudentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if ($specialistStudent) {
            $user = User::where('email', $specialistStudent->email)
                ->first();
            if ($user) {
                return $user->email . " -> " . $user->name;
            }
            return "";
        }
    }

    protected function removeRecordsOnCurrentCycle($cycle)
    {
        $this->where('cycle_id', $cycle->id)->delete();
    }

    protected function clearTeacherIdFromAllTables()
    {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $models = GlobalActions::getModelNames();
        // step 1: clear all the teacher Id in all tables
        //         due new upload of this file
        foreach ($models as $model) {
            $myModel = "\App\Models\\$model";
            $myModel::where('cycle_id', $cycle->id)
                ->update([
                    'specialist_id' => null
                ]);
        }
    }
}
