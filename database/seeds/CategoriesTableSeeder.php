<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Category::class)->create(['name' => 'Softdrinks']);
        factory(App\Category::class)->create(['name' => 'Hamburguers']);
        factory(App\Category::class)->create(['name' => 'Petiscos']);
        factory(App\Category::class)->create(['name' => 'Lanches']);
    }
}
