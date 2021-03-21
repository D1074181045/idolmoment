<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\GameInfo;
use App\Models\CoolDown;
use App\Models\OwnCharacter;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function spa()
    {
        return view('home.app');
    }

    /**
     * 前往遊戲首頁
     *
     * @return View
     */
    public function index()
    {
        $cool_down = CoolDown::query()->CurrentLoginUser();

        $self_game_info = $cool_down->GameInfo;
        $teetee_status = $this->teetee_info($self_game_info)['status'];

        $signature_time = $cool_down->signature;
        $activity_time = $cool_down->activity;

        return view('home.index', [
            'self_game_info' => $self_game_info,
            'teetee_status' => $teetee_status,
            'signature_time' => $signature_time,
            'activity_time' => $activity_time
        ]);
    }

    /**
     * 前往活躍偶像頁面
     *
     * @return View
     */
    public function active_idol()
    {
        $pageRow_records = 20;

        $self_game_info = GameInfo::query()->CurrentLoginUser();
        $self_name = $this->UserNameEncrypt2($self_game_info->name);
        $self_popularity = $self_game_info->popularity;

        $idol_count = GameInfo::query()->where('popularity' , '<=', $self_popularity)->count();
        $max_popularity = GameInfo::query()->max('popularity');

        $total_pages = ceil($idol_count / $pageRow_records);

        return view('home.active-idol', [
            'total_pages' => $total_pages,
            'max_popularity' => $max_popularity,
            'self_name' => $self_name,
            'self_popularity' => $self_popularity
        ]);
    }

    /**
     * 前往個人資料頁面
     *
     * @param $name
     * @return View
     */
    public function profile($name) {
        $self_name = Auth::user()->name;
        $opposite_name = $this->UserNameDecrypt($name);

        $self_game_info = GameInfo::query()->with('GameCharacter')->findOrFail($self_name);
        $opposite_game_info = GameInfo::query()->with('GameCharacter')->findOrFail($opposite_name);

        return view('home.profile', [
            'opposite_game_info' => $opposite_game_info,
            'opposite_name' => $name,
            'self_game_info' => $self_game_info,
            'self_name' => $self_name,
            'operating_time' => $self_game_info->CoolDown->operating
        ]);
    }

    /**
     * 前往偶像轉生頁面
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View|\Illuminate\Http\JsonResponse
     */
    public function rebirth() {
        $own_characters = OwnCharacter::query()->OwnCharacterList()->get();

        return view('home.rebirth', ['own_characters' => $own_characters]);
    }

    /**
     * 前往聊天室頁面
     *
     * @return View
     */
    public function chatroom() {
        $chat_messages = ChatRoom::query()->with('GameInfo')->get();
        $chat_time = CoolDown::query()->CurrentLoginUser()->chat;

        $this->UsersNameEncrypt($chat_messages);

        return view('home.chatroom', [
            'chat_messages' => $chat_messages,
            'chat_time' => $chat_time
        ]);
    }
}
