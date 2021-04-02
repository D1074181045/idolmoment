<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatRoomEvent;
use App\Events\DangerEvent;
use App\Events\UnlockCharacterEvent;
use App\Http\Controllers\Controller;
use App\Http\Other\activity;
use App\Http\Other\cooperation;
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
    public array $self_character_list = array();

    public string $opposite_name;
    public array $opposite_character_list = array();

    /**
     * 解鎖偶像
     *
     * @param $character_name
     * @param $IsSelf
     * @return array
     */
    public function unlock_character($character_name, $IsSelf = true)
    {
        if ($IsSelf) {
            $Character = OwnCharacter::query()->BuildOwnCharacter([
                'username' => Auth::user()->name,
                'character_name' => $character_name
            ]);
        } else {
            $Character = OwnCharacter::query()->BuildOwnCharacter([
                'username' => $this->opposite_name,
                'character_name' => $character_name
            ]);
        }

        if (!$Character->wasRecentlyCreated) {
            return [
                'status' => 0,
                'message' => '已擁有該偶像'
            ];
        }

        $tc_name = $Character->GameCharacter->tc_name;
        if ($IsSelf)
            array_push($this->self_character_list, $tc_name);
        else
            array_push($this->opposite_character_list, $tc_name);

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
            case 'Akai Haato':
                $Json = $this->unlock_character("Akai Haato");
                break;
            case 'Uruha Rushia':
                $Json = $this->unlock_character("Uruha Rushia");
                break;
            case 'Inugami Korone':
                $Json = $this->unlock_character("Inugami Korone");
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
     * 能力提升的解鎖事件
     *
     * @param $game_info
     * @param bool $IsSelf
     * @return void
     */
    public function ability_upgrade_unlock_character($game_info, $IsSelf = true) {
        if (GameInfo::query()->orderByDesc('popularity')->first()->nickname == $game_info->nickname)
            $this->unlock_character("Kiryu Coco", $IsSelf);

        $popularity = $game_info->popularity;
        switch ($popularity) {
            case ($popularity >= 10000000):
                $this->unlock_character("Tokino Sora", $IsSelf);
                break;
            case ($popularity >= 5000000):
                $this->unlock_character("Himemori Luna", $IsSelf);
                break;
            case ($popularity >= 3000000):
                $this->unlock_character("Momosuzu Nene", $IsSelf);
                break;
            case ($popularity >= 2000000):
                $this->unlock_character("Ninomae Ina'nis", $IsSelf);
                break;
            case ($popularity >= 1000000):
                $this->unlock_character("AZKi", $IsSelf);
                break;
            case ($popularity >= 500000):
                $this->unlock_character("Shirogane Noel", $IsSelf);
                break;
            case ($popularity >= 100000):
                $this->unlock_character("Hoshimachi Suisei", $IsSelf);
                break;
            case ($popularity >= 50000):
                $this->unlock_character("Amane Kanata", $IsSelf);
                break;
            case ($popularity >= 10000):
                $this->unlock_character("Nakiri Ayame", $IsSelf);
                break;
        }

        $reputation = $game_info->reputation;
        switch ($reputation) {
            case ($reputation >= 45000):
                $this->unlock_character('Anya Melfissa', $IsSelf);
                break;
            case ($reputation >= 40000):
                $this->unlock_character('Yozora Mel', $IsSelf);
                break;
            case ($reputation >= 30000):
                $this->unlock_character('Shishiro Botan', $IsSelf);
                break;
            case ($reputation >= 15000):
                $this->unlock_character('Oozora Subaru', $IsSelf);
                break;
            case ($reputation >= 10000):
                $this->unlock_character('Ookami Mio', $IsSelf);
                break;
            case ($reputation >= 5000):
                $this->unlock_character('Murasaki Shion', $IsSelf);
                break;
            case ($reputation >= 1000):
                $this->unlock_character('Tsunomaki Watame', $IsSelf);
                break;
        }

        $charm = $reputation = $game_info->charm;
        switch ($charm) {
            case ($charm >= 1320):
                $this->unlock_character('Pavolia Reine', $IsSelf);
                break;
            case ($charm >= 1000):
                $this->unlock_character('Aki Rosenthal', $IsSelf);
                break;
            case ($charm >= 700):
                $this->unlock_character('Ayunda Risu', $IsSelf);
                break;
            case ($charm >= 300):
                $this->unlock_character('Yuzuki Choco', $IsSelf);
                break;
        }
    }

    /**
     * 顯示聊天訊息
     *
     * @return JsonResponse
     */
    public function get_chat() {

        $chat_messages = ChatRoom::query()->Chat_info()->get();
        $this->UsersNameEncrypt($chat_messages);

        return response()->json([
            'status' => 1,
            'chat_messages' => $chat_messages,
        ]);
    }

    /**
     * 對方的個人資料
     *
     * @param $name
     * @return JsonResponse
     */
    public function profile($name) {
        $opposite_name = $this->UserNameDecrypt($name);

        $opposite_game_info = GameInfo::query()->with(['GameCharacter' => function ($query) {
            $query->select('tc_name', 'en_name', 'img_file_name');
        }])->findOrFail($opposite_name,[
            'nickname', 'charm', 'max_vitality', 'energy', 'graduate', 'popularity',
            'rebirth_counter', 'reputation', 'resistance', 'signature', 'teetee', 'use_character'
        ]);

        $opposite_game_info['name'] = $name;

        return response()->json([
            'status' => 1,
            'opposite_profile' => $opposite_game_info,
        ]);
    }

    /**
     * 獲得自己的遊戲資料
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

            $cool_down = CoolDown::query()->select('signature', 'activity', 'cooperation', 'chat', 'operating')->CurrentLoginUser();
            if (!$cool_down) {
                return response()->json([
                    'status' => 0
                ]);
            }

            $self_game_info = GameInfo::query()
                ->select('name', 'nickname', 'charm', 'max_vitality', 'current_vitality', 'energy', 'graduate',
                        'popularity', 'rebirth_counter', 'reputation', 'resistance', 'signature', 'teetee', 'use_character')
                ->with(['GameCharacter' => function ($query) {
                    $query->select('tc_name', 'en_name', 'img_file_name');
                }])->CurrentLoginUser();

            if (!$self_game_info) {
                return response()->json([
                    'status' => 0
                ]);
            }

            $teetee_info = $this->teetee_info($self_game_info);
            $this->UserNameEncrypt($self_game_info);

            return response()->json([
                'status' => 1,
                'profile' => $self_game_info,
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

                $this->DrawCharacter(['Watson Amelia', 'Houshou Marine'], rand(0, 5));
                break;
            case 'live':
                $activity->live();

                switch ($self_game_info->use_character) {
                    case 'Inugami Korone':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Nekomata Okayu'], rand(0, 15));
                        break;
                    case 'Usada Pekora':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Sakuramiko', 'Moona Hoshinova'], rand(0, 20));
                        break;
                    case 'Shirogane Noel':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Shiranui Flare'], rand(0, 15));
                        break;
                    case 'Mori Calliope':
                        $this->DrawCharacter(['Takanashi Kiara', 'Shirakami Fubuki'], rand(0, 10));
                        break;
                    case 'Watson Amelia':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Gawr Gura'], rand(0, 15));
                        break;
                    case 'Shirakami Fubuki':
                        $this->DrawCharacter(['Mori Calliope', 'Natsuiro Matsuri'], rand(0, 10));
                        break;
                    case 'Uruha Rushia':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Kureiji Ollie'], rand(0, 15));
                        break;
                    default:
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki'], rand(0, 10));
                        break;
                }

                break;
            case 'do-good-things':
                $activity->do_good_things();

                switch ($self_game_info->use_character){
                    case 'Amane Kanata':
                        $this->DrawCharacter(['Tokoyami Towa'], rand(0, 5));
                        break;
                }

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

        $this->ability_upgrade_unlock_character($self_game_info);

        return response()->json([
            'status' => 1,
            'activity_time' => $activity_time,
            'ability' => [
                'popularity' => $self_game_info->popularity,
                'reputation' => $self_game_info->reputation,
                'max_vitality' => $self_game_info->max_vitality,
                'current_vitality' => $self_game_info->current_vitality,
                'energy' => $self_game_info->energy,
                'resistance' => $self_game_info->resistance,
                'charm' => $self_game_info->charm,
            ],
            'message' => "活動進行生效"
        ]);
    }

    /**
     * 偶像的合作活動
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cooperation(Request $request) {
        if (!$cool_down = CoolDown::query()->CurrentLoginUser()) {
            return response()->json([
                'status' => 0,
                'message' => '合作活動進行失敗'
            ]);
        }

        $cooperation_time = $cool_down->cooperation;

        if ($cooperation_time > Carbon::now()) {
            return response()->json([
                'status' => 0,
                'message' => '合作活動進行失敗，剩餘時間：' . $this->remain_time($cooperation_time, Carbon::now()) . '秒'
            ]);
        }

        $self_game_info = $cool_down->GameInfo;

        if ($self_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '合作活動進行失敗，你已畢業'
            ]);
        }

        $teetee_info = $this->teetee_info($self_game_info);

        if (!$teetee_info['status']) {
            return response()->json([
                'status' => 0,
                'message' => '你沒有貼貼夥伴，無法進行合作活動'
            ]);
        }

        if ($teetee_info['teetee_graduate']) {
            return response()->json([
                'status' => 0,
                'message' => '你的貼貼夥伴已畢業'
            ]);
        }

        $this->opposite_name = $this->UserNameDecrypt($teetee_info['teetee_name']);
        $teetee_game_info = GameInfo::query()->find($this->opposite_name);

        $cooperation = new cooperation($self_game_info, $teetee_game_info);

        switch ($request->post('cooperation_type')) {
            case 'play-ordinary-game':
                $cooperation->play_ordinary_game();
                break;
            case 'play-tacit-game':
                $cooperation->play_tacit_game();
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => '合作活動進行失敗，無此活動'
                ]);
        }

        unset($cooperation);

        $cooperation_time = $cool_down->update_cooperation(COOPERATION_DELAY_T);

        $this->ability_upgrade_unlock_character($self_game_info);
        $this->ability_upgrade_unlock_character($teetee_game_info, false);

        return response()->json([
            'status' => 1,
            'cooperation_time' => $cooperation_time,
            'ability' => [
                'popularity' => $self_game_info->popularity,
                'reputation' => $self_game_info->reputation,
                'max_vitality' => $self_game_info->max_vitality,
                'current_vitality' => $self_game_info->current_vitality,
                'energy' => $self_game_info->energy,
                'resistance' => $self_game_info->resistance,
                'charm' => $self_game_info->charm,
            ],
            'message' => "合作活動進行生效"
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

        $opposite_name = $request->post('opposite_name');
        $self_game_info = $cool_down->GameInfo;

        $self_teetee_name = $this->teetee_info($self_game_info)['teetee_name'];

        if ($self_teetee_name === $opposite_name) {
            return response()->json([
                'status' => 0,
                'message' => '不能寄刀片給你的貼貼'
            ]);
        }

        if ($self_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，你已畢業'
            ]);
        }

        $operating_time = $cool_down->operating;

        if ($operating_time > Carbon::now()) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，剩餘時間：' . $this->remain_time($operating_time, Carbon::now()) . '秒'
            ]);
        }

        $this->opposite_name = $opposite_name_decrypt = $this->UserNameDecrypt($opposite_name);

        $opposite_game_info = GameInfo::query()->find($opposite_name_decrypt);

        if ($opposite_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，對方已畢業'
            ]);
        }

        $operating = new operating($opposite_game_info, $self_game_info);

        switch ($request->post('operating_type')) {
            case 'send-blade':
                $information = $operating->send_blade();
                event(new DangerEvent($opposite_name, '你收到刀片了'));
                break;
            case 'endorse':
                $information = $operating->endorse();
                event(new DangerEvent($opposite_name, '你受到讚賞了'));
                break;
            case 'donate':
                $information = $operating->donate();
                event(new DangerEvent($opposite_name, '你接收到了斗內'));
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => "操作無效，未有此操作"
                ]);
        }

        unset($operating);

        $operating_time = $cool_down->update_operating(OPERATING_DELAY_T);

        $this->ability_upgrade_unlock_character($self_game_info);
        $this->ability_upgrade_unlock_character($opposite_game_info, false);

        return response()->json([
            'status' => 1,
            'opposite_ability' => [
                'popularity' => $opposite_game_info->popularity,
                'reputation' => $opposite_game_info->reputation,
                'max_vitality' => $opposite_game_info->max_vitality,
                'current_vitality' => $opposite_game_info->current_vitality,
                'energy' => $opposite_game_info->energy,
                'resistance' => $opposite_game_info->resistance,
                'charm' => $opposite_game_info->charm,
                'graduate' => $opposite_game_info->graduate
            ],
            'self_ability' => [
                'popularity' => $self_game_info->popularity,
                'reputation' => $self_game_info->reputation,
                'max_vitality' => $self_game_info->max_vitality,
                'current_vitality' => $self_game_info->current_vitality,
                'energy' => $self_game_info->energy,
                'resistance' => $self_game_info->resistance,
                'charm' => $self_game_info->charm,
                'graduate' => $self_game_info->graduate
            ],
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

        if (!$self_game_info = GameInfo::CurrentLoginUser()) {
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
                $this->unlock_character('Yukihana Lamy');
                break;
        }

        return response()->json([
            'status' => 1,
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
        try {
            $this->validate($request, [
                'message' => ['nullable', 'max:255'],
            ]);

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
            $message = $chat_message['message'];
            $chat_created_at = $chat_message['created_at'];

            try {
                event(new ChatRoomEvent($name, $nickname, $message, $chat_created_at));
            } catch (Exception $e) {}

            return response()->json([
                'status' => 1,
                'chat_time' => $chat_time,
                'message' => "送出成功"
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 0,
                'message' => "送出失敗"
            ]);
        }
    }

    /**
     * 顯示擁有的偶像
     *
     * @return JsonResponse
     */
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
            if (!empty($this->self_character_list)) {
                event(new UnlockCharacterEvent($this->self_character_list, Auth::user()->name));
            }
            if (!empty($this->opposite_character_list)) {
                event(new UnlockCharacterEvent($this->opposite_character_list, $this->opposite_name));
            }
        } catch (Exception $e) {}
    }
}
