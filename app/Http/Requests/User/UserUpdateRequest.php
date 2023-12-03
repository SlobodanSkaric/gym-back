<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            "name"                  => "string|min:2|max:255",
            "lastname"              => "string|min:2|max:255",
            "email"                 => "email|unique:users,email",
            "status"                => "integer|in:0,1",
            "password"              => "string|min:8|confirmed",
            "password_confirmation" => "string|min:8"
        ];
    }
}
