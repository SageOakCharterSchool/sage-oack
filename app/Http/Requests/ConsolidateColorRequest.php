<?php

namespace App\Http\Requests;

use App\Rules\UniqueTableColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ConsolidateColorRequest extends FormRequest
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
        $request = Request::capture();
        //dd($request->all());

        $validation = [
			'cycle_id' => 'required',
			'created_by' => 'required',
            'column_name' => ['required','string', 'max:155'],
            //'column_name' => ['required','string', 'max:155',new UniqueTableColor($request->table_name)],
			'value' => 'string',
			'color' => 'string',
			'background_color' => 'string',
        ];

        // if ($request->form_status == "E") {
        //     $validation['table_name'] = ['required','string', 'max:155'];
        // }

        return $validation;
    }
}
