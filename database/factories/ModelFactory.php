<?php

use App\Game;
use App\Order;
use App\User;
use App\UserBalance;
use Faker\Generator as Faker;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => time(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => str_random(10),
        'api_token' => str_random(80),
    ];
});

$factory->define(UserBalance::class, function (Faker $faker) {
    return [
        'balance' => $faker->randomElement([10,25,50])
    ];
});

$factory->define(Order::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->define(Game::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->name,
        'publisher' => $faker->unique()->name,
        'release_date' => $faker->dateTimeThisDecade($max = '-2 years', null),
        'image_url' => $faker->unique()->imageUrl(640, 640),
        'price' => $faker->randomNumber(3)+1,
        'description' => $faker->text,
    ];
});
