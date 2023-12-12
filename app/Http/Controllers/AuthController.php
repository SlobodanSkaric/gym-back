<?php

namespace App\Http\Controllers;

use App\Contracts\LoginRequestInterface;
use App\Http\Requests\User\UserLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequestInterface $request, $role): JsonResponse
    {
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

        return response()->json([
           "message" => "Bad Role"
        ]);
    }

    private function userLogin($parameters, $remember, $role):JsonResponse
    {
        if(!\Illuminate\Support\Facades\Auth::attempt($parameters, $remember)){
            return response()->json([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken("user_login_token", ["role:user"])->plainTextToken;

        return response()->json(["token" => $token, "csrf" =>csrf_token()]);
    }

    private function adminLogin($parameters, $remember, $role):JsonResponse
    {
        if(!\Illuminate\Support\Facades\Auth::guard("admin")->attempt($parameters, $remember)){
            return response()->json([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $user = Auth::guard("admin")->user();
        $user->tokens()->delete();
        $token = $user->createToken("admin_login_token",["role:admin"])->plainTextToken;

        return response()->json(["token" => $token, "csrf" => csrf_token()]);
    }

    private function coachLogin($parameters, $remember, $role):JsonResponse
    {
        if(!\Illuminate\Support\Facades\Auth::guard("coach")->attempt($parameters, $remember)){
            return response()->json([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $user = Auth::guard("coach")->user();
        $user->tokens()->delete();
        $token = $user->createToken("coach_login_token", ["role:coach"])->plainTextToken;

        return response()->json(["token" => $token, "csrf" => csrf_token()]);
    }
}
