<?php

namespace App\Http\Controllers;

use App\Contracts\LoginRequestInterface;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Resources\User\UserGetResource;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use function Sodium\add;

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

        $paymentDataTimeModel = Payment::where("user_id", $user->id)->orderBy("payment_at", "desc")->first();
        if($paymentDataTimeModel){
            $paymentDataTime = $paymentDataTimeModel->payment_at;

            $carbonObj = Carbon::parse($paymentDataTime);
            $carbonAddDay = $carbonObj->addDays(1);
            $carbonNow = Carbon::now();

            if($carbonNow->gt($carbonAddDay)){
                User::where("id", Auth::user()->id)->update(["status" => 0]);
                $user = Auth::user();
            }
        }


        return response()->json(["token" => $token, new UserGetResource($user) ,"csrf" =>csrf_token()]);
    }

    private function adminLogin($parameters, $remember, $role):JsonResponse
    {
        if(!\Illuminate\Support\Facades\Auth::guard("admin")->attempt($parameters, $remember)){
            return response()->json([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $admin = Auth::guard("admin")->user();
        $admin->tokens()->delete();
        $token = $admin->createToken("admin_login_token",["role:admin"])->plainTextToken;

        return response()->json(["token" => $token, "csrf" => csrf_token()]);
    }

    private function coachLogin($parameters, $remember, $role):JsonResponse
    {
        if(!\Illuminate\Support\Facades\Auth::guard("coach")->attempt($parameters, $remember)){
            return response()->json([
                "error" => "Credencial is not corect"
            ], 422);
        }

        $coach = Auth::guard("coach")->user();
        $coach->tokens()->delete();
        $token = $coach->createToken("coach_login_token", ["role:coach"])->plainTextToken;

        return response()->json(["token" => $token, "csrf" => csrf_token()]);
    }
}
