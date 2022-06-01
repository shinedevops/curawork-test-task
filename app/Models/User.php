<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sentRequests() {
        return $this->hasMany(Request::class, 'sent_to', 'id');
    }
    public function receivedRequests() {
        return $this->hasMany(Request::class, 'sent_by', 'id');
    }

    /** Many to many relationship **/
    public function sentUserRequests(){
        return $this->belongsToMany(User::class,'requests','sent_by','sent_to')->withPivot('id', 'status');
    }
    public function recievedUserRequests(){
        return $this->belongsToMany(User::class,'requests','sent_to','sent_by')->withPivot('id', 'status');
    }
}
