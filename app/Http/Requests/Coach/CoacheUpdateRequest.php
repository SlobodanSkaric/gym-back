<?php

namespace App\Http\Requests\Coach;

use Illuminate\Foundation\Http\FormRequest;

class CoacheUpdateRequest extends FormRequest
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
            "email"                 => "email|unique:coach,email",
            "password"              => "string|min:8|max:255",
            "password_confirmation" => "string|min:8|max:255"
        ];
    }
}
