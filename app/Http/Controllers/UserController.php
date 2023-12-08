<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{//TODO implement check auth user
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        //check role
        $users = User::where("status", "=", 1)->get();

        if(count($users) == 0){
            return response()->json(["message" => "No users in databse"]);
        }
        return response()->json(["users" => $users]);
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
        try{
            $user = User::findOrFail($id);
            $user->coach;

            $role = $this->separatedRole($request);

            if($role != "user"){
                return \response()->json(["message" => "Role is not corect"]);
            }

            return \response()->json($user);
        }catch (ModelNotFoundException $e){
            return  \response()->json(["message" => "User not found"]);
        }


           /* $user = User::with("coach")->find($id);

            if(!$user){
                return \response()->json(["message" => "No match user"]);
            }

            return \response()->json(["user" => $user]);*/
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
        $role = $this->separatedRole($request);

        if($role != "user"){
            return \response()->json(["message" => "Role is not corect"]);
        }

        try {
             User::where("id", $id)->update(["status" => $request["status"]]);

            return \response()->json(["message" => "Update success"]);
        }catch (\Exception $e){
            return \response()->json(["message" => "Update not success"]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDeleteRequest $request, $id)
    {
        $request->validated();

        try {
            User::where("id", $id)->update(["status" => 0]);

            return response()->json(["message" => "Delete is success"]);
        }catch (\Exception $e){
            return response()->json(["message" => "Delete is not success"]);
        }

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
