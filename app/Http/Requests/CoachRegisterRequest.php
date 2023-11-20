<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoachRegisterRequest extends FormRequest
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
            "email"                 => "required|email|unique:coach,email",
            "password"              => "required|string|min:8|max:255",
            "password_confirmation" => "required|string|min:8|max:255"
        ];
    }

    public function message(){
        return [
            "name.min"      => "The name cannot be less then 2 characters",
            "name.max"      => "The name cannot be longer then 255 characters",
            "email.email"   => "Email is not valid format",
            "password.min"  => "The name cannot be less then 8 characters"
        ];
    }
}
