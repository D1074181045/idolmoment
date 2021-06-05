<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GameInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'game_info';
    protected $primaryKey = 'name';

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'nickname',
        'use_character',
        'popularity',
        'reputation',
        'max_vitality',
        'current_vitality',
        'energy',
        'resistance',
        'charm',
        'rebirth_counter',
        'graduate',
        'signature',
        'teetee'
    ];
    /**
     * @var mixed
     */
    private $use_character;

    /**
     * 檢查是否建立過遊戲資料
     *
     *
     * @param $query
     * @param string $username
     */
    public function scopeUserGameInfoBuilt($query, string $username) {
        return $query->where('name', '=', $username)->count();
    }

    /**
     * 檢查是否已有此暱稱
     *
     *
     * @param $query
     * @param string $nickname
     */
    public function scopeNickNameBuilt($query, string $nickname) {
        $query->where('nickname', '=', $nickname);
    }


    /**
     * 新增遊戲資料
     *
     *
     * @param $query
     * @param array $array
     */
    public function scopeBuildDefaultGameInfo($query, array $array) {
        $name = Arr::get($array, 'name');
        $nickname = Arr::get($array, 'nickname');
        $use_character = Arr::get($array, 'use_character');
        $vitality = Arr::get($array, 'vitality');
        $energy = Arr::get($array, 'energy');
        $resistance = Arr::get($array, 'resistance');
        $charm = Arr::get($array, 'charm');

        return $query->firstOrCreate([
            'name' => $name,
        ], [
            'nickname' => $nickname,
            'use_character' => $use_character,
            'max_vitality' => $vitality,
            'current_vitality' => $vitality,
            'energy' => $energy,
            'resistance' => $resistance,
            'charm' => $charm
        ]);
    }

    public function LikeTypeNum($type) {
        return $this->hasMany('App\Models\Like', 'to_name', 'name')
            ->where('type', $type)->count();
    }

    public function SelectedLikeType($from_name) {
        $like = $this->hasMany('App\Models\Like', 'to_name', 'name')
            ->where('from_name', $from_name)
            ->first();

        if ($like)
            return $like->type;
        else
            return null;
    }

    /**
     * 冷卻
     *
     *
     */
    public function CoolDown()
    {
        return $this->hasOne('App\Models\CoolDown', 'name');
    }

    /**
     * 關聯遊戲角色
     *
     *
     */
    public function GameCharacter() {
        return $this->belongsTo('App\Models\GameCharacter', 'use_character', 'en_name');
    }

    /**
     * 活耀偶像列表
     *
     * @param $query
     * @param $user_popularity
     */
    public function scopeActiveIdol($query, $user_popularity) {
        $query->join('game_characters', 'game_characters.en_name', '=', 'game_info.use_character')
            ->where('popularity' , '<=', $user_popularity)
            ->select('name', 'use_character', 'img_file_name', 'popularity', 'reputation', 'nickname', 'signature', 'tc_name', 'graduate')
            ->orderby('popularity', 'DESC');
    }

    /**
     * 查詢指定偶像
     *
     * @param $query
     * @param string $nickname
     */
    public function scopeSearchName($query, string $nickname) {
        $query->join('game_characters', 'game_characters.en_name', '=', 'game_info.use_character')
            ->where('nickname' , '=', $nickname)
            ->select('name', 'use_character', 'img_file_name', 'popularity', 'reputation', 'nickname', 'signature', 'tc_name', 'graduate')
            ->orderby('popularity', 'DESC');
    }

    /**
     * 查詢指定偶像
     *
     * @param $query
     * @return false
     */
    public function scopeCurrentLoginUser($query) {
        $self_name = Auth::user()->name;

        if ($self_name)
            return $query->find($self_name);
        else
            return false;
    }

    public function update_teetee($teetee) {
        $this->update([
            'teetee' => $teetee
        ]);
    }

    public function update_signature($signature) {
        $this->update([
            'signature' => $signature
        ]);
    }

    public function rebirth($game_character) {
        $this->update([
            'use_character' => $game_character->en_name,
            'popularity' => ceil($this['popularity'] / 10),
            'reputation' => ceil($this['reputation'] / 2),
            'max_vitality' => $game_character->vitality,
            'current_vitality' => $game_character->vitality,
            'energy' => $game_character->energy,
            'resistance' => $game_character->resistance,
            'charm' => $game_character->charm,
            'rebirth_counter' => $this->rebirth_counter + 1,
            'graduate' => false
        ]);
    }
}
