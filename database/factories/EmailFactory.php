<?php

use Faker\Generator as Faker;
use Webpatser\Uuid\Uuid;

$factory->define(App\Email::class, function (Faker $faker) {
  return [
    'id' => Uuid::generate(5, $faker->name, Uuid::NS_DNS)->string,
    'user_id' => Uuid::generate(4)->string,
    'email_address' => 'email@test.com',
    'is_default' => 0
  ];
});
