<?php

namespace App\Rules;

use App\Models\ConsolidateMapping;
use App\Models\Cycle;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueColumnNameInConsolidated implements ValidationRule
{
    public $id = null;
    public function __construct($id = null) {
        $this->id = $id;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->id) {
            $cycle = Cycle::getCurrentCycle();
            $checkIfColumnNameExists =  ConsolidateMapping::where('cycle_id', $cycle->id)
                ->where('column_name',strtolower($value))
                ->first();
            if ($checkIfColumnNameExists) {
                $fail('The :attribute already exists ');
            }
        }
    }
}
