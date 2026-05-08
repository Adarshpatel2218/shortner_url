<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    protected $table = 'company_user';

    protected $fillable = [
        'user_id',
        'company_id',
        'role',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'company_id' => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invitation()
    {
        return $this->hasOne(Invitation::class, 'user_id', 'user_id');
    }


}