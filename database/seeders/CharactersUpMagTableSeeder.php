<?php


namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharactersUpMagTableSeeder extends Seeder
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
                DB::table('characters_up_mag')->insert([
                        'character_name' => trim($data[0]),
                        'vitality' => trim($data[1]),
                        'energy' => trim($data[2]),
                        'resistance' => trim($data[3]),
                        'charm' => trim($data[4]),
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
        $file = base_path().'/database/seeders/csvs/Characters_Up_Mag.csv';

        $this->CsvInsert($file);
    }
}
