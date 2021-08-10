<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameCharacter extends Model
{
    use HasFactory;

    protected $table = 'game_characters';

    protected $primaryKey = 'en_name';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'en_name',
        'tc_name',
        'img_file_name',
        'vitality',
        'energy',
        'resistance',
        'charm',
        'introduction',
        'created_at',
        'updated_at'
    ];

    /**
     * 遊戲角色倍率
     *
     *
     */
    public function CharacterUpMag() {
        return $this->hasOne('App\Models\CharacterUpMag', 'character_name', 'en_name');
    }
}
