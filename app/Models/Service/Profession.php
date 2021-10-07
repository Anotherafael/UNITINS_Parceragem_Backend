<?php

namespace App\Models\Service;

use App\Models\Service\Section;
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

    public function section()
    {
        return $this->hasOne(Section::class, 'section_id');
    }
}
