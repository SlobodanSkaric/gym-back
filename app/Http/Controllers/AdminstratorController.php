<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdministratorRegistrationRequest;
use App\Models\Administrator;
use Illuminate\Http\Request;

class AdminstratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Administrator::all();
        return $admin;
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
