<?php

namespace App\Http\Controllers;

use App\Models\GameInfo;
use Illuminate\Contracts\View\view;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function spa()
    {
        return view('user.app');
    }

    /**
     * 建立使用者暱稱頁面
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
