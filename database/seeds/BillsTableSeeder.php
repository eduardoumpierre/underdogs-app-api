<?php

use Illuminate\Database\Seeder;

class BillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Bill::class, 3)->create(['is_active' => 1]);
        factory(App\Bill::class, 15)->create();
    }
}
