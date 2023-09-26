<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\User;
use App\Models\UserCertificate;
use Illuminate\Http\Request;

class adminController extends Controller
{
    function addCertifications (Request $req) {
        $req->validate([
            'certificate_name' => 'required|string'
        ]);
        $exist = Certification::where(['certificate_name' => $req->certificate_name])->first();
        if ($exist) {
            return response()->json(['message' => 'Certificate already exist'], 409);
        }
        $certification = new Certification;
        $certification->certificate_name = $req->certificate_name;
        $certification->save();
        $certification->user_count = $certification->users->count();
        return response()->json(['message' => 'Certificate added','certification' => $certification ], 201);
    }

    function getCertifications () {
        $certifications = Certification::all();
        foreach ($certifications as $certification) {
            $certification->user_count = $certification->users->count();
            $certification->certificate_id = $certification->id;

        }
        return response()->json($certifications, 200);
    }

    function deleteCertification (Request $req) {
        $req->validate([
            'certificate_id' => 'required|integer'
        ]);
        $certification = Certification::where(['id' => $req->certificate_id])->first();
        if (!$certification) {
            return response()->json(['message' => 'Certificate not found'], 404);
        }
        $certification->delete();
        $user_certifications = UserCertificate::where(['certificate_id' => $req->certificate_id])->get();
        foreach ($user_certifications as $user_certification) {
            $user_certification->delete();
        }
        return response()->json(['message' => 'Certificate deleted'], 200);
    }

    function approveUser(Request $req){
        $user = User::where(['id' => $req->user_id])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->approve = true;
        $user->save();
        return response()->json(['message' => 'User approved','user' => $user], 200);
    }

    function getUsers () {
        $users = User::where('role','user')->orderBy('approve')->get();
        return response()->json($users, 200);
    }

    function deleteUser(Request $req){
        $user = User::where(['id' => $req->user_id])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        $user_certifications = UserCertificate::where(['user_id' => $req->user_id])->get();
        foreach ($user_certifications as $user_certification) {
            $user_certification->delete();
        }
        return response()->json(['message' => 'User deleted'], 200);
    }
}
