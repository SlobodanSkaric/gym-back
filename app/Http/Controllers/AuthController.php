<?php

namespace App\Http\Controllers;

use App\Contracts\LoginRequestInterface;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request, $role):\Illuminate\Http\Response{
        $roleValidation = $request->validated();

        $remember = $roleValidation["remember"] ?? false;
        unset($roleValidation["remember"]);

        if($role == "user"){
            return $this->userLogin($roleValidation, $remember,$role);
        }
        if($role == "admin"){
            return $this->adminLogin($roleValidation, $remember,$role);
        }

        if($role == "coach"){
            return $this->coachLogin($roleValidation, $remember,$role);
        }

        return response([
           "message" => "Bad Role"
        ]);
    }

    private function userLogin($parameters, $remember, $role)
    {
        if(!\Illuminate\Support\Facades\Auth::attempt($parameters, $remember)){
            return response([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $user = Auth::user();

        $token = $user->createToken("user_login_token")->plainTextToken;

        return response([
            "user" =>[
                "email" => $user->email,
                "name"  => $user->name,
                "role"  => $role
            ],
            "token" => $token
        ]);
    }

    private function adminLogin($parameters, $remember, $role)
    {
        if(!\Illuminate\Support\Facades\Auth::guard("admin")->attempt($parameters, $remember)){
            return response([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $user = Auth::guard("admin")->user();

        $token = $user->createToken("admin_login_token")->plainTextToken;

        return response([
            "admin" =>[
                "email" => $user->email,
                "name"  => $user->name,
                "role"  => $role
            ],
            "token" => $token
        ]);
    }

    private function coachLogin($parameters, $remember, $role)
    {
        if(!\Illuminate\Support\Facades\Auth::guard("coach")->attempt($parameters, $remember)){
            return response([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $user = Auth::guard("coach")->user();

        $token = $user->createToken("coach_login_token")->plainTextToken;

        return response([
            "admin" =>[
                "email" => $user->email,
                "name"  => $user->name,
                "role"  => $role
            ],
            "token" => $token
        ]);
    }
}
