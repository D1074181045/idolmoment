<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        switch (true) {
            case strpos($agent,"Firefox"):
            case strpos($agent,"Chrome"):
            case strpos($agent,"Safari"):
            case strpos($agent,"Opera"):
                if (Auth::check()){ // 登入中
                    $self_info = Auth::user()->GameInfo;

                    if (is_null($self_info)) { // 未創建遊戲資料
                        return Redirect::to(Route('user.create.profile'));
                    }
                } else {
                    return Redirect::to(Route('user', 'login'));
                }
                break;
            default:
                return response('目前瀏覽器不支援');
        }

        return $next($request);
    }
}
