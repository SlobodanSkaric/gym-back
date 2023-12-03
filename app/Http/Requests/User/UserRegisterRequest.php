<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            "name"                  => "required|string|min:2|max:255",
            "lastname"              => "required|string|min:2|max:255",
            "email"                 => "required|email|unique:users,email",
            "password"              => "required|string|min:8|confirmed",
            "password_confirmation" => "required|string|min:8"
        ];
    }

    public function messages()
    {
        return [
            "name.min"    => "The name cannot be less then 2 characters",
            "name.max"    => "The name cannot be longer then 255 characters",
            "email.email" => "Email is not valid format"
            /*TODO implement password validation*/
        ];
    }
}
