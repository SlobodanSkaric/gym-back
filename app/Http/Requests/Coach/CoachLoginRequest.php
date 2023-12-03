<?php

namespace App\Http\Requests\Coach;

use App\Contracts\LoginRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

class CoachLoginRequest extends FormRequest implements LoginRequestInterface
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
    public function rules():array
    {
        return [
            "email"     => "required|email",
            "password"  => "required|string",
            "remember"  => "boolean"
        ];
    }
}
