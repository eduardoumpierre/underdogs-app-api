<?php

use Illuminate\Database\Seeder;

class ProductsIngredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ProductIngredient::class, 15)->create();
    }
}
