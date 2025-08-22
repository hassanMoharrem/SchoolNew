<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;


class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'teacher';
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'birthday',
        'visible',
    ];

        /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : asset('assets/images/images.png');
    }

    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->birthday)->age;
    }
}
