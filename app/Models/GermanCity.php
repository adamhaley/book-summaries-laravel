<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GermanCity extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'state',
        'city',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}


