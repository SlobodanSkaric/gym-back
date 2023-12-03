<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        $users = User::all();

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegisterRequest $request): \Illuminate\Http\Response
    {
        $userRegistrationData = $request->validated();

        $createdUser = User::create([
            "name"     => $userRegistrationData["name"],
            "lastname" => $userRegistrationData["lastname"],
            "email"    => $userRegistrationData["email"],
            "password" => bcrypt($userRegistrationData["password"])
        ]);

        $userToken = $createdUser->createToken("main_user_toke")->plainTextToken;

        return response([
           "user"   => [
               "usr_id"     => $createdUser["id"] ,
               "name"       => $createdUser["name"],
               "lastname"   => $createdUser["lastname"],
               "email"      => $createdUser["email"]
           ],
            "token" => $userToken
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {

        $privilegsRequest = $request->user()->tokens->first();
        $abilities = $privilegsRequest->abilities;
        $roleAbilities = $abilities[0];
        $rolePos = strpos($roleAbilities, ":") +1;
        $role = substr($roleAbilities, $rolePos);

        if($role != "user"){
            return \response()->json(["message" => "Not valid role"]);
        }


            $user = User::with("coach")->find($id);

            if(!$user){
                return \response()->json(["message" => "No match user"]);
            }

            return \response()->json(["user" => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $request->validated();

        try{
            User::where("id", $id)->update(["status" => $request["status"]]);

            return \response()->json(["message" => "Update success"]);
        }catch (\Exception $e){
            return \response()->json(["message" => "Update is not success"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
