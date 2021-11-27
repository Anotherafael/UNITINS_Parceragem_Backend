<?php

namespace App\Models\Transaction;

use App\Models\Auth\User;
use App\Models\Transaction\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestOrder extends Model
{
    use HasFactory;

    protected $table = 'order_requests';

    protected $fillable = [
        'id',
        'order_id',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getStatusAttribute($value)
    {
        return $this->allStatus()[$value];
    }

    public static function allStatus()
    {
        return [
            1 => 'Pendente',
            'Aceito',
            'Cancelado'
        ];
    }
}
