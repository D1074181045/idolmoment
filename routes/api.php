<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware(['refresh.token'])->group(function(){
    Route::get('/profile/{name}', [HomeController::class, 'profile']);
    Route::get('/get-chats', [HomeController::class, 'get_chats']);
    Route::get('/my-profile', [HomeController::class, 'my_profile']);
    Route::get('/own-character', [HomeController::class, 'own_character']);
    Route::patch('/update-password', [UserController::class, 'update_password']);
    Route::post('/store-profile', [UserController::class, 'store_profile']);
    Route::post('/unlock-character', [HomeController::class, 'keyup_unlock_character']);
    Route::patch('/update-signature', [HomeController::class, 'update_signature']);
    Route::patch('/update-teetee', [HomeController::class, 'update_teetee']);
    Route::patch('/activity', [HomeController::class, 'activity']);
    Route::patch('/cooperation', [HomeController::class, 'cooperation']);
    Route::patch('/operating', [HomeController::class, 'operating']);
    Route::patch('/rebirth', [HomeController::class, 'rebirth']);
    Route::get('/change-page', [HomeController::class, 'change_page']);
    Route::post('/create-message', [HomeController::class, 'create_message']);
    Route::post('/like', [HomeController::class, 'like']);
});

Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/send', [VerificationController::class, 'send']);

/****************************************************************************************************/
