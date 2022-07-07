<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirmation' => ['required'],
        ];
    }

    public function messages() {
        return [
            'first_name.required' => 'Please enter first name',
            'last_name.required' => 'Please enter first name',
            'email.required' => 'Please enter first name',
            'password.required' => 'Please enter first name',
        ];
    }
}
