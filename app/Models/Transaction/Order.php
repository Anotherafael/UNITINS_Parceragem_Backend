<?php

namespace App\Models\Transaction;

use App\Models\Auth\User;
use App\Models\Service\Task;
use App\Models\Auth\Professional;
use App\Models\Service\Profession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'orders';

    protected $fillable = [
        'id',
        'date',
        'hour',
        'price',
        'task_id',
        'professional_id',
        'status'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function orderRequests()
    {
        return $this->belongsToMany(User::class, 'order_requests', 'order_id', 'user_id')->withTimestamps();
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    public function getStatusAttribute($value)
    {
        return $this->allStatus()[$value];
    }

    public static function allStatus()
    {
        return [
            1 => 'Pendente',
            'À realizar',
            'Cancelado',
            'Realizado'
        ];
    }
}
