<?php

namespace Tests\Fixtures\Factories;

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\Tests\Fixtures\Models\TestModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'encrypt_string' => $faker->safeEmail,
        'encrypt_integer' => $faker->numberBetween(0, 99),
        'encrypt_boolean' => $faker->boolean,
        'encrypt_boolean2' => $faker->boolean,
        'encrypt_float' => $faker->randomFloat(0, 99),
        'encrypt_date' => $faker->date('Y-m-d H:i:s'),
        'hash_string' => $faker->text(200)
    ];
});
