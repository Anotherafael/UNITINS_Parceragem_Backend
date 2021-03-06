<?php

namespace App\Models\Auth;

use App\Mail\ResetPasswordEmail;
use App\Models\Transaction\Order;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'photo_path',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_requests', 'user_id', 'order_id')->withTimestamps();
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = 'http://localhost:8000/api/v1/reset-password?email='.$this->email.'&token='.$token;
        Mail::to($this->email)->send(new ResetPasswordEmail($this, $url));
    }
}
