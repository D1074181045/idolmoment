<?php


namespace App\Http\Other;

use Vinkla\Hashids\Facades\Hashids;

trait UserNameCrypto
{
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
}
