<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'user_id'  
    ];

}
