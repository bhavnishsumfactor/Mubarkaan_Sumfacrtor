<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Option;
use Faker\Generator as Faker;

$yesOrNo = array_flip(config('constants.YES_OR_NO'));
$factory->define(Option::class, function (Faker $faker) use ($yesOrNo) {
    return [
        'option_name' => $faker->unique()->word,
        'option_is_color' => $faker->randomElement($yesOrNo),
        'option_has_image' => $faker->randomElement($yesOrNo),
        'option_has_sizechart' => config('constants.NO')
    ];
});
