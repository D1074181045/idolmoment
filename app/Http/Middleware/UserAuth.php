<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) { // 登入中
            $self_info = Auth::user()->GameInfo;

            if (is_null($self_info)) { // 未創建遊戲資料
                if (!$request->is('password/update') && !$request->is('email/send'))
                    return Redirect::to(Route('user.create.profile'));
            }
        } else {
            return Redirect::to(Route('user', 'login'));
        }

        return $next($request);
    }
}
