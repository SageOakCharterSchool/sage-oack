<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\MathList;
use App\Models\Cycle;
use App\Models\StudentList;
use Livewire\Component;

class CheckStudentFeed extends Component
{
    public $keyWord, $mathList = [], $studentList = [];
    private $users;
    public function render()
    {
        $cycle =  Cycle::getCurrentCycle();
        //dd($this->keyWord);
        $this->mathList = MathList::where("student_id",$this->keyWord)
                        ->where('cycle_id',$cycle->id)
                        ->get();
        $this->studentList = StudentList::where("student_id",$this->keyWord)
                        ->where('cycle_id',$cycle->id)
                        ->get();
        //dd($mathList,$studentList)
        return view('livewire.admin.user.check-student-feed',[
            'mathList' => $this->mathList,
            'studentList' => $this->studentList,
        ]);
    }
}
