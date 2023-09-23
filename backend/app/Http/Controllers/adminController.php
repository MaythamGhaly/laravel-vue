<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\User;
use Illuminate\Http\Request;

class adminController extends Controller
{
    function addCertifications (Request $req) {
        $req->validate([
            'certificate_name' => 'required|string'
        ]);
        $exist = Certification::where(['certificate_name' => $req->certificate_name])->first();
        if ($exist) {
            return response('Certificate already exists', 409);
        }
        $certification = new Certification;
        $certification->certificate_name = $req->certificate_name;
        $certification->save();
        return response($certification, 201);
    }

    function getCertifications () {
        $certifications = Certification::all();
        foreach ($certifications as $certification) {
            $certification->user_count = $certification->users->count();
        }
        return response($certifications, 200);
    }

    function deleteCertification (Request $req) {
        $req->validate([
            'certificate_name' => 'required|string'
        ]);
        $certification = Certification::where(['certificate_name' => $req->certificate_name])->first();
        if (!$certification) {
            return response('Certificate not found', 404);
        }
        $certification->delete();
        return response('Certificate deleted', 200);
    }

    function approveUser(Request $req){
        $user = User::where(['email' => $req->email])->first();
        if (!$user) {
            return response('User not found', 404);
        }
        $user->approve = true;
        $user->save();
        return response($user, 200);
    }

    function getUsers () {
        $users = User::all();
        return response($users, 200);
    }
}
