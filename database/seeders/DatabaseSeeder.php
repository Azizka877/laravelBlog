<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Option;
use App\Models\Property;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(1)->create([
            'email' => 'ka@gmail.com',
        ]);
        $options = Option::factory(10)->create();
        Property::factory(50)->hasAttached($options->random(3))->create();

    }
}
