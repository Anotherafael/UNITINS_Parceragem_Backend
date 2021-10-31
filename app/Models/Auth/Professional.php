<?php

namespace App\Models\Auth;

use App\Mail\ResetPasswordEmail;
use App\Models\Transaction\Order;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Service\Profession;
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
        'api_token',
    ];

    public function professions()
    {
        return $this->belongsToMany(Profession::class, 'professional_professions', 'professional_id', 'profession_id')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = 'http://localhost:8000/api/v1/reset-password?email='.$this->email.'&token=' . $token;
        Mail::to($this->email)->send(new ResetPasswordEmail($this, $url));
    }
}
