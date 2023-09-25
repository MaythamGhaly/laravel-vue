<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
Use \Carbon\Carbon;

class authController extends Controller
{
    function login(Request $req)
    {
        $user = User::where(['email' => $req->email])->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return "Username or password is not matched";
        }
        if (!$user->approve) {
            return response('User not approved', 401);
        }
        $token = $user->createToken('apiToken')->plainTextToken;
        $user->last_login = now()->toDateString();
        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($res, 201);
    }

    function register(Request $req)
    {
        $req->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'sex' => 'required|string',
            'blood_type' => 'required|string',
        ]);
        $exist = User::where(['email' => $req->email])->first();
        if ($exist) {
            return response()->json(['message' => 'User already exist'], 409);
        }
        $user = new User;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->role = $req->role;
        $user->sex = $req->sex;
        $user->blood_type = $req->blood_type;
        $user->password = Hash::make($req->password);
        $user->save();

        $token = $user->createToken('apiToken')->plainTextToken;
        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($res, 201);
    }
}
