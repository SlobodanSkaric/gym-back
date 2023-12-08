<?php

namespace App\Http\Controllers;

use App\Http\Requests\Coach\CoacheUpdateRequest;
use App\Http\Requests\Coach\CoachRegisterRequest;
use App\Models\Coach;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CoachController extends Controller
{//TODO implement check auth user
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if(!\Auth::user()){
            return \response()->json(["message" => "Coach is not auth"]);
        }
        $coach = Coach::with("users")->get();

        return response()->json(["coach" => $coach]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        if(\Auth::user()->id != $id){
            return \response()->json(["message" => "Is not action supported"]);
        }
        try {
            $coach = Coach::where("id", $id)->with("users")->get();

            $role = $this->separatedRole($request);

            if($role != "coach"){
                return response()->json(["message" => "Role is not valid"]);
            }

            return response()->json(["coach" => $coach]);

        }catch (ModelNotFoundException $e){
            return response()->json(["message" => "Coach is not found"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CoacheUpdateRequest $request, $id)
    {
        if(\Auth::user()->id != $id){
            return \response()->json(["message" => "Is not action supported"]);
        }
        $valiidate = $request->validated();
        $role = $this->separatedRole($request);

        if($role != "coach"){
            return response()->json(["message" => "Bed role"]);
        }

        $updateCoach = Coach::where("id", $id)->update($valiidate);

        if(!$updateCoach){
            return response()->json(["message" => "Update is not success"]);
        }

        return response()->json(["message" => "Update is succes"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*TODO implent status in db and then implement delte coach*/
    }

    private function separatedRole($req){
        $getUserToken = $req->user()->tokens->first();
        $abilities = $getUserToken->abilities;
        $roleAbilities = $abilities[0];
        $roleSplit = strpos($roleAbilities, ":") + 1;
        $role = substr($roleAbilities, $roleSplit);

        return $role;
    }
}
