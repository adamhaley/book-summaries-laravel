<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinderFelixExecution extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'execution',
        'postal_code',
        'num_results',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'num_results' => 'integer',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(GermanCompany::class, 'populated_by');
    }
}


