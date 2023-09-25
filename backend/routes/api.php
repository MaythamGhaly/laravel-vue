<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\authController;
use App\Http\Controllers\userController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [authController::class, 'login']);
Route::post('/register', [authController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-certifications', [adminController::class, 'getCertifications']);
    Route::post('/add-certifications', [userController::class, 'addCertifications']);
    Route::get('/get-user-certifications', [userController::class, 'getUserCertifications']);
    Route::post('/delete-user-certification', [userController::class, 'deleteUserCertification']);
    Route::post('/edit-profile', [userController::class, 'editProfile']);
    //admin routes
    Route::middleware('admin')->group(function () {
        Route::post('/approve-user', [adminController::class, 'approveUser']);
        Route::post('/add-certifications', [adminController::class, 'addCertifications']);
        Route::post('/delete-certification', [adminController::class, 'deleteCertification']);
        Route::get('/get-users', [adminController::class, 'getUsers']);
    });
});
Route::middleware('auth:sanctum')->get('/authenticated-route', function () {
    // This route is protected by the auth:sanctum middleware
    // You can access the authenticated user using auth()->user()
    return auth()->user()->id;
});
