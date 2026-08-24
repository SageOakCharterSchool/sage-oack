<?php

namespace App\Rules;

use App\Models\Formula;
use Illuminate\Contracts\Validation\Rule;

class ValidateUniqueFormulaNameByCycle implements Rule
{
    public $cycleId,$formulaId,$formulaName;
    public function __construct($formulaName,$cycleId,$formulaId=null)
    {
        //dd($formulaName,$cycleId,$formulaId);
        $this->cycleId = $cycleId;
        $this->formulaId = $formulaId;
        $this->formulaName = $formulaName;
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
        if ($this->formulaId && $this->formulaId > 0) {  // validate update mode

            $formulaNameExist = Formula::where("cycle_id",$this->cycleId)
                ->where('formula_name',$this->formulaName)
                ->where('id','!=',$this->formulaId)
                ->first();
        } else { // validate insert mode
            //dd($this->cycleId,$this->formulaName,$this->formulaId);
            $formulaNameExist = Formula::where("cycle_id",$this->cycleId)
                            ->where('formula_name',$this->formulaName)
                            ->first();

        }
        if ($formulaNameExist) {
            return false;
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
        return 'The Formula Name is already taken.';
    }
}
