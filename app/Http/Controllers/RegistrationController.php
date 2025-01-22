<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\AdminNofiticationNewRegistrationEmail;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Rules\IsAllowedDomain;


class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index() {
        return view('auth/register');
    }

    public function registerme(Request $request) {
        $data = [];
        $isValid = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new IsAllowedDomain],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($isValid->fails()) {
            return redirect('registration')
                    ->withErrors($isValid)
                    ->withInput();
        }
        $password = $request->password;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_token' => Str::random(32),
            'email_verified' => 0,
            'role_as' => 2
        ]);

        Mail::to($user->email)->send(new VerificationEmail($user,$password));

        $allAdmins = User::where("role_as",1)->get(); // all admins
        foreach ($allAdmins as $admin) {
            if (getenv("APP_ENV") == "PROD" || getenv("APP_ENV") == "TEST") {
                //Mail::to($admin->email)->send(new AdminNofiticationNewRegistrationEmail($user,$password));
            } else {
                Mail::to('jmancera@gmail.com')->send(new AdminNofiticationNewRegistrationEmail($user,$password));
            }
            //Mail::to($admin->email)->send(new AdminNofiticationNewRegistrationEmail($user,$password));
        }

        session()->flash('message', 'Please check your email to activate your account');
        Auth::logout();
        return redirect("/login");

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $password = $data['password'];
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verification_token' => Str::random(32),
            'email_verified' => 0,
            'role_as' => 2,
            'status' => 0,
        ]);

        Mail::to($user->email)->send(new VerificationEmail($user,$password));

        $allAdmins = User::where("role_as",1)->get(); // all admins
        foreach ($allAdmins as $admin) {
            //Mail::to($admin->email)->send(new AdminNofiticationNewRegistrationEmail($user,$password));
        }

        session()->flash('message', 'Please check your email to activate your account');
        Auth::logout();
        return redirect("/login");

    }
}
