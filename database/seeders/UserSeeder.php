<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()
        ->hasLoans(1)
        ->create([
            'name' => 'Customer One',
            'email' => 'customer1@nomail.com',
        ]);

        \App\Models\User::factory()
        ->hasLoans(1)
        ->create([
            'name' => 'Customer Two',
            'email' => 'customer2@nomail.com',
        ]);
    }
}
