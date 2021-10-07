<?php

namespace App\Models\Service;

use App\Models\Service\Profession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function profession()
    {
        return $this->hasOne(Profession::class);
    }
}
