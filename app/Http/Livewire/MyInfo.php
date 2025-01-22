<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyInfo extends Component
{
    public $name;
    public $email;

    protected function rules() {
        return [
            'name' => 'required'
        ];
    }

    public function updated($fields) {

        $this->validateOnly($fields);
    }

    public function saveMyInfo() {
        $validated = $this->validate();
        $user = Auth::user();
        $user->name = $this->name;
        $user->save();
    }

    public function render()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        return view('livewire.my-info');
    }
}
