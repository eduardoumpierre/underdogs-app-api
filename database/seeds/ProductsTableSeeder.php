<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Product::class)->create(['name' => 'Hamburguer underdogs']);
        factory(App\Product::class)->create(['name' => 'Hamburguer underdogs supremo']);
        factory(App\Product::class)->create(['name' => 'Bebida underdogs']);
        factory(App\Product::class)->create(['name' => 'Cerveja underdogs']);
        factory(App\Product::class)->create(['name' => 'Batata underdogs']);
    }
}
