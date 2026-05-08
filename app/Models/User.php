<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_superadmin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_superadmin' => 'boolean',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }


}