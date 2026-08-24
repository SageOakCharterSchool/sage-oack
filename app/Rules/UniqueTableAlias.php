<?php

namespace App\Rules;

use App\Models\TableAlias;
use Illuminate\Contracts\Validation\Rule;

class UniqueTableAlias implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
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
        $tableAlias = TableAlias::where('table_alias',$value)
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
        return 'The table alias already exists';
    }
}
