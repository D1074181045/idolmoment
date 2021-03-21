<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\user::factory(10)->create();
//        $this->call(GameCharactersTableSeeder::class);
//        $this->call(CharactersUpMagTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(OwnCharactersTableSeeder::class);
        $this->call(GameInfoTableSeeder::class);
        $this->call(OperatingCoolDownSeeder::class);
    }
}
