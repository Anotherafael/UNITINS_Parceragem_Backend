<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'services';

    protected $fillable = [
        'id',
        'name',
        'profession_id'
    ];
}
