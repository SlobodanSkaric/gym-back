<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

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

        $addPayment = Payment::create([
            "count"     => $paymentDataCount["count"],
            "user_id"   => $id
        ]);

        return response([
            "payment_data" => [
                "user_id" => $addPayment["user_id"],
                "count"   => $addPayment["count"]
            ]
        ]);

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
