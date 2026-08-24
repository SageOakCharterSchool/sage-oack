<?php

namespace App\Models;

use App\Helpers\JMHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;

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

    protected function createSpecialist($request, $user)
    {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $names = explode(" ", $user->name);
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

    protected function createSpecialistFromUsers()
    {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            return;
        }
        $allSpecialist = User::where('role_as', 4)
            ->where('status', 1)
            ->get();
        foreach ($allSpecialist as $row) {
            $existAsSpecialist = $this->where("email", $row->email)
                ->where('cycle_id', $cycle->id)
                ->first();
            if (!$existAsSpecialist) {
                $names = explode(" ", $row->name);
                $data = [
                    'cycle_id' => $cycle->id,
                    'student_id' => null,
                    'specialist_id' => $row->id,
                    'email' => $row->email,
                    'name' => $row->name,
                    'first_name' => $names[0] ?? "",
                    'last_name' => $names[1] ?? "",
                    'created_by' => \Auth::user()->id,
                ];
                $this->create($data);
            }
        }
    }

    protected function processUploadedFile($fileName, $filePath)
    {
        $cycle = Cycle::getCurrentCycle();
        if (!$cycle) {
            unlink($filePath);
            return [
                'status' => false,
                'message' => 'Wrong Cycle'
            ];
        }
        $headersToCheck = [
            0 => "SEIS ID",
            1 => "SSID",
            2 => "Last Name",
            3 => "First Name",
            4 => "Middle Name",
            5 => "Preferred Name",
            6 => "DOB",
            7 => "CaseManager",
            8 => "Case Manager Email",
            9 => "Reporting LEA",
            10 => "DSEA",
            11 => "School",
            12 => "Grade",
            13 => "Primany Language",
            14 => "SPED Type",
            15 => "Disability 1",
            16 => "Next Plan Review",
            17 => "Next Reevaluation",
            18 => "Eligibility"
        ];
        $filePath = Storage::path("public/uploads/" . $fileName);
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            // Optionally, skip the header row if your CSV has one
            $headers = fgetcsv($handle);
            //dd($headersToCheck,$headers);
            $good = 0;
            for ($i = 0; $i <= 18; $i++) {
                if (isset($headers[$i]) && isset($headersToCheck[$i])) {
                    if (trim(strtolower($headers[$i])) == trim(strtolower($headersToCheck[$i]))) {
                        $good++;
                    }
                }
            }
            if ($good != 19) {
                unlink($filePath);
                return [
                    'status' => false,
                    'message' => 'We indentify only ' . $good . ' columns out of 18'
                ];
            }
            $invalidUsers = 0;
            $cycle =  Cycle::getCurrentCycle();
            $totalRows = 0;
            $totalDuplicates = 0;
            $totalBlanks = 0;
            $totalMissing = 0;
            $missingInStudAccounts = [];
            while (($row = fgetcsv($handle)) !== FALSE) {
                $isGood = JMHelper::JMisCsvRowNotEmpty($row);
                if (!$isGood) {
                    // empty row skipped
                    continue;
                }
                $user = User::where('email', $row[8])->first();
                if (!$user) {
                    $password = Str::password();
                    $user = User::create([
                        'name' => JMHelper::JMSanitizeField($row[7]),
                        'email' => strtolower(JMHelper::JMSanitizeField($row[8])),
                        'password' => Hash::make($password),
                        'email_verification_token' => Str::random(32),
                        'email_verified' => 0,
                        'role_as' => 4
                    ]);
                    if (strtolower(getenv("APP_ENV")) == "prod") {
                        Mail::to($user->email)->send(new VerificationEmail($user, $password));
                    } else {
                        if ($totalRows % 300 == 0) {
                            //Mail::to($user->email)->send(new VerificationEmail($user, $password));
                        }
                    }
                }
                $studentExistsForThisSpecialist = SpecialistStudent::where('cycle_id',$cycle->id)
                        ->where('specialist_id',$user->id)
                        ->where('student_id',JMHelper::JMSanitizeField($row[1]))
                        ->first();
                if ($studentExistsForThisSpecialist) {
                    $totalDuplicates++;
                    continue; // Student already exists skipped
                }
                $studentInfo = StudentAccounts::where('cycle_id',$cycle->id)
                                        ->where('student_id',JMHelper::JMSanitizeField($row[1]))
                                        ->first();
                if (!$studentInfo) {
                    $totalMissing++;
                    $missingInStudAccounts[$row[1]] = $row[2] . ' ' . $row[3] ;
                    continue; // Student does not exist on students accounts skipped
                }
                $data = [
                    'cycle_id' => $cycle->id,
                    'specialist_id' => $user->id,
                    'email' => strtolower(JMHelper::JMSanitizeField($row[8])),
                    'name' => JMHelper::JMSanitizeField($row[7]),
                    'students_list',
                    'student_id' => JMHelper::JMSanitizeField($row[1]),
                    'first_name' => JMHelper::JMSanitizeField($row[3]),
                    'last_name' => JMHelper::JMSanitizeField($row[2]),
                    'created_by' => \Auth::user()->id,
                ];
                SpecialistStudent::create($data);
                $totalRows++;
            }
            fclose($handle);
            unlink($filePath);
            $message  = 'Total Records created ' . $totalRows . "\n";
            $message .= 'Total Duplicates ' . $totalDuplicates . "\n";
            $message .= 'Total Non Existing in Student Accounts ' . $totalMissing . "\n";
            foreach ($missingInStudAccounts as $k => $val) {
                $message .= " Student " . $k . ' -> ' . $val . "\n";
            }
            //dd($message);
            return [
                'status' => true,
                'message' => $message
            ];
        }
    }
}
