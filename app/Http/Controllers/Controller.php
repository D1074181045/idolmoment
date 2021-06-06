<?php

namespace App\Http\Controllers;

use App\Http\Other\UserNameCrypto;
use App\Models\GameInfo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

define('SIGNATURE_DELAY_T', env('SIGNATURE_DELAY_T', 60));
define('ACTIVITY_DELAY_T', env('ACTIVITY_DELAY_T', 60));
define('COOPERATION_DELAY_T', env('COOPERATION_DELAY_T', 90));
define('CHAT_DELAY_T', env('CHAT_DELAY_T', 30));
define('OPERATING_DELAY_T', env('OPERATING_DELAY_T', 120));

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, UserNameCrypto;

    /**
     * 特殊字元轉換 List
     *
     * @param $data
     * @param string $key
     */
    public function HtmlSpecialChars_List(&$data, string $key) {
        foreach ($data as $index => $value) {
            $data[$index]->{$key} = htmlspecialchars($data[$index]->{$key});
        }
    }

    /**
     * 剩餘時間
     *
     *
     * @param $from
     * @param $to
     * @return int
     */
    public function remain_time($from , $to) {
        return strtotime($from) - strtotime($to);
    }

    /**
     * 取得貼貼狀態
     *
     *
     * @param $GameInfo
     * @return array
     */
    public function teetee_info($GameInfo)
    {
        try {
            if ($teetee = GameInfo::query()->where('nickname', $GameInfo->teetee)->first()) {
                if ($teetee->name !== $GameInfo->name) {
                    return [
                        'teetee_status' => $teetee->teetee === $GameInfo->nickname,
                        'teetee_name' => $teetee->teetee === $GameInfo->nickname ? $this->UserNameEncrypt2($teetee->name) : null,
                        'teetee_graduate' => $teetee->teetee === $GameInfo->nickname ? $teetee->graduate : null,
                    ];
                }
            }

            return [
                'teetee_status' => 0,
                'teetee_name' => null,
                'teetee_graduate' => null,
            ];
        } catch (\Exception $e) {
            return [
                'teetee_status' => 0,
                'teetee_name' => null,
                'teetee_graduate' => null,
            ];
        }
    }
}
