<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 1; $i <= 100; $i++) {
            DB::table('game_info')->insert([
                'name' => "test" . $i,
                'nickname' => 'test' . $i,
                'use_character' => 'Minato Aqua',
                'popularity' => rand(1, 99999),
                'reputation' => rand(-10000, 99999),
                'max_vitality' => rand(1, 1000),
                'current_vitality' => rand(1, 1000),
                'energy' => rand(1, 1000),
                'resistance' => rand(1, 2000),
                'charm' => rand(1, 2000),
                'rebirth_counter' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
