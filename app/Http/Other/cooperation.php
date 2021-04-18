<?php


namespace App\Http\Other;

class cooperation
{
    public $self = [];
    public $teetee = [];

    public function two_judgment($self_character_name, $teetee_character_name, $arr) {
        return ($self_character_name === $arr[0] && $teetee_character_name === $arr[1]) ||
            ($self_character_name === $arr[1] && $teetee_character_name === $arr[0]);
    }

    /**
     * 羈絆檢查
     *
     * @param $self_character_name
     * @param $teetee_character_name
     * @return bool
     */
    public function isFetters($self_character_name, $teetee_character_name) {
        switch (true) {
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Inugami Korone', 'Nekomata Okayu']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Usada Pekora', 'Moona Hoshinova']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Usada Pekora', 'Sakuramiko']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Shirogane Noel', 'Shiranui Flare']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Mori Calliope', 'Takanashi Kiara']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Watson Amelia', 'Gawr Gura']):
            case $this->two_judgment($self_character_name, $teetee_character_name, ['Shirakami Fubuki', 'Natsuiro Matsuri']):
                return true;
        }
        return false;
    }

    /**
     * 載入資料
     *
     * @param $self_game_info
     * @param $teetee_game_info
     */
    public function __construct($self_game_info, $teetee_game_info) {
        $this->self['game_info'] = $self_game_info;
        $this->self['character_up_mag'] = $self_game_info->GameCharacter->CharacterUpMag;

        $this->teetee['game_info'] = $teetee_game_info;
        $this->teetee['character_up_mag'] = $teetee_game_info->GameCharacter->CharacterUpMag;
    }

    /**
     * 玩普通遊戲
     *
     */
    public function play_ordinary_game() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 人氣產生值：(200~400) 羈絆+10%
         * 魅力平均:(自己魅力 + 對方魅力) / 2
         * 精力平均:(自己精力 + 對方精力) / 2
         * 名聲平均:(自己名聲 + 對方名聲) / 2
         * ------------------------------------------------------------------
         * 雙方各自人氣：雙方各自人氣 + 人氣產生值 * ( 魅力平均 * 0.02 + 精力平均 * 0.005 ) + 名聲平均 * 0.2
         * ------------------------------------------------------------------
         * */

        $popularity_rand = rand(200, 400);

        if ($this->isFetters($this->self['game_info']->use_character, $this->teetee['game_info']->use_character)) {
            $popularity_rand += $popularity_rand * 0.1;
        }

        $ave_charm = ($this->self['game_info']->charm + $this->teetee['game_info']->charm) / 2;
        $ave_energy = ($this->self['game_info']->energy + $this->teetee['game_info']->energy) / 2;
        $ave_reputation = ($this->self['game_info']->reputation + $this->teetee['game_info']->reputation) / 2;

        $this->self['game_info']->popularity += ceil($popularity_rand * ( $ave_charm * 0.02 + $ave_energy * 0.005 ) + $ave_reputation * 0.2);
        $this->teetee['game_info']->popularity += ceil($popularity_rand * ( $ave_charm * 0.02 + $ave_energy * 0.005 ) + $ave_reputation * 0.2);
    }

    /**
     * 玩默契遊戲
     *
     */
    public function play_tacit_game() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 人氣產生值：(150~300) 羈絆 5倍
         * 魅力平均:(自己魅力 + 對方魅力) / 2
         * 精力平均:(自己精力 + 對方精力) / 2
         * 名聲平均:(自己名聲 + 對方名聲) / 2
         * ------------------------------------------------------------------
         * 雙方各自人氣：雙方各自人氣 + 人氣產生值 * ( 魅力平均 * 0.02 + 精力平均 * 0.005 ) + 名聲平均 * 0.2
         * ------------------------------------------------------------------
         * */

        $popularity_rand = rand(150, 300);

        if ($this->isFetters($this->self['game_info']->use_character, $this->teetee['game_info']->use_character)) {
            $popularity_rand += $popularity_rand * 5;
        }

        $ave_charm = ($this->self['game_info']->charm + $this->teetee['game_info']->charm) / 2;
        $ave_energy = ($this->self['game_info']->energy + $this->teetee['game_info']->energy) / 2;
        $ave_reputation = ($this->self['game_info']->reputation + $this->teetee['game_info']->reputation) / 2;

        $this->self['game_info']->popularity += ceil($popularity_rand * ( $ave_charm * 0.02 + $ave_energy * 0.005 ) + $ave_reputation * 0.2);
        $this->teetee['game_info']->popularity += ceil($popularity_rand * ( $ave_charm * 0.02 + $ave_energy * 0.005 ) + $ave_reputation * 0.2);
    }

    public function __destruct() {
        $arr = ['self', 'teetee'];

        other_fc::patch_save_data($arr, $this);
    }
}
