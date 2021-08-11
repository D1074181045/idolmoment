<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BrowserSupport
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        switch (true) {
            case strpos($agent,"trident"):
            case strpos($agent,"msie"):
                return response('目前瀏覽器不支援');
        }

        return $next($request);
    }
}
