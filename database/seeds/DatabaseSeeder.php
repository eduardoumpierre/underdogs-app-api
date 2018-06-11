<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('CategoriesTableSeeder');
        $this->call('IngredientsTableSeeder');
        $this->call('ProductsTableSeeder');
        $this->call('ProductsIngredientsTableSeeder');

        $this->call('LevelsTableSeeder');

        $this->call('BadgesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('UsersBadgesTableSeeder');

        $this->call('CardsTableSeeder');
        $this->call('DropsTableSeeder');
        $this->call('LevelsDropsTableSeeder');

        $this->call('BillsTableSeeder');
    }
}
