<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdministratorRegistrationRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\Administrator;
use Illuminate\Http\Request;
class AdminstratorController extends Controller
{//TODO implement check auth user
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        if(!\Auth::user()){
            return response()->json(["message" => "User is not auth"]);
        }

        $role = $this->separatedRole(\request());

        if($role != "admin"){
            return response()->json(["messagee" => "Role is not valid. You are not Admin"]);
        }

        $admin = Administrator::where("status", "=", 1)->get();

        return response()->json(["admin" => $admin]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdministratorRegistrationRequest $request)
    {
        $administratorRegistrationData = $request->validated();

        $creatAdministrator = Administrator::create([
            "name"      => $administratorRegistrationData["name"],
            "lastname"  => $administratorRegistrationData["lastname"],
            "email"     => $administratorRegistrationData["email"],
            "password"  => bcrypt($administratorRegistrationData["password"])
        ]);

        $administratorToken = $creatAdministrator->createToken("main_administrator_token")->plainTextToken;

        return response([
            "administrator" => [
                "name"      => $creatAdministrator["name"],
                "lastname"  => $creatAdministrator["lastname"],
                "email"     => $creatAdministrator["email"]
            ],
            "token" => $administratorToken
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
            return response()->json(["message" => "Is no action supported"]);
        }

        $role = $this->separatedRole($request);

        if($role != "admin"){
            return response()->json(["message" => "Bed Role"]);
        }

        try {
            $admin = Administrator::findOrFail($id);

            if($admin->status == 0){
                return response()->json(["message" => "Admin is not acitve"]);
            }

            return response()->json(["admin" => $admin]);
        }catch (\Exception $e){
            return response()->json(["message" => "Admin not found"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdminUpdateRequest $request, $id)
    {
        if(\Auth::user()->id != $id){
            return response()->json(["message" => "Is no Action supported"]);
        }

        $role = $this->separatedRole($request);
        $request->validated();

        if($role != "admin"){
            return response()->json(["message" => "Role is not valid. You are not Admin"]);
        }

        try {
            Administrator::where("id", $id)->update(["status" => $request["status"]]);

            return response()->json(["message" => "Update success"]);
        }catch (\Exception $e){
            return response()->json(["message" => "Update is not succes"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(\Auth::user()->id != $id){
            return response()->json(["message" => "Is no Action supported"]);
        }

        $role = $this->separatedRole(\request());

        if($role != "admin"){
            return response()->json(["message" => "Role is not valid. You are not Admin"]);
        }

        try {
            Administrator::where("id", $id)->update(["status" => 0]);

            return response()->json(["message" => "Delete success"]);
        }catch (\Exception $e){
            return response()->json(["message" => "Delete is not succes"]);
        }


    }

    private function separatedRole($req){
        $getAminToken = $req->user()->tokens->first();
        $abilities = $getAminToken->abilities;
        $roleAbilities = strpos($abilities[0], ":") + 1;
        $role = substr($abilities[0], $roleAbilities);

        return $role;
    }
}
