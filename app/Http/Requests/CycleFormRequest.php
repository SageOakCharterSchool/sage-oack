<?php

namespace App\Http\Requests;

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;

class CycleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        //dd(FormRequest::all());
        return [
            'cycle_name' => ['required', 'string'],
            'date_from' => ['required','date_format:Y-m-d'],
            'date_to' => ['required','date_format:Y-m-d','after:date_from']
        ];
    }
}
