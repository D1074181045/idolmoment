<?php

use App\Http\Controllers\ServiceController;
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

Route::get('sitemap.xml', [ServiceController::class, 'getSitemap']);

Route::middleware('auth')->group(function(){
    Route::get('logout', [UserController::class, 'logout'])->name('home.logout');
    Route::get('create-profile', [UserController::class, 'create_profile'])->name('user.create.profile');
});

Route::get('{user}', [UserController::class, 'spa'])
    ->where('user', join('|', [
        'login', 'register', 'password/reset.*', 'email/verify/.*/.*'
    ]))
    ->middleware('bs.sup')
    ->name('user');

Route::get('{home}', [HomeController::class, 'spa'])
    ->where('home', '^(?!api\/).*$') // 開頭非 "api/" 結尾任意
    ->middleware('auth.user')
    ->name('home');
