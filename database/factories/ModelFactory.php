<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Badge::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'experience' => $faker->numberBetween(25, 100),
    ];
});

$factory->define(App\Bill::class, function (Faker\Generator $faker) {
    return [
        'is_active' => $faker->boolean(),
        'users_id' => DB::table('users')->pluck('id')->random(),
        'cards_id' => DB::table('cards')->pluck('id')->random(),
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Ingredient::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'allergenic' => $faker->boolean()
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(15,35),
        'experience' => $faker->numberBetween(5, 10),
        'categories_id' => DB::table('categories')->pluck('id')->random()
    ];
});

$factory->define(App\ProductIngredient::class, function (Faker\Generator $faker) {
    return [
        'products_id' => DB::table('products')->pluck('id')->random(),
        'ingredients_id' => DB::table('ingredients')->pluck('id')->random()
    ];
});


$factory->define(App\User::class, function (Faker\Generator $faker) {
    $number = $faker->numberBetween(150,2000);

    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->email,
        'password' => app('hash')->make('123'),
        'experience' => $number,
        'levels_id' => DB::table('levels')->where('experience', '<=', $number)->orderByDesc('id')->first()->id,
        'role' => $faker->numberBetween(0, 1),
        'birthday' => $faker->date()
    ];
});

$factory->define(App\UserBadge::class, function (Faker\Generator $faker) {
    return [
        'users_id' => DB::table('users')->pluck('id')->random(),
        'badges_id' => DB::table('badges')->pluck('id')->random()
    ];
});

$factory->define(App\Level::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->unique()->numberBetween(0, 100000),
        'experience' => $faker->unique()->numberBetween(0, 100000)
    ];
});

$factory->define(App\Card::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->unique()->numberBetween(10000, 99999)
    ];
});

$factory->define(App\Drop::class, function (Faker\Generator $faker) {
    return [
        'description' => $faker->name
    ];
});

$factory->define(App\LevelDrop::class, function (Faker\Generator $faker) {
    return [
        'levels_id' => $faker->randomNumber(),
        'drops_id' => DB::table('drops')->pluck('id')->random()
    ];
});

$factory->define(App\Schedule::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'description' => $faker->realText(),
        'date' => $faker->dateTimeBetween('+0 days', '+1 year')
    ];
});

$factory->define(App\Achievement::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'experience' => $faker->numberBetween(50, 200),
        'category' => 0,
        'entity' => DB::table('products')->pluck('id')->random(),
        'value' => $faker->numberBetween(5, 25),
        'drops_id' => DB::table('drops')->pluck('id')->random()
    ];
});
