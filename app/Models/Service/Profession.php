<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
