<?php

namespace App\Models\Auth;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Service\Profession;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Professional extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

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

    public function professions()
    {
        return $this->belongsToMany(Profession::class, 'professional_professions', 'professional_id', 'profession_id');
    }
}
