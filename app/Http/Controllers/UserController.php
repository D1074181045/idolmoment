<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\view;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function spa()
    {
        return view('user.app');
    }

    /**
     * 創建偶像頁面
     *
     *
     * @return RedirectResponse|view
     */
    public function create_profile()
    {
        $self_info = Auth::user()->GameInfo;

        if (!is_null($self_info))
            return Redirect::to(Route('home', ''));
        else
            return view('home.app');
    }

    /**
     * 登出
     *
     * @return RedirectResponse
     */
    public function logout() {
        Auth::logout();

        return Redirect::to('/');
    }
}
