<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Techer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'techer';
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'birthday',
        'visible',
    ];
}
