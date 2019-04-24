<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GuestListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            factory(App\GuestList::class)->create();
        }
    }
}
