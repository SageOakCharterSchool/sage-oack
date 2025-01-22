<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeacherStudent;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\AdminNofiticationNewRegistrationEmail;


class RegisterNewTeachers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newTeachers = TeacherStudent::where('cycle_id',6)
            //->take(3)
            ->get();
        //dd($newTeachers);
        $count = $registered =  0;
        foreach ($newTeachers as $row) {
            $count++;
            $teacherExists = User::where("email", strtolower($row->email))->first();
            if (!$teacherExists) {
                $registered++;

                $password = Str::random(10);
                $data = [
                    'name' => $row->first_name . ' ' . $row->last_name,
                    'email' => $row->email,
                    'password' => bcrypt($password),
                    'status' => 1,
                    'email_verification_token' => Str::random(32),
                    'email_verified' => 0,
                    'role_as' => 2, // teacher
                ];
                $user = User::create($data);
                $reveiverEmailAddress = $user->email;
                Mail::to($user->email)->send(new VerificationEmail($user, $password));
                $allAdmins = User::where("role_as", 1)->get(); // all admins
                foreach ($allAdmins as $admin) {
                    if (getenv("APP_ENV") == "PROD" || getenv("APP_ENV") == "TEST") {
                        //Mail::to($admin->email)->send(new AdminNofiticationNewRegistrationEmail($user,$password));
                    } else {
                        //Mail::to('jmancera@gmail.com')->send(new AdminNofiticationNewRegistrationEmail($user,$password));
                    }
                }
            }
        }
        echo "Registered = " . $registered . " total = " . $total;
    }
}
