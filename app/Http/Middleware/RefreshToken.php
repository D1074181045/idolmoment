<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class RefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (JWTAuth::parseToken()->authenticate())
                return $next($request);

            throw new UnauthorizedHttpException('jwt-auth', '未登錄');
        } catch (TokenExpiredException $exception) { // token 過期
            try {
                $refresh_token = JWTAuth::parseToken()->refresh(); // 刷新 token

            } catch (JWTException $e) {

                throw new UnauthorizedHttpException('jwt-auth', $e->getMessage());
            }
        } catch (JWTException $e) {

            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage());
        }

        $response = $next($request);

        return $response->header('Authorization', 'Bearer '.$refresh_token);
    }
}
