<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChamberMemberController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SocialMediaController;

//-------------------------  Public routes -------------------------

//  Auth
Route::post('auth/signup', [AuthController::class, 'signup']);
Route::post('auth/login', [AuthController::class, 'login']);

//  Memberships
Route::get('/memberships', [MembershipController::class, 'index']);
Route::get('/memberships-dash', [MembershipController::class, 'indexDash']);
Route::get('/memberships/{id}', [MembershipController::class, 'show']);

//  Events
Route::get('/events', [EventController::class, 'index']);
Route::get('/events-dash', [EventController::class, 'indexDash']);
Route::get('/events/{id}', [EventController::class, 'show']);

//  Services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services-dash', [ServiceController::class, 'indexDash']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

//  Labels
Route::get('/labels', [LabelController::class, 'index']);
Route::get('/labels/{id}', [LabelController::class, 'show']);

//  JoinRequests
Route::get('/join-requests', [JoinRequestController::class, 'index']);
Route::get('/join-requests/{id}', [JoinRequestController::class, 'show']);
Route::get('/join-requests-charts', [JoinRequestController::class, 'charts']);

//  SocialMedias
Route::get('/social-medias', [SocialMediaController::class, 'index']);
Route::get('/social-medias/{id}', [SocialMediaController::class, 'show']);

//  Contacts
Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/{id}', [ContactController::class, 'show']);

//  ChamberMembers
Route::get('/chamber-members', [ChamberMemberController::class, 'index']);
Route::get('/chamber-members-dash', [ChamberMemberController::class, 'indexDash']);
Route::get('/chamber-members/{id}', [ChamberMemberController::class, 'show']);


//-------------------------  Protected routes -------------------------

Route::middleware(['auth:sanctum'])->group(function () {
    //  Content wich is protected by auth:sanctum

    //  Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    //  Memberships
    Route::post('/memberships', [MembershipController::class, 'store']);
    Route::put('/memberships/{id}', [MembershipController::class, 'update']);
    Route::delete('/memberships/{id}', [MembershipController::class, 'destroy']);

    //  Events
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}/update-data', [EventController::class, 'updateData']);
    Route::post('/events/{id}/update-image', [EventController::class, 'updateImage']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::delete('/events/{id}/image', [EventController::class, 'deleteImage']);

    //  Services
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}/update-data', [ServiceController::class, 'updateData']);
    Route::post('/services/{id}/update-image', [ServiceController::class, 'updateImage']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::delete('/services/{id}/image', [ServiceController::class, 'deleteImage']);

    //  Labels
    Route::post('/labels', [LabelController::class, 'store']);
    Route::put('/labels/{id}', [LabelController::class, 'update']);
    Route::post('/labels/{id}/update-video', [LabelController::class, 'uploadVideo']);
    Route::delete('/labels/{id}', [LabelController::class, 'destroy']);

    //  JoinRequests
    Route::post('/join-requests', [JoinRequestController::class, 'store']);
    Route::put('/join-requests/{id}', [JoinRequestController::class, 'update']);
    Route::delete('/join-requests/{id}', [JoinRequestController::class, 'destroy']);

    //  SocialMedias
    Route::post('/social-medias', [SocialMediaController::class, 'store']);
    Route::put('/social-medias/{id}', [SocialMediaController::class, 'update']);
    Route::delete('/social-medias/{id}', [SocialMediaController::class, 'destroy']);

    //  Contacts
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);

    //  ChamberMembers
    Route::post('/chamber-members', [ChamberMemberController::class, 'store']);
    Route::put('/chamber-members/{id}/update-data', [ChamberMemberController::class, 'updateData']);
    Route::post('/chamber-members/{id}/update-image', [ChamberMemberController::class, 'updateImage']);
    Route::delete('/chamber-members/{id}', [ChamberMemberController::class, 'destroy']);
    Route::delete('/chamber-members/{id}/image', [ChamberMemberController::class, 'deleteImage']);
});
