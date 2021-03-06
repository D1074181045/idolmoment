<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Auth\ThrottlesLogins;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProfileRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\GameInfo;
use App\Models\OwnCharacter;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    use ThrottlesLogins;

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * 登入訊息
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    function login(Request $request)
    {
        if (method_exists($this,'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        try {
            $this->validate($request, [
                'username' => ['required'],
                'password' => ['required'],
                'autologin' => ['required']
            ]);

            $username = strtolower($request->post('username'));
            $password = $request->post('password');
            $autologin = filter_var($request->post('autologin'), FILTER_VALIDATE_BOOLEAN);

            $input_data = ['name' => $username, 'password' => $password];

            if (!$token = JWTAuth::attempt($input_data, $autologin)) {
                $this->incrementLoginAttempts($request);

                return response()->json([
                    'status' => 0,
                    'message' => "錯誤：登入失敗，帳號或密碼不正確。"
                ], 400);
            }

            $this->clearLoginAttempts($request);

            if ($autologin) {
                Auth::attempt($input_data, true);

                $rememberTokenName = Auth::getRecallerName();
                $rememberCookie = Auth::getCookieJar()->queued($rememberTokenName);

                if (!is_null($rememberCookie)) {
                    $cookieValue = $rememberCookie->getValue();

                    return response()->json([
                        'status' => 1,
                        'message' => "登入成功",
                        'token' => $token
                    ])->Cookie(cookie()->forever($rememberTokenName, $cookieValue));
                }
            }

            Auth::attempt($input_data, false);

            return response()->json([
                'status' => 1,
                'message' => "登入成功",
                'token' => $token
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => "錯誤：使用者名稱或密碼為空。"
            ], 400);
        }
    }

    /**
     * 註冊訊息
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    function register(CreateUserRequest $request)
    {
        $username = strtolower($request->post('username'));
        $password = $request->post('password');

        $user = User::query()->BuildUser([
            'username' => $username,
            'password' => $password
        ]);

        if (!$user->wasRecentlyCreated) {
            return response()->json([
                'status' => 0,
                'message' => "錯誤：使用者 $user->name 已註冊"
            ], 400);
        }

        return response()->json([
            'status' => 1,
            'message' => "使用者 $user->name 註冊完成"
        ]);
    }

    function update_password(UpdateUserPasswordRequest $request)
    {
        $old_password = $request->post('old_password');
        $new_password = $request->post('new_password');

        if ($old_password === $new_password){
            return response()->json([
                'status' => 1,
                'message' => "密碼修改失敗，密碼變更一樣"
            ]);
        }

        $user = $request->user();

        if (!Auth::guard('web')->validate(['name' => $user->name, 'password' => $old_password])) {
            return response()->json([
                'status' => 0,
                'message' => "密碼修改失敗，舊密碼不正確"
            ], 400);
        }

        $user->update([
            'password' => Hash::make($new_password)
        ]);

        return response()->json([
            'status' => 1,
            'message' => "密碼修改成功"
        ]);
    }

    /**
     * 建立個人資料
     *
     * @param CreateProfileRequest $request
     * @return JsonResponse
     */
    public function store_profile(CreateProfileRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 0,
                'message' => "錯誤：建立失敗，尚未登入"
            ], 400);
        }

        $self_name = $request->user()->name;
        $nickname = $request->post('nickname');

        if (GameInfo::query()->where('nickname', $nickname)->count()) {
            return response()->json([
                'status' => 0,
                'message' => "錯誤：建立失敗，已有相同暱稱"
            ], 400);
        }

        $DefaultCharacter = OwnCharacter::query()->BuildDefaultOwnCharacter($self_name)->GameCharacter;

        $wasRecentlyCreated = GameInfo::query()->BuildDefaultGameInfo([
            'name' => $self_name,
            'nickname' => $nickname,
            'use_character' => $DefaultCharacter->en_name,
            'vitality' => $DefaultCharacter->vitality,
            'energy' => $DefaultCharacter->energy,
            'resistance' => $DefaultCharacter->resistance,
            'charm' => $DefaultCharacter->charm
        ])->CoolDown()->firstOrCreate([
            'name' => $self_name,
        ])->wasRecentlyCreated;

        if (!$wasRecentlyCreated) {
            // 本次未創建遊戲資料
            return response()->json([
                'status' => 0,
                'message' => "錯誤：建立失敗，已創建角色"
            ], 400);
        }

        return response()->json([
            'status' => 1,
            'message' => "建立成功"
        ]);
    }
}
