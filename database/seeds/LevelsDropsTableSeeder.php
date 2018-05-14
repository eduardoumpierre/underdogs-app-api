<?php

use Illuminate\Database\Seeder;

class LevelsDropsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 99; $i++) {
            for ($x = 0; $x < 3; $x++) {
                factory(App\LevelDrop::class)->create([
                    'levels_id' => $i
                ]);
            }
        }
    }
}
