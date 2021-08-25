<?php


namespace App\PlayAbilityOperation;


trait PatchSaveData
{
    /**
     * 修正資料儲存
     *
     * @param $arr
     * @param $data
     */
    protected function patch_save_data($arr, $data) {
        foreach ($arr as $value) {
            if ($data->{$value}['game_info']->popularity < 1)
                $data->{$value}['game_info']->popularity = 1;
            if ($data->{$value}['game_info']->reputation == 0)
                $data->{$value}['game_info']->reputation = 1;
            if ($data->{$value}['game_info']->current_vitality > $data->{$value}['game_info']->max_vitality)
                $data->{$value}['game_info']->current_vitality = $data->{$value}['game_info']->max_vitality;
            if ($data->{$value}['game_info']->energy < 1)
                $data->{$value}['game_info']->energy = 1;
            if ($data->{$value}['game_info']->resistance < 1)
                $data->{$value}['game_info']->resistance = 1;
            if ($data->{$value}['game_info']->charm < 1)
                $data->{$value}['game_info']->charm = 1;

            $data->{$value}['game_info']->save();
        }
    }
}
