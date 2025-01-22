<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Cycle;
use App\Models\StudentAccounts;

class validStudentEmailUnique implements Rule
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
        $studAccount = StudentAccounts::where('column_e',$value)
                            ->where('cycle_id',$cycle->id)
                            ->first();
        if ($studAccount) {
            return false;  // there is an account with that email
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'There is an account associated to that email already';
    }
}
