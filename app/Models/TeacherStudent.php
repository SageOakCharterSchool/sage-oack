<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GlobalActions;
use App\Models\AttendanceEla;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaracraftTech\LaravelDynamicModel\DynamicModel;
use LaracraftTech\LaravelDynamicModel\DynamicModelFactory;
use Illuminate\Support\Facades\Schema;


class TeacherStudent extends Model
{
    use HasFactory;

    protected $table = "teacher_students";
    /*
    0 => (Staff1) Last Name
    1 => (Staff1) First Name
    2 => (Staff1) Work Email
    3 => (Staff1) Teacher Number
    4 => (Staff1) Staff ID
    5 => (Students1) Last Name
    6 => (Students1) First Name
    7 => (Students1) District ID
    8 => (Students1) SSID (State Student ID Number)
    9 => (Students1) Local Student ID

    */
    protected $fillable = [
        'cycle_id',
        'teacher_id',
        'email',
        'name',
        'students_list',
        'student_id',
        'first_name',
        'last_name',
        'column_d',
        'column_e',
        'column_f',
        'column_g',
        'column_h',
        'column_i',
        'column_j',
        'created_by',
    ];

    public static function getTableName()
    {
        return (new self())->getTable();
    }


    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');
    }

    protected function getIdFromEmail() {
        $email = \Auth::user()->email;
        $teacherStudent = $this->where("email",$email)->first();
        if ($teacherStudent) {
            return $teacherStudent->teacher_id;
        }
        return false;
    }

    protected function getAllRecordsByStudentIDOnCycle($cycle,$studentID) {
        $rows = $this->where('cycle_id',$cycle->id)
                    ->where('student_id',$studentID)->get();
        if ($rows->isNotEmpty()) {
            return $rows;
        }
        return false;
    }

    protected function checkIfTeacherStudentHasRecordsOnCycle($cycle=null) {
        if (!$cycle) {
            $cycle = Cycle::getCurrentCycle();
            if (!$cycle) {
                return;
            }
        }
        $rows = $this->where('cycle_id',$cycle->id)->get();
        if ($rows->isNotEmpty()) {
            return true;
        }
        return false;

    }



    protected function getAllTeacherStudents($teacherId,$request) {
        //dd($request->all(),$request->search);
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) { // super admins and managers

            if ($request->has('search')) {
                $myStudents = $this->select('st_ac.*','teacher_students.teacher_id','teacher_students.email','teacher_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                        $join->on('teacher_students.student_id','=','st_ac.student_id');
                        $join->on('teacher_students.cycle_id','=','st_ac.cycle_id');
                    })
                ->whereNotNull('st_ac.student_id')
                ->where('teacher_students.cycle_id',$cycle->id)
                ->where('st_ac.student_id','like','%'.$request->search.'%')
                    ->orWhere('st_ac.column_a','like','%'.$request->search.'%')
                    ->orWhere('st_ac.column_b','like','%'.$request->search.'%')
                ->orderBy('st_ac.column_b')
                ->paginate(50);
                //dd($myStudents);
            } else {

                $myStudents =  $this->select('st_ac.*','teacher_students.teacher_id','teacher_students.email','teacher_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                    $join->on('teacher_students.student_id','=','st_ac.student_id');
                    $join->on('teacher_students.cycle_id','=','st_ac.cycle_id');
                })
                ->where('teacher_students.cycle_id',$cycle->id)
                ->whereNotNull('st_ac.student_id')
                ->orderBy('st_ac.column_b')
                ->paginate(50);
            }
        } else { // teachers

            if ($request->has('search')) {
                $myStudents =  $this->select('st_ac.*','teacher_students.teacher_id','teacher_students.email','teacher_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                    $join->on('teacher_students.student_id','=','st_ac.student_id');
                    $join->on('teacher_students.cycle_id','=','st_ac.cycle_id');
                })
                ->whereNotNull('st_ac.student_id')
                ->where('teacher_students.teacher_id',$teacherId)
                ->where('teacher_students.cycle_id',$cycle->id)
                        ->where(function($query) use($request)
                        {
                            $query->where('st_ac.student_id','like','%'.$request->search.'%')
                            ->orWhere('st_ac.column_a','like','%'.$request->search.'%')
                            ->orWhere('st_ac.column_b','like','%'.$request->search.'%');
                        })
                        ->orderBy('st_ac.column_b')
                        ->paginate(50);
            } else {
                $myStudents =  $this->select('st_ac.*','teacher_students.teacher_id','teacher_students.email','teacher_students.name')
                    ->leftJoin('student_accounts as st_ac', function ($join) {
                    $join->on('teacher_students.student_id','=','st_ac.student_id');
                    $join->on('teacher_students.cycle_id','=','st_ac.cycle_id');
                })
                ->whereNotNull('st_ac.student_id')
                ->where('teacher_students.teacher_id',$teacherId)
                ->where('teacher_students.cycle_id',$cycle->id)
                ->orderBy('st_ac.column_b')
                //->toSql();
                ->paginate(50);
                //dd($myStudents,$teacherId,$cycle->id);
            }
        }
        return $myStudents;
    }

    static function getUserInfoFromId($teacherStudentId) {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $teacherStudent = TeacherStudent::where('teacher_id',$teacherStudentId)
                            ->where('cycle_id',$cycle->id)
                            ->first();
        if ($teacherStudent) {
            $user = User::where('email',$teacherStudent->email)
                        ->first();
            if ($user) {
                return $user->email . " -> " . $user->name;
            }
            return "";
        }
    }

    protected function removeRecordsOnCurrentCycle($cycle) {
        $this->where('cycle_id',$cycle->id)->delete();
    }

    protected function clearTeacherIdFromAllTables() {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $models = GlobalActions::getModelNames();
        // step 1: clear all the teacher Id in all tables
        //         due new upload of this file
        foreach ($models as $model) {
            $myModel = "\App\Models\\$model";
            $myModel::where('cycle_id',$cycle->id)
                ->update([
                    'teacher_id' => null
                ]);
        }
    }
    protected function reprocessTeacherStudentForAllTables() {

        set_time_limit(0);
        ini_set('memory_limit','-1');
        return;
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $this->clearTeacherIdFromAllTables();
        $models = GlobalActions::getModelNames();
        $teachersStudents = [];
        //$teachersStudents = TeacherStudent::select('student_id')->get()->keyBy('teacher_id')->toArray();
        $rows = TeacherStudent::orderBy('teacher_id')
                    ->where('cycle_id',$cycle->id)
                    ->orderBy('student_id')
                    ->get(['student_id','teacher_id']);
        foreach ($rows as $row) {
            $teachersStudents[$row->teacher_id][] = $row->student_id;
        }
        $tables = config('constants.tables');
        $tables[] = 'consolidateds';
        //dd($tables);
        foreach ($tables as $table) {
        //foreach (['i_ready_reading_eoy_s'] as $table) {

        //foreach ($models as $model) {
            //$myModel = "\App\Models\\$model";
            Log::info($table);
            //DB::disableQueryLog();
            foreach ($teachersStudents as $teacherId => $teachersStudent) {
                foreach ($teachersStudent as $studentID) {
                    //echo ($teacherId . " -> " . $studentID) . "<br>";
                    $myTable = $table;
                    DB::table($myTable)->select(['id'])
                        ->where('cycle_id',$cycle->id)
                        ->where('student_id',$studentID)
                        ->whereNull('teacher_id')
                        //->get();
                        ->chunkById(100, function ($rows) use($teacherId,$myTable) {
                            //dd($rows);
                            foreach($rows as $row) {
                                DB::table($myTable)
                                    ->where('id',$row->id)
                                    ->update([
                                        'teacher_id' => $teacherId
                                    ]);
                            }
                            unset($rows);
                        });

                    //$myModel::UpdateTeacherIdToItsStudents($teacherId,$studentID,$cycle);
                }
            }
            Log::info("Completed -> " . $table);
            Log::info('MEMORY USAGE: '.memory_get_usage(true) );
        }

    }
    protected function reprocessTeacherStudentForAllTables2() {
        set_time_limit(0);
        ini_set('memory_limit','-1');

        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $this->clearTeacherIdFromAllTables();
        $models = GlobalActions::getModelNames();
        $teachersStudents = [];
        //$teachersStudents = TeacherStudent::select('student_id')->get()->keyBy('teacher_id')->toArray();
        $rows = TeacherStudent::orderBy('teacher_id')
                    ->where('cycle_id',$cycle->id)
                    ->orderBy('student_id')
                    ->get(['student_id','teacher_id']);
        foreach ($rows as $row) {
            $teachersStudents[$row->teacher_id][] = $row->student_id;
        }
        //dd( $teachersStudents);
        $tables = config('constants.tables');
        //$tables[] = 'consolidateds';
        $tables[] = 'consolidate3s';
        // for ($i=2; $i<=30; $i++) {
        //     unset($tables[$i]);
        // }
        //dd($tables);
        foreach ($tables as $table) {
            foreach ($teachersStudents as $teacherId => $teachersStudent) {
                    //echo ($teacherId . " -> " . $studentID) . "<br>";
                    $myTable = $table;
                    $myIds = DB::table($myTable)
                        ->where('cycle_id',$cycle->id)
                        ->whereIn('student_id',$teachersStudent)
                        ->whereNull('teacher_id')
                        ->pluck('id')->toArray();
                    DB::table($myTable)
                        ->whereIn('id',$myIds)
                        ->update([
                            'teacher_id' => $teacherId
                    ]);

                    //$myModel::UpdateTeacherIdToItsStudents($teacherId,$studentID,$cycle);
            }
            Log::info("Completed -> " . $table);
            Log::info('MEMORY USAGE: '.memory_get_usage(true) );
        }

    }

    public function attendanceEla(Cycle $cycle=null) {
        $tmp = $this->hasMany(AttendanceEla::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function attendanceMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(AttendanceMath::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function consolidated(Cycle $cycle=null) {

        $tmp = $this->hasMany(Consolidate3::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }


    public function easyCBMFall(Cycle $cycle=null) {

        $tmp = $this->hasMany(EasyCBMFall::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function easyCBMProgMon(Cycle $cycle=null) {

        $tmp = $this->hasMany(EasyCBMProgMon::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function freckleMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(FreckleMinutes::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathBOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathBOY::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathEOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathEOY::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathMidYear(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathMidYear::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyMathMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyMathMinutes::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingBOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingBOY::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingEOY(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingEOY::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingMidYear(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingMidYear::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function iReadyReadingMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(IReadyReadingMinutes::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function math180Minutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(Math180Minutes::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function mathList(Cycle $cycle=null) {

        $tmp = $this->hasMany(MathList::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function read180Minutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(Read180Minutes::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }


    public function starEOYMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarEOYMath::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starEOYReading(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarEOYReading::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starFallMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarFallMath::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starFallReading(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarFallReading::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starMidYearMath(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarMidYearMath::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function starMidYearReading(Cycle $cycle=null) {

        $tmp = $this->hasMany(StarMidYearReading::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function studentList(Cycle $cycle=null) {

        $tmp = $this->hasMany(StudentList::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    public function transMathMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(TransMathMinutes::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }
    public function vMathMinutes(Cycle $cycle=null) {

        $tmp = $this->hasMany(VMathMinutes::class,'teacher_id','teacher_id');
        if (!$cycle) {
            return $tmp;
        }
        return $tmp->where("cycle_id",$cycle->id)->get();
    }

    protected function manualCreateStudentAccount($request,$cycle) {
        $teacherStudent = TeacherStudent::where('teacher_id',$request->teacher_id)
                            ->where('cycle_id',$cycle->id)
                            ->first();
        if ($teacherStudent) {
            $data = [
                'cycle_id' => $cycle->id, //cicly id
                'created_by' => \Auth::id(), //created_by
                'teacher_id' => $request->teacher_id,
                'email' => $teacherStudent->email,
                'name' => '',
                'students_list' => "",
                'student_id' => $request->student_id,
                'first_name' => $teacherStudent->first_name, // first name
                'last_name' => $teacherStudent->last_name, // last name
                'column_d' => $request->teacher_id,
                'column_e' => null,
                'column_f' => $request->student_first_name, // first name
                'column_g' => $request->student_last_name, // last name
                'column_h' => null,
                'column_i' => $request->student_id,
                'column_j' => null,
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ];
            TeacherStudent::insert($data);
        }
    }

    protected function reassignTeacherIds($cycleId=null) {
        if (!$cycleId) {
            $cycle = Cycle::getCurrentCycle();
            $cycleId = $cycle->id;
        }
        set_time_limit(0);
        ini_set('memory_limit','-1');
        $table = MasterTables::getTableId('teacher_students');
        $id1 = $table->id;
        $table = MasterTables::getTableId('tutor');
        $id2 = $table->id;
        $tablesToSkip = [$id1, $id2];

        // if (in_array($this->tableId, $tablesToSkip)) {
        //     return;
        // }

        //dd($table);
        $tempTableName = "consolidated_cycle_" . $cycleId;
        $tempTableModel = app(DynamicModelFactory::class)->create(DynamicModel::class, $tempTableName);

        $teacherRows = MultiTableFields::select('teacher_id', 'student_id')
            ->where('cycle_id', $cycleId)
            ->where("table_id", $id1)
            ->groupBy('teacher_id','student_id')
            ->get();
        foreach ($teacherRows as $teacherRow) {
            MultiTableFields::where('cycle_id', $cycleId)
                ->whereNotIn("table_id", $tablesToSkip)
                ->where("student_id", $teacherRow->student_id)
                ->where("teacher_id", 0)
                ->update([
                    'teacher_id' => $teacherRow->teacher_id
                ]);
            $tempTableModel->where("student_id", $teacherRow->student_id)
                        ->where("teacher_id", 0)
                            ->update([
                                'teacher_id' => $teacherRow->teacher_id
                            ]);
        }
    }


}
