<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::factory()->create([
            'name' => 'Admin One',
            'email' => 'admin1@nomail.com',
        ]);

        \App\Models\Admin::factory()->create([
            'name' => 'Admin Two',
            'email' => 'admin2@nomail.com',
        ]);
    }
}
