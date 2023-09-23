<?php

namespace App\Http\Controllers;

use App\Models\UserCertificate;
use Illuminate\Http\Request;

class userController extends Controller
{
    function userCertifications(Request $req)
    {
        $user_certificate = UserCertificate::where(['user_id' => auth()->user()->id, 'certificate_id' => $req->certificate_id])->first();
        if ($user_certificate) {
            return response('User already has this certificate', 409);
        }
        $UserCertificate = new UserCertificate();
        $UserCertificate->user_id = auth()->user()->id;
        $UserCertificate->certificate_id = $req->certificate_id;
        $UserCertificate->level = $req->level;
        $UserCertificate->save();
        return response($UserCertificate, 200);
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
            return response('User does not have this certificate', 404);
        }
        $user_certificate->delete();
        return response('Certificate deleted', 200);
    }

    function editProfile(Request $req){
        $user = auth()->user();
        $user->name = $req->name;
        $user->password = $req->password;
        $user->blood_type = $req->blood_type;
        $user->sex = $req->sex;
        $user->save();
        return response($user, 200);
    }
}
