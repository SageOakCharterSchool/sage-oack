<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TeacherStudent;
use App\Models\Cycle;

class validTeacher implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $cycle =  Cycle::getCurrentCycle();
        //dd($cycle->id,$value);
        $teacherAccount = TeacherStudent::where('teacher_id', $value)
            ->where('cycle_id', $cycle->id)
            ->first();
        if ($teacherAccount) {
            return true;  // there is no teacher
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'That Teacher account does not exists.';
    }
}
