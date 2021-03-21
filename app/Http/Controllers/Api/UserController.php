<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProfileRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\GameCharacter;
use App\Models\GameInfo;
use App\Models\CoolDown;
use App\Models\OwnCharacter;
use App\Models\User;
use Carbon\Carbon;
use http\Cookie;
use Illuminate\Cookie\CookieJar;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * 登入訊息
     *
     * @param Request $request
     * @return JsonResponse
     */
    function login(Request $request)
    {
        try {
            $this->validate($request, [
                'username' => ['required'],
                'password' => ['required'],
                'autologin' => ['required']
            ]);



            $username = strtolower($request->get('username'));
            $password = $request->get('password');
            $autologin = filter_var($request->get('autologin'), FILTER_VALIDATE_BOOLEAN);

            $input_data = ['name' => $username, 'password' => $password];

            if (!$token = JWTAuth::attempt($input_data, $autologin)) {
                return response()->json([
                    'status' => 0,
                    'message' => "錯誤：登入失敗，帳號或密碼不正確。"
                ]);
            }

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
            ]);
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
                'message' => "錯誤：使用者(" . $user->name . ")已註冊"
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'message' => "使用者(" . $user->name . ")註冊完成"
            ]);
        }
    }

    function update_password(UpdateUserPasswordRequest $request)
    {
        $old_password = $request->post('old_password');
        $new_password = $request->post('new_password');

        $user = Auth::user();

        if (Auth::guard('web')->validate(['name' => $user->name, 'password' => $old_password])) {
            $user->password = Hash::make($new_password);
            $user->save();

            return response()->json([
                'status' => 1,
                'message' => "密碼修改成功，請重新登入"
            ]);

        } else {
            return response()->json([
                'status' => 0,
                'message' => "密碼修改失敗"
            ]);
        }
    }

    /**
     * 建立個人資料
     *
     * @param CreateProfileRequest $request
     * @return JsonResponse
     */
    public function store_profile(CreateProfileRequest $request)
    {
        if (!Auth::check() && !Auth::viaRemember()) {
            return response()->json([
                'status' => 0,
                'message' => "錯誤：建立失敗，尚未登入"
            ]);
        }

        $self_name = Auth::user()->name;
        $nickname = $request->post('nickname');

        if (GameInfo::query()->where('nickname', $nickname)->count()) {
            return response()->json([
                'status' => 0,
                'message' => "錯誤：建立失敗，已有相同暱稱"
            ]);
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
            return response()->json([
                'status' => 0,
                'message' => "錯誤：建立失敗，已創建角色"
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'message' => "建立成功"
            ]);
        }
    }
}
