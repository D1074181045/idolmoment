<?php


namespace App\PlayAbilityOperation;

class Operating
{
    use PatchSaveData;

    public $self = [];
    public $opposite = [];

    /**
     * 載入資料
     *
     * @param $opposite_game_info
     * @param $self_game_info
     */
    public function __construct($opposite_game_info, $self_game_info) {
        $this->opposite['game_info'] = $opposite_game_info;
        $this->opposite['character_up_mag'] = $opposite_game_info->GameCharacter->CharacterUpMag;
        $this->opposite['nickname'] = '<div style="color: #298fe2; padding-left: 10px; padding-right: 10px;">' . $opposite_game_info->nickname . '</div>';

        $this->self['game_info'] = $self_game_info;
        $this->self['character_up_mag'] = $self_game_info->GameCharacter->CharacterUpMag;
        $this->self['nickname'] = '<div style="color: #298fe2; padding-left: 10px; padding-right: 10px;">' . $self_game_info->nickname . '</div>';
    }

    /**
     * 寄刀片
     *
     * @return string
     */
    public function send_blade() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 對方目前生命值：對方目前生命值 - (100~300) * 100 / ( 對方的抗壓性 + 對方的精力 )
         * 對方精力：對方精力 - (20~40) / 對方偶像精力成長係數
         * ------------------------------------------------------------------
         * */

        $resistance = $this->opposite['game_info']->resistance;
        $energy = $this->opposite['game_info']->energy;

        $this->opposite['game_info']->current_vitality -= ceil(rand(100, 300) * 100 / ($resistance + $energy));
        $this->opposite['game_info']->energy -= ceil(rand(50, 100) / $this->opposite['character_up_mag']->energy);

        if ($this->opposite['game_info']->current_vitality <= 0) {
            $this->opposite['game_info']->current_vitality = 0;
            $this->opposite['game_info']->graduate = true;
            return '偶像' . $this->opposite['nickname'] . '因承受不住壓力而宣布退役。';
        }

        return '偶像' . $this->opposite['nickname'] . '因心靈受創，生命值降低了。';
    }

    /**
     * 抹黑
     *
     * @return string
     */
    public function defame() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 抗壓性產生值:(20~50)
         * ------------------------------------------------------------------
         * 名聲負
         * 人氣產生值:(30000~50000)
         * 名聲產生值:(300~500)
         * ------------------------------------------------------------------
         * 名聲正
         * 人氣產生值:(10000~20000)
         * 名聲產生值:(100~200)
         * ------------------------------------------------------------------
         * 對方人氣：對方人氣 - 人氣產生值
         * 對方名聲：對方名聲 - 名聲產生值
         * 對方抗壓性：對方抗壓性 - 抗壓性產生值 / 對方偶像抗壓性成長係數
         * ------------------------------------------------------------------
         * */

        $reputation = $this->opposite['game_info']->reputation;
        $resistance = $this->opposite['game_info']->resistance;
        $energy = $this->opposite['game_info']->energy;

        if ($reputation <= -1) {
            $popularity_rand = rand(30000, 50000);
            $reputation_rand = rand(300, 500);

            switch ($reputation) {
                case $reputation <= -50000:
                    $popularity_rand *= 2.25;
                    $reputation_rand *= 2.25;
                    break;
                case $reputation <= -40000:
                    $popularity_rand *= 2;
                    $reputation_rand *= 2;
                    break;
                case $reputation <= -30000:
                    $popularity_rand *= 1.75;
                    $reputation_rand *= 1.75;
                    break;
                case $reputation <= -20000:
                    $popularity_rand *= 1.5;
                    $reputation_rand *= 1.5;
                    break;
                case $reputation <= -10000:
                    $popularity_rand *= 1.25;
                    $reputation_rand *= 1.25;
                    break;
                case $reputation <= -1:
                    $popularity_rand *= 1;
                    $reputation_rand *= 1;
                    break;
            }
        }
        else {
            $popularity_rand = rand(10000, 20000);
            $reputation_rand = rand(100, 200);

            switch ($reputation) {
                case $reputation >= 50000:
                    $popularity_rand *= 0.5;
                    $reputation_rand *= 0.5;
                    break;
                case $reputation >= 40000:
                    $popularity_rand *= 0.75;
                    $reputation_rand *= 0.75;
                    break;
                case $reputation >= 30000:
                    $popularity_rand *= 1;
                    $reputation_rand *= 1;
                    break;
                case $reputation >= 10000:
                    $popularity_rand *= 1.25;
                    $reputation_rand *= 1.25;
                    break;
                case $reputation >= 5000:
                    $popularity_rand *= 1.5;
                    $reputation_rand *= 1.5;
                    break;
                case $reputation >= 1000:
                    $popularity_rand *= 1.75;
                    $reputation_rand *= 1.75;
                    break;
                case $reputation >= 1:
                    $popularity_rand *= 2;
                    $reputation_rand *= 2;
                    break;
            }
        }

        $this->opposite['game_info']->popularity -= ceil($popularity_rand);
        $this->opposite['game_info']->reputation -= ceil($reputation_rand);

        $resistance_rand = rand(20, 50);
        switch ($add = $resistance + $energy) {
            case ($add >= 5000):
                $resistance_rand *= 1;
                break;
            case ($add >= 4000):
                $resistance_rand *= 1.25;
                break;
            case ($add >= 3000):
                $resistance_rand *= 1.5;
                break;
            case ($add >= 2000):
                $resistance_rand *= 1.75;
                break;
            case ($add >= 1000):
                $resistance_rand *= 2;
                break;
            case ($add >= 1):
                $resistance_rand *= 2.25;
                break;
        }

        $this->opposite['game_info']->resistance -= ceil($resistance_rand / $this->opposite['character_up_mag']->resistance);

        return '偶像' . $this->opposite['nickname'] . '傳出了負面消息';
    }

    /**
     * 聲援
     *
     * @return string
     */
    public function endorse() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 人氣產生值：(400~800)
         * 名聲產生值：(100~500)
         * 魅力產生值：(10~20)
         * ------------------------------------------------------------------
         * 雙方各自人氣：雙方各自人氣 + 人氣產生值 * ( 雙方各自魅力 * 0.01 + 雙方各自精力 * 0.005 ) + 雙方各自名聲 * 0.2
         * 雙方各自名聲：雙方各自名聲 + 名聲產生值
         * 雙方各自魅力：雙方各自魅力 + 魅力產生值 * 雙方各自偶像魅力成長係數
         * ------------------------------------------------------------------
         * */

        $popularity_rand = rand(400, 800);
        $reputation_rand = rand(100, 500);
        $charm_rand = rand(10, 20);

        $this->self['game_info']->popularity += ceil($popularity_rand * ( $this->self['game_info']->charm * 0.01 + $this->self['game_info']->energy * 0.005 ) + $this->self['game_info']->reputation * 0.2);
        $this->opposite['game_info']->popularity += ceil($popularity_rand * ( $this->opposite['game_info']->charm * 0.01 + $this->opposite['game_info']->energy * 0.005 ) + $this->opposite['game_info']->reputation * 0.2);

        $this->self['game_info']->reputation += $reputation_rand;
        $this->opposite['game_info']->reputation += $reputation_rand;

        $this->self['game_info']->charm += ceil($charm_rand * $this->self['character_up_mag']->charm);
        $this->opposite['game_info']->charm += ceil($charm_rand * $this->opposite['character_up_mag']->charm);

        return '你聲援了偶像' . $this->opposite['nickname'] . '，雙方人氣魅力與名聲提升了';
    }

    /**
     * 斗內
     *
     * @return string
     */
    public function donate() {
        /*
         * 能力係數加權
         * ------------------------------------------------------------------
         * 名聲產生值：(300~700)
         * ------------------------------------------------------------------
         * 對方精力：對方精力 + (20~40) * 自己偶像魅力成長係數
         * 自己名聲：自己名聲 + 名聲產生值
         * ------------------------------------------------------------------
         * */

        $reputation_rand = rand(300, 700);

        $this->opposite['game_info']->energy += ceil(rand(20, 100) * $this->opposite['character_up_mag']->energy);
        $this->self['game_info']->reputation += $reputation_rand;

        return '你斗內了偶像' . $this->opposite['nickname'] . '，對方更有精力了';
    }

    public function __destruct() {
        $arr = ['self', 'opposite'];

        $this->patch_save_data($arr, $this);
    }
}
