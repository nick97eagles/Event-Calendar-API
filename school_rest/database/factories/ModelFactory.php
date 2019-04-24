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

$factory->define(App\Calendar::class, function (Faker\Generator $faker) {
    return [
       // 'event_id' => $faker->randomNumber($nbDigits=4, $strict=false)
        'event_name' => $faker->jobTitle,
        'event_desc' => $faker->bs,
        'location'  => $faker->address,
        'created_at' => $faker->dateTimeBetween('-3 years', 'now'),
        'event_date' => $faker->dateTimeBetween('-1 years'),
        'hosted_by' => $faker->company,
        'updated_at' => $faker->dateTimeBetween('-1 years')
    ];
});

$factory->define(App\GuestList::class, function (Faker\Generator $faker) {
    return [
       // 'event_id' => $faker->randomNumber($nbDigits=4, $strict=false)
        'student_name' => $faker->name,
        'event_id' => $faker->numberBetween($min=0 , $max=100),
        'phone_number' => $faker->phoneNumber,
    ];
});
