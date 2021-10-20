<?php

namespace App\Models\Transaction;

use App\Models\Auth\User;
use App\Models\Service\Service;
use App\Models\Auth\Professional;
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
        'price',
        'service_id',
        'professional_id',
        'status'
    ];

    public function service()
    {
        return $this->hasOne(Service::class);
    }

    public function professional()
    {
        return $this->hasOne(Professional::class);
    }

    public function getStatusNameAttribute()
    {
        return $this->allStatus()[$this->status];
    }

    public static function allStatus()
    {
        return [
            1 => 'Pendente',
            'Encaminhado',
            'Deletado'
        ];
    }
}
