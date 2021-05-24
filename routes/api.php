<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\UserController;
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

Route::get('/login', [UserController::class, 'login'])->name('api.login');
Route::post('/register', [UserController::class, 'register'])->name('api.register');

Route::middleware(['refresh.token'])->group(function(){
    Route::get('/profile/{name}', [HomeController::class, 'profile']);
    Route::get('/get-chats', [HomeController::class, 'get_chats']);
    Route::get('/my-profile', [HomeController::class, 'my_profile']);
    Route::get('/own-character', [HomeController::class, 'own_character'])->name('home.own-character');
    Route::patch('/update-password', [UserController::class, 'update_password'])->name('api.update.password');
    Route::post('/store-profile', [UserController::class, 'store_profile'])->name('api.store.profile');
    Route::post('/unlock-character', [HomeController::class, 'keyup_unlock_character'])->name('api.store.keyup_unlock_character');
    Route::patch('/update-signature', [HomeController::class, 'update_signature'])->name('api.update.signature');
    Route::patch('/update-teetee', [HomeController::class, 'update_teetee'])->name('api.update.teetee');
    Route::patch('/activity', [HomeController::class, 'activity'])->name('api.activity');
    Route::patch('/cooperation', [HomeController::class, 'cooperation'])->name('api.cooperation');
    Route::patch('/operating', [HomeController::class, 'operating'])->name('api.operating');
    Route::patch('/rebirth', [HomeController::class, 'rebirth'])->name('api.rebirth');
    Route::get('/change-page', [HomeController::class, 'change_page'])->name('api.change_page');
    Route::post('/create-message', [HomeController::class, 'create_message'])->name('api.create.message');
    Route::post('/like', [HomeController::class, 'like'])->name('api.like');
});

/****************************************************************************************************/
