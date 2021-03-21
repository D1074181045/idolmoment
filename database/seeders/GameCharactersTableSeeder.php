<?php


namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameCharactersTableSeeder extends Seeder
{
    function CsvInsert($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return;

        $header = null;

        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                DB::table('game_characters')->insert([
                        'en_name' => trim($data[0]),
                        'tc_name' => trim($data[1]),
                        'img_file_name' => trim($data[2]),
                        'vitality' => trim($data[3]),
                        'energy' => trim($data[4]),
                        'resistance' => trim($data[5]),
                        'charm' => trim($data[6]),
                        'introduction' => trim($data[7]),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                ]);
            }
            fclose($handle);
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = base_path().'/database/seeders/csvs/Game_Characters.csv';

        $this->CsvInsert($file);
    }
}
