<?php


namespace App\Http\Other;


class activity
{
    private $self = [];

    public function __construct($self_game_info) {
        $this->self['game_info'] = $self_game_info;
        $this->self['character_up_mag'] = $self_game_info->GameCharacter->CharacterUpMag;
    }

    public function adult_live() {
        $popularity_rand = rand(750, 1000);
        $reputation_rand = rand(1000, 2000);
        $resistance_rand = rand(20, 50);
        $charm_rand = rand(10, 20);

        $this->self['game_info']->popularity += ceil($popularity_rand * $this->self['game_info']->charm * 0.05 + $popularity_rand * $this->self['game_info']->energy * 0.005 + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation -= $reputation_rand;
        $this->self['game_info']->resistance += ceil($resistance_rand  * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm -= ceil($charm_rand * $this->self['character_up_mag']->charm);
    }

    public function live() {
        $popularity_rand = rand(50, 500);
        $reputation_rand = rand(100, 500);
        $resistance_rand = rand(10, 25);
        $charm_rand = rand(1, 20);

        $this->self['game_info']->popularity += ceil($popularity_rand * $this->self['game_info']->charm * 0.005 + $popularity_rand * $this->self['game_info']->energy * 0.005 + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation += $reputation_rand;
        $this->self['game_info']->resistance += ceil($resistance_rand  * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm += ceil($charm_rand * $this->self['character_up_mag']->charm);
    }

    public function do_good_things() {
        $popularity_rand = rand(25, 250);
        $reputation_rand = rand(300, 700);
        $resistance_rand = rand(2, 12);
        $charm_rand = rand(2, 40);

        $this->self['game_info']->popularity += ceil($popularity_rand * $this->self['game_info']->charm * 0.004 + $popularity_rand * $this->self['game_info']->energy * 0.005 + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation += $reputation_rand;
        $this->self['game_info']->resistance += ceil($resistance_rand  * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm += ceil($charm_rand * $this->self['character_up_mag']->charm);
    }

    public function go_to_sleep() {
        $recover_vitality_rand = ceil(rand(10, 30) * $this->self['character_up_mag']->vitality * $this->self['game_info']->energy * 0.5);

        $this->self['game_info']->current_vitality += $recover_vitality_rand;
    }

    public function meditation() {
        $energy_rand = rand(20, 50);

        $this->self['game_info']->energy += ceil($energy_rand * $this->self['character_up_mag']->energy);
    }

    public function __destruct() {
        if ($this->self['game_info']->popularity < 1)
            $this->self['game_info']->popularity = 1;
        if ($this->self['game_info']->charm < 1)
            $this->self['game_info']->charm = 1;
        if ($this->self['game_info']->current_vitality > $this->self['game_info']->max_vitality)
            $this->self['game_info']->current_vitality = $this->self['game_info']->max_vitality;

        $this->self['game_info']->save();
    }
}
