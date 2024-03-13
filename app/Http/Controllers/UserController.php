<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth:api", ["except" => ["login", "register"]]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:User',
            'profileImg' => 'image'
        ]);

        $creds = $request->only(["email", "password", "name"]);
        $user = new User;
        if ($request->exists("profileImg")) {
        }
        $user->name = $creds['name'];
        $user->email = $creds['email'];
        $user->password = Hash::make($creds['password']);
        if ($user->save()) {
            return response()->json(["message" => "User created successfully.", "user" => $user], 200);
        }
        return response()->json(["message" => "An error occured while creating user."], 500);
    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $input = $request->only(["email", "password"]);
        if (!$authorized = Auth::attempt($input)) {
            $code = 401;
            $message = "Failed to authorize";
            return response()->json(compact('message'), $code);
        }

        return $this->respondWithToken($authorized);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(["message" => "User logged out."]);
    }
}