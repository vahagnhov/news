<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text,
        'photo' => $faker->image(),
        'date' => $faker->date,
        'url' => $faker->imageUrl()
    ];
});
