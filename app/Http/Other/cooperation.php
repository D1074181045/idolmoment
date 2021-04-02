<?php


namespace App\Http\Other;

class cooperation
{
    private $self = [];
    private $teetee = [];

    public function two_judgment($self_character_name, $teetee_character_name, $arr) {
        return ($self_character_name === $arr[0] && $teetee_character_name === $arr[1]) ||
               ($self_character_name === $arr[1] && $teetee_character_name === $arr[0]);
    }

    public function isCombination($self_character_name, $teetee_character_name) {
        switch (true) {
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Inugami Korone', 'Nekomata Okayu']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Usada Pekora', 'Moona Hoshinova']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Usada Pekora', 'Sakuramiko']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Shirogane Noel', 'Shiranui Flare']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Mori Calliope', 'Takanashi Kiara']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Watson Amelia', 'Gawr Gura']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Shirakami Fubuki', 'Natsuiro Matsuri']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Uruha Rushia', 'Kureiji Ollie']):
                return true;
        }
        return false;
    }

    public function __construct($self_game_info, $teetee_game_info) {
        $this->self['game_info'] = $self_game_info;
        $this->self['character_up_mag'] = $self_game_info->GameCharacter->CharacterUpMag;

        $this->teetee['game_info'] = $teetee_game_info;
        $this->teetee['character_up_mag'] = $teetee_game_info->GameCharacter->CharacterUpMag;
    }

    public function play_ordinary_game() {
        $popularity_rand = rand(200, 400);
        $reputation_rand = rand(150, 500);
        $resistance_rand = rand(20, 40);
        $charm_rand = rand(2, 25);

        if ($this->isCombination($this->self['game_info']->use_character, $this->teetee['game_info']->use_character)) {
            $popularity_rand += $popularity_rand * 0.1;
            $reputation_rand += $reputation_rand * 0.1;
            $resistance_rand += $resistance_rand * 0.1;
            $charm_rand += $charm_rand * 0.1;
        }

        $this->self['game_info']->popularity += ceil($popularity_rand * $this->self['game_info']->charm * 0.02 + $popularity_rand * $this->self['game_info']->energy * 0.005 + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation += ceil($reputation_rand);
        $this->self['game_info']->resistance += ceil($resistance_rand  * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm += ceil($charm_rand * $this->self['character_up_mag']->charm);

        $this->teetee['game_info']->popularity += ceil($popularity_rand * $this->teetee['game_info']->charm * 0.02 + $popularity_rand * $this->teetee['game_info']->energy * 0.005 + $this->teetee['game_info']->reputation * 0.2);
        $this->teetee['game_info']->reputation += ceil($reputation_rand);
        $this->teetee['game_info']->resistance += ceil($resistance_rand  * $this->teetee['character_up_mag']->resistance);
        $this->teetee['game_info']->charm += ceil($charm_rand * $this->teetee['character_up_mag']->charm);
    }

    public function play_tacit_game() {
        $popularity_rand = rand(150, 300);
        $reputation_rand = rand(100, 300);
        $resistance_rand = rand(10, 25);
        $charm_rand = rand(1, 20);

        if ($this->isCombination($this->self['game_info']->use_character, $this->teetee['game_info']->use_character)) {
            $popularity_rand += $popularity_rand * 5;
            $reputation_rand += $reputation_rand * 5;
            $resistance_rand += $resistance_rand * 1.5;
            $charm_rand += $charm_rand * 1.9;
        }

        $this->self['game_info']->popularity += ceil($popularity_rand * $this->self['game_info']->charm * 0.02 + $popularity_rand * $this->self['game_info']->energy * 0.005 + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation += ceil($reputation_rand);
        $this->self['game_info']->resistance += ceil($resistance_rand  * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm += ceil($charm_rand * $this->self['character_up_mag']->charm);

        $this->teetee['game_info']->popularity += ceil($popularity_rand * $this->teetee['game_info']->charm * 0.02 + $popularity_rand * $this->teetee['game_info']->energy * 0.005 + $this->teetee['game_info']->reputation * 0.2);
        $this->teetee['game_info']->reputation += ceil($reputation_rand);
        $this->teetee['game_info']->resistance += ceil($resistance_rand  * $this->teetee['character_up_mag']->resistance);
        $this->teetee['game_info']->charm += ceil($charm_rand * $this->teetee['character_up_mag']->charm);
    }

    public function __destruct() {
        if ($this->self['game_info']->popularity < 1)
            $this->self['game_info']->popularity = 1;
        if ($this->teetee['game_info']->popularity < 1)
            $this->teetee['game_info']->popularity = 1;

        $this->self['game_info']->save();
        $this->teetee['game_info']->save();
    }
}
