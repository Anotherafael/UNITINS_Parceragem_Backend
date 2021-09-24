<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'sections';

    protected $fillable = [
        'id',
        'name',    
    ];
}
