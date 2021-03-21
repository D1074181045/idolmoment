<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatRoomEvent;
use App\Events\DangerEvent;
use App\Events\UnlockCharacterEvent;
use App\Http\Controllers\Controller;
use App\Http\Other\activity;
use App\Http\Other\operating;
use App\Models\ChatRoom;
use App\Models\GameInfo;
use App\Models\GameCharacter;
use App\Models\CoolDown;
use App\Models\OwnCharacter;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public array $Character_List = array();
    public bool $Back_Home = false;

    /**
     * 解鎖偶像
     *
     * @param $character_name
     * @return array
     */
    public function unlock_character($character_name)
    {
        $Character = OwnCharacter::query()->BuildOwnCharacter([
            'character_name' => $character_name
        ]);

        if (!$Character->wasRecentlyCreated) {
            return [
                'status' => 0,
                'message' => '已擁有該偶像'
            ];
        }

        $tc_name = $Character->GameCharacter->tc_name;
        array_push($this->Character_List, $tc_name);

        return [
            'status' => 1,
            'message' => '解鎖新偶像：' . $tc_name
        ];
    }

    /**
     * 偶像抽獎
     *
     * @param $character_array
     * @param $rand
     */
    public function DrawCharacter($character_array, $rand)
    {
        $i = $rand;
        if (isset($character_array[$i])) {
            $this->unlock_character($character_array[$i]);
        }
    }

    /**
     * 簽名觸發
     *
     * @param $string
     */
    public function signature_unlock_character($string)
    {
        switch (true) {
            case substr($string, strpos($string, 'peko')) === 'peko':
                $this->unlock_character('Usada Pekora');
                break;
            case strpos($string, 'ahoy!') !== false:
                $this->unlock_character('Houshou marine');
                break;
        }
    }

    /**
     * keyup觸發
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function keyup_unlock_character(Request $request)
    {
        switch ($request->post('character_name')) {
            case 'Gawr Gura':
                $Json = $this->unlock_character('Gawr Gura');
                break;
            case 'Akai Haato':
                $Json = $this->unlock_character('Akai Haato');
                break;
            case 'Sakuramiko':
                $Json = $this->unlock_character('Sakuramiko');
                break;
            case 'Uruha Rushia':
                $Json = $this->unlock_character('Uruha Rushia');
                break;
            case 'Inugami Korone':
                $Json = $this->unlock_character('Inugami Korone');
                break;
            default:
                $Json = [
                    'status' => 0,
                    'message' => '沒有該角色解鎖事件'
                ];

                break;
        }

        return response()->json($Json);
    }

    /**
     * 更改簽名檔
     *
     * @return JsonResponse
     */
    public function MyProfile()
    {
        try {
            if (!GameInfo::query()->UserGameInfoBuilt(Auth::user()->name)->count()) {
                return response()->json([
                    'status' => 0,
                    'message' => '尚未創建'
                ]);
            }

            $cool_down = CoolDown::query()->select('signature', 'activity', 'chat', 'operating')->CurrentLoginUser();
            if (!$cool_down) {
                return response()->json([
                    'status' => 0
                ]);
            }

            $self_game_info = GameInfo::query()
                ->select('name', 'nickname', 'charm', 'max_vitality', 'current_vitality', 'energy', 'graduate',
                        'popularity', 'rebirth_counter', 'reputation', 'resistance', 'signature', 'teetee', 'use_character')
                ->CurrentLoginUser();

            if (!$self_game_info) {
                return response()->json([
                    'status' => 0
                ]);
            }

            $teetee_info = $this->teetee_info($self_game_info);
            $self_game_info['use_character'] = [
                'img_file_name' => $self_game_info->GameCharacter->img_file_name,
                'tc_name' => $self_game_info->GameCharacter->tc_name,
                'en_name' => $self_game_info->GameCharacter->en_name
            ];
            $this->UserNameEncrypt($self_game_info);
            unset($self_game_info->GameCharacter);

            return response()->json([
                'status' => 1,
                'my_profile' => $self_game_info,
                'teetee_info' => $teetee_info,
                'cool_down' => $cool_down,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => '更新失敗，簽名超過30字元或有非法字元'
            ]);
        }
    }

    /**
     * 更改簽名檔
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update_signature(Request $request)
    {
        try {
            $this->validate($request, [
                'signature' => ['nullable', 'regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9 ]+$/u', 'string', 'max:30']
            ]);

            if (!$cool_down = CoolDown::query()->CurrentLoginUser()) {
                return response()->json([
                    'status' => 0,
                    'message' => '更新失敗'
                ]);
            }

            $signature_time = $cool_down->signature;

            if ($signature_time > Carbon::now()) {
                return response()->json([
                    'status' => 0,
                    'message' => '更新失敗，剩餘時間：' . $this->remain_time($signature_time, Carbon::now()) . '秒'
                ]);
            }

            $signature = $request->post('signature');

            $cool_down->GameInfo->update_signature($signature);

            $signature_time = $cool_down->update_signature(SIGNATURE_DELAY_T);

            $this->signature_unlock_character(strtolower($signature));

            return response()->json([
                'status' => 1,
                'signature_time' => $signature_time,
                'message' => '更新成功'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => '更新失敗，簽名超過30字元或有非法字元'
            ]);
        }
    }

    /**
     * 更改貼貼
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update_teetee(Request $request)
    {
        try {
            $this->validate($request, [
                'teetee' => ['nullable', 'regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u', 'string', 'max:12']
            ]);

            if (!$self_game_info = GameInfo::query()->CurrentLoginUser()) {
                return response()->json([
                    'teetee_status' => 0,
                    'teetee_name' => null,
                    'message' => '更新失敗'
                ]);
            }

            $self_game_info->update_teetee($request->post('teetee'));

            $teetee_info = $this->teetee_info($self_game_info);

            return response()->json([
                'teetee_status' => $teetee_info['status'],
                'teetee_name' => $teetee_info['teetee_name'],
                'message' => '更新成功'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'teetee_status' => 0,
                'teetee_name' => null,
                'message' => '更新失敗，無效的暱稱格式'
            ]);
        }
    }

    /**
     * 切換活躍偶樣的頁數
     *
     * @param Request $request
     * @return JsonResponse
     */
    function change_page(Request $request)
    {
        try {
            if (!$self_game_info = GameInfo::query()->CurrentLoginUser()) {
                return response()->json([
                    'status' => 0,
                    'teetee_name' => '',
                    'teetee_status' => 0,
                    'total_pages' => 0,
                    'max_popularity' => 0,
                    'data' => ""
                ]);
            }

            $teetee_info = $this->teetee_info($self_game_info);

            if ($search_name = $request->get('search_name')) {
                $idol_list = GameInfo::query()->SearchName($search_name)->get();
                $max_popularity = GameInfo::query()->max('popularity');

                $this->UsersNameEncrypt($idol_list);
                $this->HtmlSpecialChars_List($idol_list, 'signature');

                return response()->json([
                    'status' => 1,
                    'teetee_name' => $teetee_info['teetee_name'],
                    'max_popularity' => $max_popularity,
                    'idol_list' => $idol_list
                ]);

            } else {
                $this->validate($request, [
                    'page' => ['required', 'int'],
                    'popularity' => ['required', 'int']
                ]);

                $pageRow_records = 20;
                $num_pages = $request->get('page');
                $self_popularity = $request->get('popularity');

                $startRow_records = ($num_pages - 1) * $pageRow_records;

                $idol_total = GameInfo::query()->ActiveIdol($self_popularity);
                $idol_count = $idol_total->count();
                $idol_list = $idol_total->skip($startRow_records)->take($pageRow_records)->get();

                $max_popularity = GameInfo::query()->max('popularity');

                $this->UsersNameEncrypt($idol_list);
                $this->HtmlSpecialChars_List($idol_list, 'signature');

                return response()->json([
                    'status' => 1,
                    'teetee_name' => $teetee_info['teetee_name'],
                    'total_pages' => ceil($idol_count / $pageRow_records),
                    'max_popularity' => $max_popularity,
                    'idol_list' => $idol_list
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'teetee_name' => '',
                'teetee_status' => 0,
                'total_pages' => 0,
                'max_popularity' => 0,
                'data' => ""
            ]);

        }
    }

    /**
     * 偶像活動
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function activity(Request $request)
    {
        if (!$cool_down = CoolDown::query()->CurrentLoginUser()) {
            return response()->json([
                'status' => 0,
                'message' => '活動進行失敗'
            ]);
        }

        $activity_time = $cool_down->activity;

        if ($activity_time > Carbon::now()) {
            return response()->json([
                'status' => 0,
                'message' => '活動進行失敗，剩餘時間：' . $this->remain_time($activity_time, Carbon::now()) . '秒'
            ]);
        }

        $self_game_info = $cool_down->GameInfo;

        if ($self_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '活動進行失敗，你已畢業'
            ]);
        }

        $activity = new activity($self_game_info);

        switch ($request->post('activity_type')) {
            case 'adult-live':
                $activity->adult_live();

                $this->DrawCharacter(['Watson Amelia'], rand(0, 10));
                break;
            case 'live':
                $activity->live();

                $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki'], rand(0, 20));
                break;
            case 'do-good-things':
                $activity->do_good_things();
                break;
            case 'go-to-sleep':
                $activity->go_to_sleep();
                break;
            case 'meditation':
                $activity->meditation();
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => '活動進行失敗，無此活動'
                ]);
        }

        unset($activity);

        $activity_time = $cool_down->update_activity(ACTIVITY_DELAY_T);

        if (GameInfo::query()->orderByDesc('popularity')->first()->nickname == $self_game_info->nickname)
            $this->unlock_character('Kiryu Coco');

        $popularity = $self_game_info->popularity;
        switch ($popularity) {
            case ($popularity >= 100000):
                $this->unlock_character('Hoshimachi Suisei');
                break;
            case ($popularity >= 50000):
                $this->unlock_character('Amane Kanata');
                break;
            case ($popularity >= 10000):
                $this->unlock_character('Nakiri Ayame');
                break;
        }

        return response()->json([
            'status' => 1,
            'activity_time' => $activity_time,
            'ability' => [
                'popularity' => number_format($self_game_info->popularity),
                'reputation' => number_format($self_game_info->reputation),
                'max_vitality' => number_format($self_game_info->max_vitality),
                'current_vitality' => number_format($self_game_info->current_vitality),
                'energy' => number_format($self_game_info->energy),
                'resistance' => number_format($self_game_info->resistance),
                'charm' => number_format($self_game_info->charm),
            ],
            'message' => "活動進行生效"
        ]);
    }

    /**
     * 偶像操作(to 對方)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function operating(Request $request)
    {
        if (!$cool_down = CoolDown::query()->CurrentLoginUser()) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗'
            ]);
        }

        $operating_time = $cool_down->operating;

        if ($operating_time > Carbon::now()) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，剩餘時間：' . $this->remain_time($operating_time, Carbon::now()) . '秒'
            ]);
        }

        $opposite_name = $request->post('opposite_name');
        $opposite_name_decrypt = $this->UserNameDecrypt($opposite_name);
        $opposite_game_info = GameInfo::query()->find($opposite_name_decrypt);

        if ($opposite_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，對方已畢業'
            ]);
        }

        $self_game_info = $cool_down->GameInfo;

        if ($self_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，你已畢業'
            ]);
        }

        $operating = new operating($opposite_game_info, $self_game_info);

        switch ($request->post('operating_type')) {
            case 'send-blade':
                $information = $operating->send_blade();
                event(new DangerEvent($opposite_name, '你遭受到攻擊了'));
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => "操作無效，未有此操作"
                ]);
        }

        unset($operating);

        $operating_time = $cool_down->update_operating(OPERATING_DELAY_T);

        return response()->json([
            'status' => 1,
            'information' => $information,
            'operating_time' => $operating_time,
            'message' => "操作成功"
        ]);
    }

    /**
     * 偶像轉生
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function rebirth(Request $request)
    {
        $character_name = $request->post('character_name');

        if (!$self_game_info = GameInfo::query()->CurrentLoginUser()) {
            return response()->json([
                'status' => 0,
                'message' => "轉生失敗"
            ]);
        }

        if (!$game_character = GameCharacter::query()->find($character_name)) {
            return response()->json([
                'status' => 0,
                'message' => "轉生失敗，沒有該偶像。"
            ]);
        }

        if (!OwnCharacter::query()->OwnedCharacter(['character_name' => $character_name])->count()) {
            return response()->json([
                'status' => 0,
                'message' => "轉生失敗，尚未解鎖該偶像。"
            ]);
        }

        $self_game_info->rebirth($game_character);

        switch ($self_game_info->rebirth_counter) {
            case 1:
                $this->Back_Home = $this->unlock_character('Yukihana Lamy')['status'];
                break;
        }

        return response()->json([
            'status' => 1,
            'Back_Home' => !$this->Back_Home,
            'message' => "轉生成功"
        ]);
    }

    /**
     * 新增訊息
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create_message(Request $request)
    {
        if (!$cool_down = CoolDown::query()->CurrentLoginUser()) {
            return response()->json([
                'status' => 0,
                'message' => '送出失敗'
            ]);
        }

        $chat_time = $cool_down->chat;

        if ($chat_time > Carbon::now()) {
            return response()->json([
                'status' => 0,
                'message' => '送出失敗，剩餘時間：' . $this->remain_time($chat_time, Carbon::now()) . '秒'
            ]);
        }

        $chat_message = ChatRoom::query()->create([
            'name' => $cool_down->name,
            'message' => $request->post('message')
        ]);

        $chat_time = $cool_down->update_chat(CHAT_DELAY_T);

        $chat_message->GameInfo;
        $chat_message = $chat_message->toArray();

        $name = $this->UserNameEncrypt2($chat_message['name']);
        $nickname = $chat_message['game_info']['nickname'];
        $message = htmlspecialchars($chat_message['message']);

        $chat_created_at = date("Y-m-d H:i:s", strtotime($chat_message['created_at']));

        try {
            event(new ChatRoomEvent($name, $nickname, $message, $chat_created_at));
        } catch (Exception $e) {}

        return response()->json([
            'status' => 1,
            'chat_time' => $chat_time,
            'message' => "送出成功"
        ]);
    }

    public function own_character() {
        $own_character_list = OwnCharacter::query()->OwnCharacterList()->get();

        return response()->json([
            'status' => 1,
            'own_character_list' => $own_character_list
        ]);
    }

    /**
     * 獲得偶像 (event)
     *
     */
    public function __destruct()
    {
        try {
            if (!empty($this->Character_List)) {
                event(new UnlockCharacterEvent($this->Character_List, $this->Back_Home));
            }
        } catch (Exception $e) {}
    }
}
