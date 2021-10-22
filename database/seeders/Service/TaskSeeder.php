<?php

namespace Database\Seeders\Service;

use Illuminate\Support\Str;
use App\Models\Service\Task;
use Illuminate\Database\Seeder;
use App\Models\Service\Profession;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profession = Profession::where('name', '=', 'Psícólogo')->first();

        $services = [
            'Ansiedade', 
            'Depressão', 
            'Terapia', 
        ];

        foreach ($services as $value) {
            $service = Task::create([
                'id' => Str::uuid(),
                'name' => $value,
                'profession_id' => $profession->id
            ]);
        }

        $this->command->info(("Services created"));
    }
}
