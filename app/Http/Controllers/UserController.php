<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
