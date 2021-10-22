<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Service\SectionSeeder;
use Database\Seeders\Service\TaskSeeder;
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
        $this->call(TaskSeeder::class);
    }
}
