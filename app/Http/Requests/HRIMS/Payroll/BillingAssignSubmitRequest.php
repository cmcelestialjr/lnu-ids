<?php

namespace App\Http\Requests\HRIMS\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class BillingAssignSubmitRequest extends FormRequest
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
            'id' => 'required|numeric',
            'employee' => 'required|numeric',
        ];
    }
}
