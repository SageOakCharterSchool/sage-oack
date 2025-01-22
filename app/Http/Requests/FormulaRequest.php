<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class FormulaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //dd(Request['formula_id']);
        $request = Request::capture();
        //dd($request->formula_id);
        return [
			'cycle_id' => 'required',
			'formula_name' => 'string|unique:formulas,formula_name,'.$request->formula_id,
			'formula_description' => 'string',
			'created_by' => 'required',
			'formula' => 'required',
        ];
    }
}
