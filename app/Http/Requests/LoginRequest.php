<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailValidation;

class LoginRequest extends FormRequest
{
    use FailValidation;
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
            'username' => 'required_without_all:email,phone',
            'email' => 'required_without_all:username,phone|email',
            'phone' => 'required_without_all:username,email|regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/',
            'password' => 'required',
            'deviceos' => 'nullable',
            'devicedata' => 'nullable',
        ];
    }
}
