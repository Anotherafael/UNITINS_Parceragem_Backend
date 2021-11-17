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
        $this->fillProfessionWithTasks('Psicólogo', [
            'Ansiedade',
            'Depressão', 
            'Terapia', 
        ]);
        $this->fillProfessionWithTasks('Otorrino', [
            'Consulta',
            'Rinoplastia', 
            'Otoplastia', 
        ]);
        $this->fillProfessionWithTasks('Odontólogo', [
            'Consulta',
            'Limpeza', 
            'Extração de sisos', 
        ]);
        $this->fillProfessionWithTasks('Professor Particular', [
            'Matemática',
            'Lingua Inglesa', 
            'Biologia', 
            'Física', 
            'Química', 
        ]);
        $this->fillProfessionWithTasks('Reforço Escolar', [
            'Ensino Fundamental I e II',
            'Ensino Médio', 
        ]);
        $this->fillProfessionWithTasks('Revisor de Redação', [
            'Revisão + Aula',
            'Somente revisão', 
        ]);
        
        $this->command->info(("Tasks created"));
    }
    
    function fillProfessionWithTasks($profession, array $tasks) {
        
        $profession = Profession::where('name', '=', $profession)->first();

        foreach ($tasks as $value) {
            $tasks = Task::create([
                'id' => Str::uuid(),
                'name' => $value,
                'profession_id' => $profession->id
            ]);
        }
    }
}
