<?php

use Illuminate\Database\Seeder;

class IngredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Ingredient::class)->create(['name' => 'Cebola']);
        factory(App\Ingredient::class)->create(['name' => 'Tomate']);
        factory(App\Ingredient::class)->create(['name' => 'Camarão']);
        factory(App\Ingredient::class)->create(['name' => 'Carne']);
        factory(App\Ingredient::class)->create(['name' => 'Queijo']);
        factory(App\Ingredient::class)->create(['name' => 'Alface']);
        factory(App\Ingredient::class)->create(['name' => 'Maionese']);
    }
}
