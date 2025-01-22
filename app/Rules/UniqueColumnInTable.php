<?php

namespace App\Rules;

use App\Models\TablesMapping;
use Illuminate\Contracts\Validation\Rule;

use function PHPUnit\Framework\isNull;

class UniqueColumnInTable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $tableId,$cycleId,$fieldId;
    public function __construct($tableId,$cycleId,$fieldId=null)
    {
        $this->tableId = (int)$tableId;
        $this->cycleId = (int)$cycleId;
        $this->fieldId = (int)$fieldId;
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
        // dd($this->tableId,
        // $this->cycleId,
        // $this->fieldId);
        if (!$this->fieldId) {
            $tableFields = TablesMapping::where("cycle_id",$this->cycleId)
                        ->where('table_id',$this->tableId)
                        ->where('column',trim($value))
                        ->first();
        } else {
            $tableFields = TablesMapping::where("cycle_id",$this->cycleId)
                        ->where('table_id',$this->tableId)
                        ->where('id','!=',$this->fieldId)
                        ->where('column',trim($value))
                        ->first();
        }
        if (!$tableFields) {
            return true;
        }
        return false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Column entered already exists.';
    }
}
