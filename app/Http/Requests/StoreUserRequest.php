<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fullname' => "required | max:160",
            'password' => "required",
            'username' => "nullable|alpha_num",
            'email' => "required_without:phone",
            'phone' => "required_without:email",
            'id_roles' => "required|numeric",
        ];
    }
}
