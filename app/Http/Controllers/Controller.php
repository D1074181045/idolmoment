<?php

namespace App\Http\Controllers;

use App\Models\GameInfo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Vinkla\Hashids\Facades\Hashids;

define('SIGNATURE_DELAY_T', env('SIGNATURE_DELAY_T', 60));
define('ACTIVITY_DELAY_T', env('ACTIVITY_DELAY_T', 60));
define('CHAT_DELAY_T', env('ACTIVITY_DELAY_T', 90));
define('OPERATING_DELAY_T', env('ACTIVITY_DELAY_T', 180));

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
     * 使用者名稱解密
     *
     * @param string $hex
     * @return string
     */
    public static function UserNameDecrypt(string $hex) {
        return hex2bin(Hashids::decodeHex($hex));
    }

    /**
     * 使用者名稱加密(多)
     *
     * @param $data
     * @param string $key
     */
    public function UsersNameEncrypt(&$data, string $key = 'name') {
        foreach ($data as $index => $value) {
            $data[$index]->{$key} = Hashids::encodeHex(bin2hex($data[$index]->{$key}));
        }
    }

    /**
     * 使用者名稱加密(一)
     *
     * @param $data
     * @param string $key
     */
    public function UserNameEncrypt(&$data, string $key = 'name') {
        $data->{$key} = Hashids::encodeHex(bin2hex($data->{$key}));
    }

    /**
     * 使用者名稱加密(一)
     *
     * @param $data
     * @return string
     */
    public static function UserNameEncrypt2($data) {
        return Hashids::encodeHex(bin2hex($data));
    }

    /**
     * 取得Cookie
     *
     *
     * @param string $key
     * @return string
     */
    public static function getCookie(string $key)
    {
        if (Session::has($key))
            $username = Session::get($key, false);
        else
            $username = Cookie::get($key, false);

        return $username;
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
     * @return int[]
     */
    public function teetee_info($GameInfo) {

        try {
            if ($teetee = GameInfo::query()->where('nickname', $GameInfo->teetee)->first()){
                if ($teetee->name !== $GameInfo->name) {
                    return [
                        'status' => $teetee->teetee === $GameInfo->nickname,
                        'self_name' => $this->UserNameEncrypt2($GameInfo->name),
                        'self_character' => $GameInfo->GameCharacter->en_name,
                        'teetee_name' => $this->UserNameEncrypt2($teetee->name),
                        'teetee_character' => $teetee->GameCharacter->en_name
                    ];
                }
            }
        } catch (\Exception $e) {
            return [
                'status' => 0,
                'teetee_name' => null,
            ];
        }

        return [
            'status' => 0,
            'teetee_name' => null,
        ];
    }
}
