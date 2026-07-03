<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'api_token',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'last_seen_at' => 'datetime',
    ];
}
