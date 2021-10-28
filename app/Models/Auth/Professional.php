<?php

namespace App\Models\Auth;

use App\Mail\ResetPasswordEmail;
use App\Models\Service\Profession;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Professional extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    protected $table = 'professionals';

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
        return $this->belongsToMany(Profession::class, 'professional_professions', 'professional_id', 'profession_id')->withTimestamps();
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = 'https://localhost:8000/reset-password?token='.$token;
        Mail::to($this->email)->send(new ResetPasswordEmail($this, $url));
    }
}
