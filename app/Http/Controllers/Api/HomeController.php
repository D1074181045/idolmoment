<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatRoomEvent;
use App\Events\PromptEvent;
use App\Events\UnlockCharacterEvent;
use App\Http\Controllers\Controller;
use App\PlayAbilityOperation\Activity;
use App\PlayAbilityOperation\Cooperation;
use App\PlayAbilityOperation\Operating;
use App\Models\ChatRoom;
use App\Models\GameInfo;
use App\Models\GameCharacter;
use App\Models\CoolDown;
use App\Models\Like;
use App\Models\OwnCharacter;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
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
     * 偶像的合作活動
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cooperation(Request $request)
    {
        $cool_down = CoolDown::query()->CurrentLoginUser();

        $cooperation_time = $cool_down->cooperation;

        if ($cooperation_time > Carbon::now()) {
            $remain_time = $this->remain_time($cooperation_time, Carbon::now());

            return response()->json([
                'status' => 0,
                'cooperation_time' => $cooperation_time,
                'message' => "合作活動進行失敗，剩餘時間： $remain_time 秒"
            ], 400);
        }

        $self_game_info = $cool_down->GameInfo;

        if ($self_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '合作活動進行失敗，你已畢業'
            ], 400);
        }

        $teetee_info = $this->teetee_info($self_game_info);

        if (!$teetee_info['teetee_status']) {
            return response()->json([
                'status' => 0,
                'message' => '你沒有貼貼夥伴，無法進行合作活動'
            ], 400);
        }

        if ($teetee_info['teetee_graduate']) {
            return response()->json([
                'status' => 0,
                'message' => '你的貼貼夥伴已畢業'
            ], 400);
        }

        $this->opposite_name = $this->UserNameDecrypt($teetee_info['teetee_name']);
        $teetee_game_info = GameInfo::query()->find($this->opposite_name);

        $cooperation = new Cooperation($self_game_info, $teetee_game_info);

        switch ($request->post('cooperation_type')) {
            case 'play-ordinary-game':
                $cooperation->play_ordinary_game();

                $cooperation_time = $cool_down->update_cooperation(90);
                break;
            case 'play-tacit-game':
                $cooperation->play_tacit_game();

                $cooperation_time = $cool_down->update_cooperation(100);
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => '合作活動進行失敗，無此活動'
                ], 400);
        }

        unset($cooperation);

        $this->ability_upgrade_unlock_character($self_game_info);
        $this->ability_upgrade_unlock_character($teetee_game_info, 'opposite');

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
        $cool_down = CoolDown::query()->CurrentLoginUser();

        $opposite_name_encrypt = $request->post('opposite_name');
        $self_game_info = $cool_down->GameInfo;

        if ($self_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，你已畢業'
            ], 400);
        }

        $operating_time = $cool_down->operating;

        if ($operating_time > Carbon::now()) {
            $remain_time = $this->remain_time($operating_time, Carbon::now());

            return response()->json([
                'status' => 0,
                'operating_time' => $operating_time,
                'message' => "操作失敗，剩餘時間： $remain_time 秒"
            ], 400);
        }

        $this->opposite_name = $this->UserNameDecrypt($opposite_name_encrypt);
        $opposite_game_info = GameInfo::query()->find($this->opposite_name);

        if ($opposite_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '操作失敗，對方已畢業'
            ], 400);
        }

        $self_teetee_name = $this->teetee_info($self_game_info)['teetee_name'];
        $operating = new Operating($opposite_game_info, $self_game_info);

        switch ($request->post('operating_type')) {
            case 'send-blade':
                if ($self_teetee_name === $opposite_name_encrypt) {
                    return response()->json([
                        'status' => 0,
                        'message' => '不能寄刀片給你的貼貼'
                    ], 400);
                }

                $information = $operating->send_blade();
                event(new PromptEvent($opposite_name_encrypt, 'danger', '你收到刀片了'));

                $operating_time = $cool_down->update_operating(160);
                break;
            case 'defame':
                if ($self_teetee_name === $opposite_name_encrypt) {
                    return response()->json([
                        'status' => 0,
                        'message' => '不能抹黑你的貼貼'
                    ], 400);
                }

                $information = $operating->defame();
                event(new PromptEvent($opposite_name_encrypt, 'danger', '你被抹黑了'));

                $operating_time = $cool_down->update_operating(160);
                break;
            case 'endorse':
                $information = $operating->endorse();
                event(new PromptEvent($opposite_name_encrypt, 'success', "你受到偶像 $self_game_info->nickname 的讚賞了"));

                $operating_time = $cool_down->update_operating(120);
                break;
            case 'donate':
                $information = $operating->donate();
                event(new PromptEvent($opposite_name_encrypt, 'success', "你收到偶像 $self_game_info->nickname 的斗內了"));

                $operating_time = $cool_down->update_operating(120);
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => "操作無效，未有此操作"
                ], 400);
        }

        unset($operating);

        $this->ability_upgrade_unlock_character($self_game_info);
        $this->ability_upgrade_unlock_character($opposite_game_info, 'opposite');

        return response()->json([
            'status' => 1,
            'ability' => [
                'opposite' => [
                    'popularity' => $opposite_game_info->popularity,
                    'reputation' => $opposite_game_info->reputation,
                    'max_vitality' => $opposite_game_info->max_vitality,
                    'current_vitality' => $opposite_game_info->current_vitality,
                    'energy' => $opposite_game_info->energy,
                    'resistance' => $opposite_game_info->resistance,
                    'charm' => $opposite_game_info->charm,
                    'graduate' => $opposite_game_info->graduate
                ],
                'self' => [
                    'popularity' => $self_game_info->popularity,
                    'reputation' => $self_game_info->reputation,
                    'max_vitality' => $self_game_info->max_vitality,
                    'current_vitality' => $self_game_info->current_vitality,
                    'energy' => $self_game_info->energy,
                    'resistance' => $self_game_info->resistance,
                    'charm' => $self_game_info->charm,
                    'graduate' => $self_game_info->graduate
                ],
            ],
            'information' => $information,
            'operating_time' => $operating_time,
            'message' => "操作成功"
        ]);
    }

    /**
     * 能力提升的解鎖事件
     *
     * @param $game_info
     * @param string $target
     * @return void
     */
    public function ability_upgrade_unlock_character($game_info, $target = 'self')
    {
        $top_name = GameInfo::query()->orderByDesc('popularity')
                    ->where('graduate', '!=', true)->first()->name;

        // 人氣第一名
        if ($top_name === $game_info->name) $this->unlock_character("Kiryu Coco", $target);

        $popularity = $game_info->popularity;
        switch ($popularity) {
            case ($popularity >= 6500000):
                $this->unlock_character("Tokino Sora", $target);
                break;
            case ($popularity >= 5500000):
                $this->unlock_character("Airani Iofifteen", $target);
                break;
            case ($popularity >= 4000000):
                $this->unlock_character("Himemori Luna", $target);
                break;
            case ($popularity >= 3000000):
                $this->unlock_character("Momosuzu Nene", $target);
                break;
            case ($popularity >= 2000000):
                $this->unlock_character("Ninomae Ina'nis", $target);
                break;
            case ($popularity >= 1000000):
                $this->unlock_character("AZKi", $target);
                break;
            case ($popularity >= 500000):
                $this->unlock_character("Shirogane Noel", $target);
                break;
            case ($popularity >= 100000):
                $this->unlock_character("Hoshimachi Suisei", $target);
                break;
            case ($popularity >= 50000):
                $this->unlock_character("Amane Kanata", $target);
                break;
            case ($popularity >= 25000):
                $this->unlock_character("Kureiji Ollie", $target);
                break;
            case ($popularity >= 10000):
                $this->unlock_character("Nakiri Ayame", $target);
                break;
        }

        $reputation = $game_info->reputation;
        switch ($reputation) {
            case ($reputation >= 50000):
                $this->unlock_character('Anya Melfissa', $target);
                break;
            case ($reputation >= 40000):
                $this->unlock_character('Yozora Mel', $target);
                break;
            case ($reputation >= 30000):
                $this->unlock_character('Shishiro Botan', $target);
                break;
            case ($reputation >= 15000):
                $this->unlock_character('Oozora Subaru', $target);
                break;
            case ($reputation >= 10000):
                $this->unlock_character('Ookami Mio', $target);
                break;
            case ($reputation >= 5000):
                $this->unlock_character('Murasaki Shion', $target);
                break;
            case ($reputation >= 1000):
                $this->unlock_character('Tsunomaki Watame', $target);
                break;
        }

        $charm = $reputation = $game_info->charm;
        switch ($charm) {
            case ($charm >= 4000):
                $this->unlock_character('Pavolia Reine', $target);
                break;
            case ($charm >= 2000):
                $this->unlock_character('Aki Rosenthal', $target);
                break;
            case ($charm >= 1000):
                $this->unlock_character('Ayunda Risu', $target);
                break;
            case ($charm >= 500):
                $this->unlock_character('Yuzuki Choco', $target);
                break;
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

            $cool_down = CoolDown::query()->CurrentLoginUser();

            $signature_time = $cool_down->signature;

            if ($signature_time > Carbon::now()) {
                $remain_time = $this->remain_time($signature_time, Carbon::now());

                return response()->json([
                    'status' => 0,
                    'signature_time' => $signature_time,
                    'message' => "更新失敗，剩餘時間： $remain_time 秒"
                ], 400);
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
            ], 400);
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
            case substr($string, strpos($string, 'peko')) === 'peko': // 字尾是 peko
                $this->unlock_character('Usada Pekora');
                break;
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
        $cool_down = CoolDown::query()->CurrentLoginUser();

        $activity_time = $cool_down->activity;

        if ($activity_time > Carbon::now()) {
            $remain_time = $this->remain_time($activity_time, Carbon::now());

            return response()->json([
                'status' => 0,
                'activity_time' => $activity_time,
                'message' => "活動進行失敗，剩餘時間： $remain_time 秒"
            ], 400);
        }

        $self_game_info = $cool_down->GameInfo;

        if ($self_game_info->graduate) {
            return response()->json([
                'status' => 0,
                'message' => '活動進行失敗，你已畢業'
            ], 400);
        }

        $activity = new Activity($self_game_info);

        switch ($request->post('activity_type')) {
            case 'adult-live':
                $activity->adult_live();

                $this->DrawCharacter(['Watson Amelia', 'Houshou Marine'], 20);

                $activity_time = $cool_down->update_activity(60);
                break;
            case 'live':
                $activity->live();

                switch ($self_game_info->use_character) {
                    case 'Inugami Korone':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Nekomata Okayu'], 6.6);
                        break;
                    case 'Usada Pekora':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Sakuramiko', 'Moona Hoshinova'], 5);
                        break;
                    case 'Shirogane Noel':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Shiranui Flare'], 6.6);
                        break;
                    case 'Mori Calliope':
                        $this->DrawCharacter(['Takanashi Kiara', 'Shirakami Fubuki'], 10);
                        break;
                    case 'Watson Amelia':
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki', 'Gawr Gura'], 6.6);
                        break;
                    case 'Shirakami Fubuki':
                        $this->DrawCharacter(['Mori Calliope', 'Natsuiro Matsuri'], 10);
                        break;
                    default:
                        $this->DrawCharacter(['Mori Calliope', 'Shirakami Fubuki'], 10);
                        break;
                }

                $activity_time = $cool_down->update_activity(45);
                break;
            case 'do-good-things':
                $activity->do_good_things();

                switch ($self_game_info->use_character) {
                    case 'Amane Kanata':
                        $this->DrawCharacter(['Tokoyami Towa'], 20);
                        break;
                }

                $activity_time = $cool_down->update_activity(45);
                break;
            case 'go-to-sleep':
                $activity->go_to_sleep();

                $activity_time = $cool_down->update_activity(120);
                break;
            case 'meditation':
                $activity->meditation();

                $activity_time = $cool_down->update_activity(30);
                break;
            default:
                return response()->json([
                    'status' => 0,
                    'message' => '活動進行失敗，無此活動'
                ], 400);
        }

        unset($activity);

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
     * 偶像抽獎
     *
     * @param $character_array
     * @param $probability
     */
    public function DrawCharacter($character_array, $probability)
    {
        $max_num = (int)(100 / $probability) - 1;
        try {
            $rand_int = random_int(0, $max_num);
            if (isset($character_array[$rand_int])) { // 表示抽到偶像
                $this->unlock_character($character_array[$rand_int]);
            }
        } catch (Exception $e) {}
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

        $self_game_info = GameInfo::CurrentLoginUser();

        if (!$game_character = GameCharacter::query()->find($character_name)) {
            return response()->json([
                'status' => 0,
                'message' => "轉生失敗，沒有該偶像。"
            ], 400);
        }

        if (!OwnCharacter::query()->OwnedCharacter(['character_name' => $character_name])->count()) {
            return response()->json([
                'status' => 0,
                'message' => "轉生失敗，尚未解鎖該偶像。"
            ], 400);
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
     * 解鎖偶像
     *
     * @param $character_name
     * @param string $target
     * @return array
     */
    public function unlock_character($character_name, $target = 'self')
    {
        switch ($target) {
            case 'self':
                $username = Auth::user()->name;
                break;
            case 'opposite':
                $username = $this->opposite_name;
                break;
            default:
                return [
                    'status' => 0
                ];
        }

        try {
            $Character = OwnCharacter::query()->BuildOwnCharacter([
                'username' => $username,
                'character_name' => $character_name
            ]);

            if (!$Character->wasRecentlyCreated) {
                return [
                    'status' => 0,
                    'message' => '已擁有該偶像'
                ];
            }

            $tc_name = $Character->GameCharacter->tc_name;

            switch ($target) {
                case 'self':
                    array_push($this->self_character_list, $tc_name);
                    break;
                case 'opposite':
                    array_push($this->opposite_character_list, $tc_name);
                    break;
            }

            return [
                'status' => 1,
                'message' => "解鎖新偶像： $tc_name"
            ];

        } catch (QueryException $e) {
            return [
                'status' => 0
            ];
        }

    }

    /**
     * 獲得自己的遊戲資料
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function my_profile(Request $request)
    {
        $name = $request->user()->name;
        $email = $request->user()->email;
        $email_verify = $request->user()->hasVerifiedEmail();

        if (!GameInfo::query()->UserGameInfoBuilt($name)) {
            return response()->json([
                'status' => 0,
                'email' => $email,
                'email_verify' => $email_verify,
                'message' => '尚未創建'
            ]);
        }

        $self_game_info = GameInfo::query()
            ->select('name', 'nickname', 'charm', 'max_vitality', 'current_vitality', 'energy', 'graduate',
                'popularity', 'rebirth_counter', 'reputation', 'resistance', 'signature', 'teetee', 'use_character')
            ->with(['GameCharacter' => function ($query) {
                $query->select('tc_name', 'en_name', 'img_file_name');
            }])
            ->with(['CoolDown' => function ($query) {
                $query->select('name', 'signature', 'activity', 'cooperation', 'chat', 'operating');
            }])->CurrentLoginUser();

        $self_cool_down = $self_game_info->CoolDown;
        unset($self_cool_down->name);

        $teetee_info = $this->teetee_info($self_game_info);

        $like_num = $self_game_info->LikeTypeNum('like');
        $dislike_num = $self_game_info->LikeTypeNum('dislike');

        $this->UserNameEncrypt($self_game_info);

        return response()->json([
            'status' => 1,
            'email' => $email,
            'email_verify' => $email_verify,
            'like_num' => $like_num,
            'dislike_num' => $dislike_num,
            'profile' => $self_game_info,
            'teetee_info' => $teetee_info,
            'cool_down' => $self_cool_down,
        ]);
    }

    /**
     * 對方的個人資料
     *
     * @param Request $request
     * @param $name
     * @return JsonResponse
     */
    public function profile(Request $request, $name)
    {
        $opposite_name = $this->UserNameDecrypt($name);

        if (!$opposite_name)
            return response()->json([
                'error' => "not found"
            ], 400);

        $opposite_game_info = GameInfo::query()->with(['GameCharacter' => function ($query) {
            $query->select('tc_name', 'en_name', 'img_file_name');
        }])->findOrFail($opposite_name, [
            'name', 'nickname', 'charm', 'max_vitality', 'energy', 'graduate', 'popularity',
            'rebirth_counter', 'reputation', 'resistance', 'signature', 'teetee', 'use_character'
        ]);

        $like_selectedType = $opposite_game_info->SelectedLikeType($request->user()->name);
        $like_num = $opposite_game_info->LikeTypeNum('like');
        $dislike_num = $opposite_game_info->LikeTypeNum('dislike');

        $opposite_game_info['name'] = $name;

        return response()->json([
            'status' => 1,
            'like_select' => $like_selectedType,
            'opposite_like_num' => $like_num,
            'opposite_dislike_num' => $dislike_num,
            'opposite_profile' => $opposite_game_info,
        ]);
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

            $self_game_info = GameInfo::query()->CurrentLoginUser();

            $self_game_info->update_teetee($request->post('teetee'));

            $teetee_info = $this->teetee_info($self_game_info);

            return response()->json([
                'status' => 1,
                'teetee_status' => $teetee_info['teetee_status'],
                'teetee_name' => $teetee_info['teetee_name'],
                'teetee_graduate' => $teetee_info['teetee_graduate'],
                'message' => '更新成功'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'teetee_status' => 0,
                'teetee_name' => null,
                'teetee_graduate' => null,
                'message' => '更新失敗，無效的暱稱格式'
            ], 400);
        }
    }

    /**
     * 切換活躍偶樣的頁數
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function change_page(Request $request)
    {
        try {
            if ($search_name = $request->get('search_name')) {
                $idol_list = GameInfo::query()->SearchName($search_name)->get();
                $max_popularity = GameInfo::query()->max('popularity');

                $this->UsersNameEncrypt($idol_list);

                return response()->json([
                    'status' => 1,
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
                    'total_pages' => ceil($idol_count / $pageRow_records),
                    'max_popularity' => $max_popularity,
                    'idol_list' => $idol_list
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'total_pages' => 0,
                'max_popularity' => 0,
                'idol_list' => ""
            ], 400);

        }
    }

    /**
     * 對玩家評價
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function like(Request $request)
    {
        try {
            $this->validate($request, [
                'type' => ['required'],
                'opposite_name' => ['required'],
            ]);

            $type = $request->post('type');
            $opposite_name = $this->UserNameDecrypt($request->post('opposite_name'));

            $opposite_game_info = GameInfo::query()->find($opposite_name);
            if (!$opposite_game_info) {
                return response()->json([
                    'status' => 0,
                    'message' => "送出失敗，未有該偶像"
                ], 400);
            }

            switch ($type) {
                case 'like':
                case 'dislike':
                    $like = Like::query()->CreateLike($opposite_name, $type);

                    if (!$like->wasRecentlyCreated)
                        $like->updateType($type);
                    break;
                case 'removelike':
                    $type = null;

                    Like::query()->DeleteLike($opposite_name);
                    break;
                default:
                    return response()->json([
                        'status' => 0,
                        'message' => "送出失敗，無此選項"
                    ], 400);
            }

            $like_num = $opposite_game_info->LikeTypeNum('like');
            $dislike_num = $opposite_game_info->LikeTypeNum('dislike');

            return response()->json([
                'status' => 1,
                'like_select' => $type,
                'opposite_like_num' => $like_num,
                'opposite_dislike_num' => $dislike_num,
                'message' => "送出成功"
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 0,
                'message' => "送出失敗"
            ], 400);
        }
    }

    /**
     * 顯示擁有的偶像
     *
     * @return JsonResponse
     */
    public function own_character()
    {
        $own_character_list = OwnCharacter::query()->OwnCharacterList()->get();

        return response()->json([
            'status' => 1,
            'own_character_list' => $own_character_list
        ]);
    }

    /**
     * 顯示聊天訊息
     *
     * @return JsonResponse
     */
    public function get_chats()
    {

        $total_chat_messages = ChatRoom::query()->Chat_info();

        $show_count = 100;
        $skip_count = max($total_chat_messages->count() - $show_count, 0);

        $chat_messages = $total_chat_messages->skip($skip_count)->take($show_count)->get();

        $this->UsersNameEncrypt($chat_messages);

        return response()->json([
            'status' => 1,
            'chat_messages' => $chat_messages,
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

            $cool_down = CoolDown::query()->CurrentLoginUser();

            $chat_time = $cool_down->chat;

            if ($chat_time > Carbon::now()) {
                $remain_time = $this->remain_time($chat_time, Carbon::now());
                return response()->json([
                    'status' => 0,
                    'chat_time' => $chat_time,
                    'message' => "送出失敗，剩餘時間： $remain_time 秒"
                ], 400);
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
            } catch (Exception $e) {
            }

            return response()->json([
                'status' => 1,
                'chat_time' => $chat_time,
                'message' => "送出成功"
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 0,
                'message' => "送出失敗"
            ], 400);
        }
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
        } catch (Exception $e) {
        }
    }
}
