<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
      'id' => Uuid::generate(5, $faker->name, Uuid::NS_DNS)->string,
      'name' => $faker->name,
      'first_name' => 'Joseph',
      'last_name' => 'Silverman',
      'email' => $faker->unique()->safeEmail,
      'password' => $password ?: $password = bcrypt('secret'),
      'remember_token' => str_random(10),
    ];
});
