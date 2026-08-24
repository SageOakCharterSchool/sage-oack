<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    //use HasApiTokens, HasFactory, Notifiable;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login',
        'status',
        'email_verification_token',
        'email_verified',
        'role_as',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getTableName()
    {
        return (new self())->getTable();
    }


    public function isAdmin()
    {
        if ($this->role_as == 1 && $this->status == 1) {
            return true;
        }
        return false;
    }

    public function isManager()
    {
        if ($this->role_as == 3 && $this->status == 1) {
            return true;
        }
        return false;
    }

    public function isTeacher()
    {
        if ($this->role_as == 2 && $this->status == 1) {
            return true;
        }
        return false;
    }

    public function isSpecialist()
    {
        if ($this->role_as == 4 && $this->status == 1) {
            return true;
        }
        return false;
    }


    public function isUser()
    {
        if ($this->role_as == 0 && $this->status == 1) {
            return true;
        }
        return false;
    }

    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cycle(): HasOne
    {
        return $this->hasOne(User::class, 'created_by', 'id');
    }

    protected function verifyUserIsSpecialist($email)
    {
        $user = User::where('email', $email)
            ->where('role_as', 4) // specialist
            ->first();
        if ($user) {
            return true;
        }
        return false;
    }

    public function getTeacherId()
    {
        $getTeacherId =  TeacherStudent::getIdFromEmail();
        if (!$getTeacherId) {
            return -2; // to make sure no records will be retrieved
        }
        return $getTeacherId;
    }

    protected function checkSSO($googleUser)
    {
        $allowedDomains = [
            'sageoak.education',
        ];
        $domain = substr(strrchr($googleUser->email, "@"), 1);
        if (!in_array($domain, $allowedDomains)) {
            return null;
        }
        $user = User::where("email", $googleUser->email)->first();
        if ($user) {
            $user->google_id = $googleUser->id;
            $user->save();
        } else {
            $data = [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'last_login' => Carbon::now(),
                'status' => 1,
                'email_verification_token' => '',
                'email_verified' => 1,
                'role_as' => 2,
                'google_id' => $googleUser->id,
                'email_verified_at' => Carbon::now(),
            ];
            $user = User::create($data);
            // Notify all admins that a new account has been created by a teacher
        }


        return $user;
    }
}
