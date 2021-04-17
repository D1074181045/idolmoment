<?php


namespace App\Http\Other;


class activity
{
    private $self = [];

    /**
     * 載入自己的資料
     *
     * @param $self_game_info
     */
    public function __construct($self_game_info) {
        $this->self['game_info'] = $self_game_info;
        $this->self['character_up_mag'] = $self_game_info->GameCharacter->CharacterUpMag;
    }

    /**
     * 成人直播
     *
     */
    public function adult_live() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 人氣產生值：(750~1000)
         * 名聲產生值：(1000~2000)
         * 抗壓性產生值：(20~50)
         * 魅力產生值：(10~20)
         * ------------------------------------------------------------------
         * 人氣：人氣 + 人氣產生值 * ( 魅力 * 0.05 + 精力 * 0.005 ) + 名聲 * 0.2
         * 名聲：名聲 - 名聲產生值
         * 抗壓性：抗壓性 + 抗壓性產生值 + 偶像抗壓性成長係數
         * 魅力：魅力 - 魅力產生值 * 偶像魅力成長係數
         * ------------------------------------------------------------------
         * */

        $popularity_rand = rand(750, 1000);
        $reputation_rand = rand(1000, 2000);
        $resistance_rand = rand(20, 50);
        $charm_rand = rand(10, 20);

        $this->self['game_info']->popularity += ceil($popularity_rand * ( $this->self['game_info']->charm * 0.05 + $this->self['game_info']->energy * 0.005 ) + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation -= $reputation_rand;
        $this->self['game_info']->resistance += ceil($resistance_rand * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm -= ceil($charm_rand * $this->self['character_up_mag']->charm);
    }

    /**
     * 直播
     *
     */
    public function live() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 人氣產生值：(50~500)
         * 名聲產生值：(100~500)
         * 抗壓性產生值：(10~25)
         * 魅力產生值：(1~20)
         * ------------------------------------------------------------------
         * 人氣：人氣 + 人氣產生值 * 0.005 * ( 魅力 + 精力 ) + 名聲 * 0.2
         * 名聲：名聲 + 名聲產生值
         * 抗壓性：抗壓性 + 抗壓性產生值 + 偶像抗壓性成長係數
         * 魅力：魅力 + 魅力產生值 * 偶像魅力成長係數
         * ------------------------------------------------------------------
         * */

        $popularity_rand = rand(50, 500);
        $reputation_rand = rand(100, 500);
        $resistance_rand = rand(10, 25);
        $charm_rand = rand(1, 20);

        $this->self['game_info']->popularity += ceil($popularity_rand * 0.005 * ( $this->self['game_info']->charm + $this->self['game_info']->energy ) + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation += $reputation_rand;
        $this->self['game_info']->resistance += ceil($resistance_rand  * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm += ceil($charm_rand * $this->self['character_up_mag']->charm);
    }

    /**
     * 做善事
     *
     */
    public function do_good_things() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 人氣產生值：(25~150)
         * 名聲產生值：(300~700)
         * 抗壓性產生值：(2~12)
         * 魅力產生值：(2~40)
         * ------------------------------------------------------------------
         * 人氣：人氣 + 人氣產生值 * ( 魅力 * 0.004 + 精力 * 0.005 ) + 名聲 * 0.2
         * 名聲：名聲 + 名聲產生值
         * 抗壓性：抗壓性 + 抗壓性產生值 + 偶像抗壓性成長係數
         * 魅力：精力 + 魅力產生值 * 偶像魅力成長係數
         * ------------------------------------------------------------------
         * */

        $popularity_rand = rand(25, 150);
        $reputation_rand = rand(300, 700);
        $resistance_rand = rand(2, 12);
        $charm_rand = rand(2, 40);

        $this->self['game_info']->popularity += ceil($popularity_rand * ( $this->self['game_info']->charm * 0.004 + $this->self['game_info']->energy * 0.005 ) + $this->self['game_info']->reputation * 0.2);
        $this->self['game_info']->reputation += $reputation_rand;
        $this->self['game_info']->resistance += ceil($resistance_rand  * $this->self['character_up_mag']->resistance);
        $this->self['game_info']->charm += ceil($charm_rand * $this->self['character_up_mag']->charm);
    }

    /**
     * 睡覺
     *
     */
    public function go_to_sleep() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 目前生命值：目前生命值 + (10~30) + 偶像生命值成長係數 * 精力 * 0.5
         * ------------------------------------------------------------------
         * */

        $recover_vitality_rand = ceil(rand(10, 30) + $this->self['character_up_mag']->vitality * $this->self['game_info']->energy * 0.5);

        $this->self['game_info']->current_vitality += $recover_vitality_rand;
    }

    /**
     * 打坐
     *
     */
    public function meditation() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 精力產生值：(50~150)
         * ------------------------------------------------------------------
         * 精力：精力 + 精力產生值 * 偶像精力成長係數
         * ------------------------------------------------------------------
         * */

        $energy_rand = rand(30, 60);

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
