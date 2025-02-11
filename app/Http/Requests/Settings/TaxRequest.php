<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TaxRequest extends FormRequest
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
        return [
            'type' => 'required',
            'name' => 'required'
        ];
    }

    

    public function withValidator(Validator $validator)
    {

        $validator->sometimes('rate', 'required', function ($input) { 
            return $input->type === 'single'; 
        });

        
        $validator->sometimes('lines.*.name', 'required', function ($input) { 
            return $input->type === 'multiple'; 
        });

        $validator->sometimes('lines.*.rate', 'required', function ($input) { 
            return $input->type === 'multiple'; 
        });
        
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('common.name'),
            'type' => __('common.type'),
            'rate' => __('common.tax_rate'),
            'lines.*.name' => __('common.name'),
            'lines.*.rate' => __('common.tax_rate'),
        ];
    }
}
