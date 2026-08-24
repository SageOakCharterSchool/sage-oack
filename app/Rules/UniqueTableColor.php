<?php

namespace App\Rules;

use App\Models\MasterTableColor;
use Illuminate\Contracts\Validation\Rule;

class UniqueTableColor implements Rule
{
    public $tableName;
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
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
        $tableAlias = MasterTableColor::where('table_name',$value)
                        ->first();
        if ($tableAlias) {
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
        return 'The table name has been taken already';
    }
}
