<?php

use Illuminate\Database\Seeder;

class DropsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Drop::class)->create(['description' => 'Desconto de 5%']);
        factory(App\Drop::class)->create(['description' => 'Desconto de 10%']);
        factory(App\Drop::class)->create(['description' => 'Desconto de 15%']);
        factory(App\Drop::class)->create(['description' => 'Camiseta']);
        factory(App\Drop::class)->create(['description' => 'Boné']);
    }
}
