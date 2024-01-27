<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Resources\User\UserGetResource;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class PaymentController extends Controller
{
    public function index()
    {
        var_dump("this is index");
    }

    public function add(PaymentRequest $request, $id)
    {
        if(!is_numeric($id) || intval($id) != $id){
            return response()->json(["message", "Id is not valid format"]);
        }

        if(\Auth::user()->id != $id){
            return response()->json(["message" => "You are not athorizate for this action"]);
        }

        $paymentDataCount = $request->validated();

        if(!$paymentDataCount){
            return response()->json(["message", "Not valid data"]);
        }

        $role = $this->separatedRole(\request());

        if($role != "user"){
            return response()->json(["message", "You are not authorizet"]);
        }

        $checkStatus = User::where("status", 1)->exists();

        if($checkStatus){
            return response()->json(["message" => "Your status is active"]);
        }

        try{
            DB::transaction(function() use ($id, $paymentDataCount) {
                Payment::create([
                    "count"     => $paymentDataCount["count"],
                    "user_id"   => $id
                ]);

                User::where("id", $id)->update(["status" => 1]);

            });
        }catch (Exception $e){
            return response()->json(["message" => "Transactio is not complited"]);
        }

        $user = User::with("coach")->find($id);

        return new UserGetResource($user);
    }

    private function separatedRole($req){
        $getUserToken = $req->user()->tokens->first();
        $abilities = $getUserToken->abilities;
        $roleAbilities = $abilities[0];
        $roleSplit = strpos($roleAbilities, ":") +1;
        $role = substr($roleAbilities, $roleSplit);

        return $role;
    }
}
