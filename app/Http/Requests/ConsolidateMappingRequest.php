<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueColumnNameInConsolidated;

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
        $consolidateMappingId = $this->route('consolidate_mapping') ? $this->route('consolidate_mapping')->id : null;
        //dd($consolidateMappingId);
        //dd(FormRequest::all());
        return [
			'cycle_id' => 'required',
			'screen_sort' => 'numeric',
			'column_name' => ['string' , new UniqueColumnNameInConsolidated($consolidateMappingId)],
			'column_description' => 'string',
			'formula_id' => 'required_without:field_source',
			'field_source' => 'required_without:formula_id',
			'created_by' => 'required',
        ];
    }
}
