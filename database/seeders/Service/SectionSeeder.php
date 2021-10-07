<?php

namespace Database\Seeders\Service;

use Illuminate\Support\Str;
use App\Models\Service\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            'Saúde', 
            'Educação', 
        ];

        foreach ($sections as $value) {
            $section = Section::create([
                'id' => Str::uuid(),
                'name' => $value,
            ]);
        }

        $this->command->info(("Sections created"));
    }
}
