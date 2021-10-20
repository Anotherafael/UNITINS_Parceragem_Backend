<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusNameAttribute()
    {
        return $this->allStatus()[$this->status];
    }

    public static function allStatus()
    {
        return [
            1 => 'Pendente',
            'Aceito',
            'Rejeitado'
        ];
    }
}
