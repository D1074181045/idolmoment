<?php

use App\Http\Controllers\Controller;
use App\Models\GameInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function(){
    Route::get('/logout', [UserController::class, 'logout'])->name('home.logout');
    Route::get('/update-password', [UserController::class, 'update_password'])->name('home.update.password');
    Route::get('/create-profile', [HomeController::class, 'spa'])->name('user.create.profile');
});

Route::get('/{user}', [UserController::class, 'spa'])
    ->where('user', 'login|register')
    ->name('user');

Route::get('/{home}', [HomeController::class, 'spa'])
    ->where('home', '.*')
    ->middleware('auth.user')
    ->name('home');

//->where('path', '^(?!api).*')
//
//Route::middleware('auth.user')->group(function(){
//    Route::get('/', [HomeController::class, 'index'])->name('home.index');
//    Route::get('/active-idol', [HomeController::class, 'active_idol'])->name('home.active_idol');
//    Route::get('/profile/{name}', [HomeController::class, 'profile'])->name('home.profile');
//    Route::get('/rebirth', [HomeController::class, 'rebirth'])->name('home.rebirth');
//    Route::get('/chatroom', [HomeController::class, 'chatroom'])->name('home.chatroom');
//});

/********************************************************************************************************************/


