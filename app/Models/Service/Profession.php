<?php

namespace App\Models\Service;

use App\Models\Service\Task;
use App\Models\Service\Section;
use App\Models\Auth\Professional;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profession extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'professions';

    protected $fillable = [
        'id',
        'name',
        'section_id'    
    ];

    public function professionals()
    {
        return $this->belongsToMany(Professional::class, 'professional_professions', 'profession_id', 'professional_id')->withTimestamps();
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
