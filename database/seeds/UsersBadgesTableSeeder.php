<?php

use Illuminate\Database\Seeder;

class UsersBadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\UserBadge::class, 15)->create();
    }
}
