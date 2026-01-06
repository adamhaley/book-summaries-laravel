<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GermanDistrict extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'state',
        'city',
        'district',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}


