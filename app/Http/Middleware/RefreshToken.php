<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
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
            if (Auth::user() == JWTAuth::parseToken()->authenticate()) // 解析token, 並驗證使用者
                return $next($request);

            throw new UnauthorizedHttpException('jwt-auth', '驗證失敗');
        } catch (TokenExpiredException $exception) { // token 過期
            try {
                $refresh_token = JWTAuth::parseToken()->refresh(); // 刷新 token
            } catch (JWTException $e) { // 無法刷新 token
                throw new UnauthorizedHttpException('jwt-auth', $e->getMessage());
            }
        } catch (TokenBlacklistedException $exception){ // 使用黑名單的token
            throw new UnauthorizedHttpException('jwt-auth', 'black token');
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage());
        }

        $response = $next($request);

        return $response->header('Authorization', 'Bearer '.$refresh_token);
    }
}
