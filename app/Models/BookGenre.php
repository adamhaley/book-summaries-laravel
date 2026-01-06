<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookGenre extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}


