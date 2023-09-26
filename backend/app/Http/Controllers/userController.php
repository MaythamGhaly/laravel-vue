<?php

namespace App\Http\Controllers;

use App\Models\UserCertificate;
use Illuminate\Http\Request;

class userController extends Controller
{
    function addCertifications(Request $req)
    {
        $user_certificate = UserCertificate::where(['user_id' => auth()->user()->id, 'certificate_id' => $req->certificate_id])->first();
        if ($user_certificate) {
            return response()->json(['message' => 'User certificate already exists'], 400);
        }
        $UserCertificate = new UserCertificate();
        $UserCertificate->user_id = auth()->user()->id;
        $UserCertificate->certificate_id = $req->certificate_id;
        $UserCertificate->save();
        return response()->json(['message' => 'User certificate added'], 200);
    }

    function getUserCertifications()
    {
        $user_certificates = UserCertificate::where(['user_id' => auth()->user()->id])->get();
        foreach ($user_certificates as $user_certificate) {
            $user_certificate->certificate_name = $user_certificate->certificate->certificate_name;
        }
        return response($user_certificates, 200);
    }

    function deleteUserCertification(Request $req)
    {
        $user_certificate = UserCertificate::where(['user_id' => auth()->user()->id, 'certificate_id' => $req->certificate_id])->first();
        if (!$user_certificate) {
            return response()->json(['message' => 'User certificate not found'], 400);
        }
        $user_certificate->delete();
        return response()->json(['message' => 'User certificate deleted'], 200);
    }

    function editProfile(Request $req){
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->name = $req->name ? $req->name : $user->name;
        $user->password = $req->password ? bcrypt($req->password) : $user->password;
        $user->blood_type = $req->blood_type ? $req->blood_type : $user->blood_type;
        $user->sex = $req->sex ? $req->sex : $user->sex;
        $user->save();
        return response()->json(['message' => 'Profile updated','user' => $user, 200]);
    }
}
