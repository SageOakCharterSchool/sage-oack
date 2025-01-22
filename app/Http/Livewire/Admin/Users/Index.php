<?php

namespace App\Http\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Helpers\LogActivity;
use App\Mail\ResendEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\AdminNofiticationNewRegistrationEmail;
use App\Models\Cycle;
use App\Models\SpecialistStudent;
use App\Models\UsersEnabledToImpersonate;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $userId, $user_id, $keyWord, $name, $email, $status, $created_by, $user_type, $role_as;
    private $users;
    protected $listeners = ['remove','resendRegistrationEmail'];

    protected function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|min:8',
            'role_as' => 'required',
        ];
    }

    public function render()
    {
        $cycle =  Cycle::getCurrentCycle();
        $keyWord = '%' . $this->keyWord . '%';
        $this->users = User::latest()
            ->orWhere('name', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->orWhere('id', 'LIKE', $keyWord)
            ->paginate(10);
        //$users = User::orderBy('id','DESC')->paginate(10);
        //dd($users);
        return view('livewire.admin.users.index',compact('cycle'));
    }

    public function saveUser()
    {
        $validatedData = $this->validate();
        $validatedData['status'] = 1;
        if (\Auth::user()->role_as != 1) {
            // create account by a teacher and needs approval
            $validatedData['status'] = 0;
        }
        $password = Str::random(10);
        $validatedData['email_verification_token'] = Str::random(32);
        $validatedData['email_verified'] = 0;
        $validatedData['password'] = bcrypt($password);
        $user = User::create($validatedData);
        //$reveiverEmailAddress = "jmancera@gmail.com";
        $reveiverEmailAddress = $user->email;
        Mail::to($user->email)->send(new VerificationEmail($user,$password));
        // Mail::to($reveiverEmailAddress)
        // ->send(new InvitationEmail($user,$password));
        // Notify all admins that a new account has been created by a teacher
        if (\Auth::user()->role_as != 1) {
            $allAdmins = User::where("role_as",1)->get(); // all admins
            foreach ($allAdmins as $admin) {
                if (getenv("APP_ENV") == "PROD" || getenv("APP_ENV") == "TEST") {
                    //Mail::to($admin->email)->send(new AdminNofiticationNewRegistrationEmail($user,$password));
                } else {
                    Mail::to('jmancera@gmail.com')->send(new AdminNofiticationNewRegistrationEmail($user,$password));
                }
            }
        }
        session()->flash('message', 'User added succesfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-add-modal');

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function alertResendEmail($id)
    {
        $this->dispatchBrowserEvent('swal:confirmResendEmail', [
                'type' => 'warning',
                'message' => 'Are you sure?',
                'text' => 'This process will resend registration email and reset password!',
                'id' => $id
            ]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function alertConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
                'type' => 'warning',
                'message' => 'Are you sure?',
                'text' => 'If deleted, you will not be able to recover this user data!',
                'id' => $id
            ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove($id)
    {
        LogActivity::addToLog('Delete User  -> ' . $id);
        User::where('id',$id)->delete();
        /* Write Delete Logic */
        $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'User Delete Successfully!',
                'text' => 'It will not list on users table soon.'
            ]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resendRegistrationEmail($id)
    {

        $user = User::where('id',$id)->first();
        if (!$user) {
            return;
        }
        LogActivity::addToLog('Resend registration email  -> ' . $id);
        $password = Str::random(10);
        $validatedData['role_as'] = 2;
        $validatedData['email_verified'] = 1;
        $validatedData['status'] = 1;
        $validatedData['password'] = bcrypt($password);
        User::where('id',$id)->update($validatedData);
        $reveiverEmailAddress = $user->email;
        Mail::to($user->email)->send(new ResendEmail($user,$password));
        // Mail::to($reveiverEmailAddress)
        // ->send(new InvitationEmail($user,$password));

        session()->flash('message', 'Email resend succesfully');

        /* Write Delete Logic */
        $this->dispatchBrowserEvent('swal:resend-email-modal', [
                'type' => 'success',
                'message' => 'Email resend Successfully!',
                'text' => 'User should receive a new registration email.'
            ]);
    }
    public function closeModal()
    {
        $this->resetInput();
    }
    public function cancel()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->name = null;
        $this->email = null;
        $this->status = null;
        $this->user_type = null;
    }

    public function enableImpersonation($userId) {
        $this->dispatchBrowserEvent('swal:enable-impersonation', [
            'type' => 'success',
            'message' => 'Impersonation enabled Successfully!',
            'text' => ''
        ]);
        UsersEnabledToImpersonate::createImpersonatePermission($userId);
    }

    public function disableImpersonation($userId) {
        UsersEnabledToImpersonate::removeImpersonatePermission($userId);
        $this->dispatchBrowserEvent('swal:disable-impersonation', [
            'type' => 'success',
            'message' => 'Impersonation disabled Successfully!',
            'text' => ''
        ]);

    }
}
