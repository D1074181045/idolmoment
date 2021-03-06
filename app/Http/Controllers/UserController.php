<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\view;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * @param Request $request
     * @return RedirectResponse|view
     */
    public function create_profile(Request $request)
    {
        $self_info = $request->user()->GameInfo;

        if (!is_null($self_info))
            return Redirect::to(Route('home', ''));
        else
            return view('home.app');
    }

    /**
     * 登出
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
