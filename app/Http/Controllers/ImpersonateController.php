<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersEnabledToImpersonate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function __construct() {}
    public function impersonate(User $user)
    {
        //dd($user->role_as);
        if (Auth::user()->isAdmin() || UsersEnabledToImpersonate::checkIfUserHasImpersonatePermissions(Auth::user()->id)) {
            //dd($user->role_as,$user->status);
            if ($user->role_as != 1 && $user->status == 1) {
                Session::put('impersonateWho', $user->id);
                Session::put('impersonateFrom', Auth::user()->id);
                Auth::login($user);
                return redirect('/home');
            } else {
                return redirect('/admin/user')->with('error', 'Cant Impersonate Unverifyed / Inactive users');
            }
        } else {
            return redirect('/admin/user')->with('error', 'Cant Impersonate Unverifyed / Inactive users');
        }
    }

    public function unimpersonate()
    {

        if (Session::has('impersonateFrom')) {
            $userToGetBack = Session::get('impersonateFrom');
            $user = User::where("id", $userToGetBack)->first();
            if ($user) {
                Session::forget('impersonateWho');
                Session::forget('impersonateFrom');
                Auth::logout();
                Auth::login($user);
                return redirect('/admin/user');
            }
        }
        return redirect('/provider/home');
    }
}
