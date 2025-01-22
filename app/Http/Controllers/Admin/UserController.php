<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\LogActivity;
use App\Models\Cycle;
use App\Models\MathList;
use App\Models\SpecialistStudent;
use App\Models\StudentAccounts;
use App\Models\StudentList;
use App\Models\TeacherStudent;
use App\Models\UsersEnabledToImpersonate;
use Illuminate\Support\Facades\Log;
use App\Rules\validTeacher;
use App\Rules\studentUniqueByCycle;
use App\Rules\validStudentEmailUnique;

class UserController extends Controller
{
    public function __construct() {}
    public function index()
    {
        $cycle =  Cycle::getCurrentCycle();
        if (!\Auth::user()->isAdmin()) {
            if (!UsersEnabledToImpersonate::checkIfUserHasImpersonatePermissions(\Auth::user()->id)) {
                return redirect('/home');
            }
        }
        LogActivity::addToLog('index');
        return view('admin.users.index', compact('cycle'));
    }

    public function impersonateList()
    {
        LogActivity::addToLog('index');
        return view('admin.users.impersonate-index');
    }

    public function edit(User $user)
    {
        LogActivity::addToLog('edit user');
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, $userId)
    {
        LogActivity::addToLog('update user');
        $user = User::findOrFail($userId);
        $user->status = ($request->status);
        $user->role_as = ($request->role_as);
        $user->update();

        return redirect('admin/user')->with('message', 'User Updated Succesfully');
    }

    public function resetStudentPassword(Request $request)
    {
        //dd($request->all());
        LogActivity::addToLog('change student password ' . $request->studentId);
        $cycle =  Cycle::getCurrentCycle();
        $studAccount = StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if (!$studAccount) {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }
        if (!$request->newPass || $request->newPass == "") {
            $data = 'Wrong Password';
            return response()->json(['msg' => $data], 401);
        }
        if (!$request->passChanged || $request->passChanged == "0") {
            $data = 'Password changed flag missing';
            return response()->json(['msg' => $data], 401);
        }
        StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->update([
                'column_f' => $request->newPass,
                'password_changed' => 1
            ]);
        $data = 'Update Completed';
        return response()->json(['msg' => $data], 200);
    }

    public function deleteStudentAccount(Request $request)
    {
        //dd($request->all());
        LogActivity::addToLog('delete student account ' . $request->studentId);
        $cycle =  Cycle::getCurrentCycle();
        $studAccount = StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if (!$studAccount) {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }

        StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->delete();
        TeacherStudent::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->delete();

        $data = 'Delete Completed';
        TeacherStudent::reprocessTeacherStudentForAllTables2();
        return response()->json(['msg' => $data], 200);
    }

    public function createStudentAccount(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'teacher_id' => ['required', new validTeacher],
            'student_id' => ['required', new studentUniqueByCycle],
            'student_first_name' => 'required',
            'student_last_name' => 'required',
            'student_email' => ['required', 'email', new validStudentEmailUnique],
            'student_grade' => 'required',
            'student_password' => 'required|min:8|max:8',
            'student_dob' => 'required|date_format:m/d/Y',
        ]);
        LogActivity::addToLog('create student account ' . $request->studentId);
        $cycle =  Cycle::getCurrentCycle();

        StudentAccounts::createStudentAccount($request, $cycle);
        TeacherStudent::manualCreateStudentAccount($request, $cycle);
        TeacherStudent::reprocessTeacherStudentForAllTables2();
        $data = "Student Successfully created";
        return response()->json(['msg' => $data], 200);
    }

    public function getStudentsTeacherInfo(Request $request)
    {
        LogActivity::addToLog('get student teacher Info ' . $request->studentId);
        $cycle =  Cycle::getCurrentCycle();
        $studAccount = StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if (!$studAccount) {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }
        $currentTeacherAssigned = TeacherStudent::select('teacher_id', 'first_name', 'last_name')
            ->where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if (!$currentTeacherAssigned) {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }
        return response()->json([
            'studAccount' => $studAccount,
            'currentTeacherAssigned' => $currentTeacherAssigned,
        ], 200);
    }
    public function getStudentsSpecialistInfo(Request $request)
    {
        LogActivity::addToLog('get student specialist Info ' . $request->studentId);
        $cycle =  Cycle::getCurrentCycle();
        $studAccount = StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if (!$studAccount) {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }
        $currentSpecialistAssigned = SpecialistStudent::select('specialist_id', 'first_name', 'last_name')
            ->where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        // if (!$currentSpecialistAssigned) {
        //     $data = 'Wrong Specialist Id';
        //     return response()->json(['msg' => $data], 401);
        // }
        if ($currentSpecialistAssigned) {
            return response()->json([
                'studAccount' => $studAccount,
                'currentSpecialistAssigned' => $currentSpecialistAssigned,
            ], 200);
        } else {
            return response()->json([
                'studAccount' => $studAccount,
            ], 200);
        }
    }

    public function reassignStudentTeacher(Request $request)
    {
        LogActivity::addToLog('reassign student teacher  ' . $request->studentId);
        $cycle =  Cycle::getCurrentCycle();
        if (!$request->newTeacherId || $request->newTeacherId == "") {
            $data = 'Wrong Teacher Id';
            return response()->json(['msg' => $data], 401);
        }
        if (!$request->studentId || $request->studentId == "") {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }

        $studAccount = StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if (!$studAccount) {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }
        $currentTeacherAssigned = TeacherStudent::where('student_id', $request->studentId)
            ->where('teacher_id', $request->newTeacherId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if ($currentTeacherAssigned) {
            $data = 'This student is already assigned to that Teacher';
            return response()->json(['msg' => $data], 401);
        }
        TeacherStudent::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->update(['teacher_id' => $request->newTeacherId]);
        TeacherStudent::reprocessTeacherStudentForAllTables2();
        return response()->json(['msg' => 'Reassign Completed'], 200);
    }

    public function reassignStudentSpecialist(Request $request)
    {
        LogActivity::addToLog('reassign student specialist  ' . $request->studentId);
        $cycle =  Cycle::getCurrentCycle();
        $user = User::where('id', $request->newSpecialistId)
            ->where('role_as', 4) // specialist
            ->first();
        if (!$user) {
            $data = 'Invalid User Selected';
            return response()->json(['msg' => $data], 401);
        }
        if (!$request->newSpecialistId || $request->newSpecialistId == "") {
            $data = 'Wrong Specialist Id';
            return response()->json(['msg' => $data], 401);
        }
        if (!$request->studentId || $request->studentId == "") {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }


        $studAccount = StudentAccounts::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if (!$studAccount) {
            $data = 'Wrong Student Id';
            return response()->json(['msg' => $data], 401);
        }
        $currentSpecialistAssigned = SpecialistStudent::where('student_id', $request->studentId)
            ->where('specialist_id', $request->newSpecialistId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if ($currentSpecialistAssigned) {
            $data = 'This student is already assigned to that Specialist';
            return response()->json(['msg' => $data], 401);
        }
        $currentSpecialistAssigned = SpecialistStudent::where('student_id', $request->studentId)
            ->where('cycle_id', $cycle->id)
            ->first();
        if ($currentSpecialistAssigned) {
            SpecialistStudent::where('student_id', $request->studentId)
                ->where('cycle_id', $cycle->id)
                ->update(['specialist_id' => $request->newSpecialistId]);
        } else {
            SpecialistStudent::createSpecialist($request,$user);
        }
        return response()->json(['msg' => 'Reassign Completed'], 200);
    }

    public function getUsersForTeacherFromFeeders(User $user)
    {
        $email = $user->email;
        $teacherStudent = TeacherStudent::where("email", $email)->first();
        if ($teacherStudent) {
            $cycle =  Cycle::getCurrentCycle();
            $teacherId = $teacherStudent->teacher_id;
            $studentListRows = StudentList::where('cycle_id', $cycle->id)
                ->where('teacher_id', $teacherId)
                ->get();

            $mathListRows = MathList::where('cycle_id', $cycle->id)
                ->where('teacher_id', $teacherId)
                ->get();
            return view(
                'admin.users.show-students-teache-feed',
                [
                    'user' => $user,
                    'teacherId' => $teacherId,
                    'studentListRows' => $studentListRows,
                    'mathListRows' => $mathListRows
                ]
            );
        }
        return redirect('admin/user')->with('message', 'Teacher Not Found');
    }
}
