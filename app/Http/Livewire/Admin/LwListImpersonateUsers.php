<?php

namespace App\Http\Livewire\Admin;

use App\Models\UsersEnabledToImpersonate;
use Livewire\Component;

class LwListImpersonateUsers extends Component
{
    public $impersonatedUsersList;
    public function render()
    {
        $this->impersonatedUsersList = UsersEnabledToImpersonate::getListOfImpersonatedUsers();

        return view('livewire.admin.lw-list-impersonate-users');
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
