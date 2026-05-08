<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id'
    ];

    // 🔗 Many-to-Many with User
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    // 🔗 Company has many URLs
    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    // 🔗 Owner (optional)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}