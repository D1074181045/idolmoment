<?php


namespace App\Http\Other;

use App\Models\CharacterUpMag;
use App\Models\GameInfo;

class operating
{
    private $self = [];
    private $opposite = [];

    public function __construct($opposite_game_info, $self_game_info) {
        $this->opposite['game_info'] = $opposite_game_info;
        $this->opposite['character_up_mag'] = $opposite_game_info->GameCharacter->CharacterUpMag;
        $this->opposite['nickname'] = '<div style="color: #298fe2; padding-left: 10px; padding-right: 10px;">' . $opposite_game_info->nickname . '</div>';

        $this->self['game_info'] = $self_game_info;
        $this->self['character_up_mag'] = $self_game_info->GameCharacter->CharacterUpMag;
        $this->self['nickname'] = '<div style="color: #298fe2; padding-left: 10px; padding-right: 10px;">' . $self_game_info->nickname . '</div>';
    }

    public function send_blade() {
        $resistance = $this->opposite['game_info']->resistance;
        $attack_vitality_number = round(rand(100, 500) * 10 / $resistance);

        $this->opposite['game_info']->current_vitality -= $attack_vitality_number;
        $this->opposite['game_info']->energy -= round(rand(20, 40) * $this->opposite['character_up_mag']->energy);

        if ($this->opposite['game_info']->current_vitality <= 0) {
            $this->opposite['game_info']->current_vitality = 0;
            $this->opposite['game_info']->graduate = true;
            return '偶像' . $this->opposite['nickname'] . '因承受不住壓力而宣布退役。';
        }

        return '偶像' . $this->opposite['nickname'] . '因心靈受創，生命值降低了。';
    }

    public function endorse($self_info) {

    }

    public function __destruct() {
        if ($this->opposite['game_info']->energy < 1)
            $this->opposite['game_info']->energy = 1;

        $this->opposite['game_info']->save();
    }
}
