<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoachRegisterRequest;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
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
    public function store(CoachRegisterRequest $request):\Illuminate\Http\Response
    {
        $coachRegistrationData = $request->validated();

        $createdCoach = Coach::create([
           "name"       => $coachRegistrationData["name"],
           "lastname"   => $coachRegistrationData["lastname"],
           "email"      => $coachRegistrationData["email"],
            "password"  => bcrypt($coachRegistrationData["password"])
        ]);

        $coachToken = $createdCoach->createToken("main_cocah_token")->plainTextToken;

        return response([
           "coach" => [
               "name"       => $createdCoach["name"],
               "lastname"   => $createdCoach["lastname"],
               "email"      => $createdCoach["email"]
           ],
           "token" =>  $coachToken
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
