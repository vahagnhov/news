<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->description,
        'photo' => $faker->photo,
        'date' => $faker->date,
        'url' => $faker->url,
        'created_at' => $faker->created_at,
        'updated_at' => $faker->updated_at
    ];
});
