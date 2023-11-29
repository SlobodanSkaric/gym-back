<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
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
        //dd($role);
        //$privilegs = in_array("role:admin", $abilities);

        try {
            $user = User::findOrFail($id);

            if($role == "admin"){
                    return response()->json($user);
            }

            if($role == "coach"){
                try{
                    $allCoacheUser = Coach::where("user_id", $id)->get();

                    return \response()->json($allCoacheUser);
                }catch (ModelNotFoundException $e){
                    return \response()->json(["message" => "No related user"]);
                }


            }
            /*TODO implement mechanisam coach get user*/
        }catch (ModelNotFoundException  $e){
            return response()->json(["message" => "User not existe"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
