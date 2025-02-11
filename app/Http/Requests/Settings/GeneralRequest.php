<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class GeneralRequest extends FormRequest
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
            'app_name' => 'required',
            'time_format' => 'required',
            'timezone' => 'required',
            'date_format' => 'required',
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'app_name' => __('common.name'),
            'time_format' => __('common.time_format'),
            'timezone' => __('common.timezone'),
            'date_format' => __('common.date_format'),
        ];
    }
}
