<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsolidateMappingRequest extends FormRequest
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
        //dd(FormRequest::all());
        return [
			'cycle_id' => 'required',
			'screen_sort' => 'numeric',
			'column_name' => 'string',
			'column_description' => 'string',
			'formula_id' => 'required_without:field_source',
			'field_source' => 'required_without:formula_id',
			'created_by' => 'required',
        ];
    }
}
