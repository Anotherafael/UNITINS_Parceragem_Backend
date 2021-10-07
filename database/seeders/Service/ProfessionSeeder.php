<?php

namespace Database\Seeders\Service;

use Illuminate\Support\Str;
use App\Models\Service\Section;
use Illuminate\Database\Seeder;
use App\Models\Service\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $section = Section::where('name', '=', 'Saúde')->first();

        $professions = [
            'Psicólogo', 
            'Odontólogo',
            'Otorrino' 
        ];


        foreach ($professions as $value) {
            $profession = Profession::create([
                'id' => Str::uuid(),
                'name' => $value,
                'section_id' => $section->id
            ]);
        }

        $this->command->info(("Professions created"));
    }
}
