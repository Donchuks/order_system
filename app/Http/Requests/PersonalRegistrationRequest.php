<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalRegistrationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|regex:/^[a-zA-ZÑñ\s]+$/',
            'last_name' => 'required|string|regex:/^[a-zA-ZÑñ\s]+$/',
            'business_email' => 'required|email|unique:tenant_users,email',
            'phone_number' => 'required|numeric|unique:tenant_users,phone_number',
            'plan' => 'required|string',
        ];
    }
}
