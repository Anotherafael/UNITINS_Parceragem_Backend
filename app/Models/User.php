<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'document_id',    
        'password',       
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
