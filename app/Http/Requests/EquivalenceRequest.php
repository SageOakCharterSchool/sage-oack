<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquivalenceRequest extends FormRequest
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
        //dd($this->id, $this->equivalence,$request->equivalence);
        return [
			'equivalence' => [
                'required',
                'string',
                Rule::unique('equivalences')->ignore($this->id,'id'),
            ],
			'value' => 'string',
			'color' => 'string',
        ];
    }
}
