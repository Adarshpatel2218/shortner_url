<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'original_url',
        'short_code',
        'clicks'
    ];

    // 🔗 URL belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 URL belongs to company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // 🔥 Scope (VERY USEFUL)
    public function scopeForCurrentUser($query)
    {
        if (auth()->user()->is_superadmin) {
            return $query;
        }

        if (currentUserRole() === 'admin') {
            return $query->where('company_id', currentCompanyId());
        }

        return $query->where('company_id', currentCompanyId())
            ->where('user_id', auth()->id());
    }
}