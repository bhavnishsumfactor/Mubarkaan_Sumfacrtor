<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use App\Models\Country;
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

$countryIds = Country::where('country_publish', config('constants.YES'))->get()->pluck('country_id')->toArray();

$factory->define(User::class, function (Faker $faker) use ($countryIds) {
    $genderArr = ['1' => 'male', '2' => 'female'];
    $gender = $faker->randomElement($genderArr);
    return [
        'user_fname' => $faker->firstName($gender),
        'user_lname' => $faker->lastName,
        'user_dob' => $faker->unique()->dateTimeBetween('-60 years', '-20 years')->format('d-m-Y'),
        'user_gender' => array_flip($genderArr)[$gender],
        'user_email' => $faker->unique()->safeEmail,
        'user_phone_country_id' => $faker->randomElement($countryIds),
        'user_phone' => $faker->phoneNumber,
        'user_password' => bcrypt('123456'),
        'user_phone_verified' => config('constants.YES'),
        'user_email_verified' => config('constants.YES'),
        'user_publish' => config('constants.YES'),
        'user_created_at' => now(),
        'user_updated_at' => now()
    ];
});
