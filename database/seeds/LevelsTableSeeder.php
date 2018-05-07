<?php

use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 99; $i++) {
            factory(App\Level::class)->create([
                'number' => $i,
                'experience' => $i == 1 ? 0 : (($i - 1) * 125) * (1 + (($i - 1) * 0.20))
            ]);
        }
    }
}
