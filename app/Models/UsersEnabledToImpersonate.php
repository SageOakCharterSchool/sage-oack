<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UsersEnabledToImpersonate extends Model
{
    use HasFactory;

    protected $table = "users_enabled_to_impersonates";

    protected $fillable = [
        'user_id',
        'status',
        'created_by',
    ];

    protected function createImpersonatePermission($userId) {
        $hasPermission = UsersEnabledToImpersonate::where('user_id',$userId)
                ->first();
        if (!$hasPermission) {
                $data = [
                    'user_id' => $userId,
                    'status' => 1,
                    'created_by' => \Auth::user()->id,
                ];
                UsersEnabledToImpersonate::create($data);
        } else {
            if ($hasPermission->status == 0) {
                $hasPermission->status = 1;
                $hasPermission->save();
            }
        }
    }

    protected function removeImpersonatePermission($userId) {

        UsersEnabledToImpersonate::where('user_id',$userId)
            ->update(['status' => 0]);

    }

    static function checkIfUserHasImpersonatePermissions($userId) {
        return UsersEnabledToImpersonate::where('user_id',$userId)
                    ->where('status',1)
                    ->first();
    }

    protected function getListOfImpersonatedUsers() {
        $rows = UsersEnabledToImpersonate::join('users','users.id','=','users_enabled_to_impersonates.user_id')
                    ->select('users_enabled_to_impersonates.*','users.name')
                    ->get();
                    return $rows;
    }

}
