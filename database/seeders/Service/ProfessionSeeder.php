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

        $this->fillSectionWithProfessions('Saúde', [
            'Psicólogo', 
            'Odontólogo',
            'Otorrino',
        ]);
        $this->fillSectionWithProfessions('Educação', [
            'Professor Particular', 
            'Reforço Escolar',
            'Revisor de Redação',
        ]);

        $this->command->info(("Professions created"));
    }

    function fillSectionWithProfessions($section, array $array) {

        $section = Section::where('name', '=', $section)->first();

        foreach ($array as $value) {
            $profession = Profession::create([
                'id' => Str::uuid(),
                'name' => $value,
                'section_id' => $section->id
            ]);
        }
    }
}
