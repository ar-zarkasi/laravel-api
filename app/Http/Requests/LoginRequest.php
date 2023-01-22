<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailValidation;

class LoginRequest extends FormRequest
{
    // use FailValidation;

    private $regex_email = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$';
    private $regex_phone = '^(?:(?:\+|00) [1-9] [0-9]{0,2}|0)[1-9][0-9]{9}$';
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
            'phone' => "required_without_all:username,email|regex:$this->regex_phone",
            'password' => 'required',
            'deviceos' => 'nullable',
            'devicedata' => 'nullable',
        ];
    }
}
