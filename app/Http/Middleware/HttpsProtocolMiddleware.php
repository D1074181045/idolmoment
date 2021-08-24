<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HttpsProtocolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
		if (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]))
			$is_https = $_SERVER["HTTP_X_FORWARDED_PROTO"] === 'https';
		else if (isset($_SERVER['HTTPS']))
			$is_https = $_SERVER["HTTPS"] === 'on';
		else
			$is_https = false;

        if (env('APP_ENV') === 'production' && !$is_https) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
