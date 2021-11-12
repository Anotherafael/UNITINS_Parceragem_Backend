<?php

namespace App\Models\Service;

use App\Models\Transaction\Order;
use App\Models\Service\Profession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'tasks';

    protected $fillable = [
        'id',
        'name',
        'profession_id'
    ];

    public function profession()
    {
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
