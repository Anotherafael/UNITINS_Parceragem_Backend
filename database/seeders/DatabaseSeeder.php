<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Database\Seeders\Service\SectionSeeder;
use Database\Seeders\Service\ServiceSeeder;
use Database\Seeders\Service\ProfessionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(SectionSeeder::class);
        $this->call(ProfessionSeeder::class);
        $this->call(ServiceSeeder::class);
    }
}
