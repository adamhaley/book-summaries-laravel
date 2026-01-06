<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Summary extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'book_id',
        'style',
        'length',
        'file_path',
        'tokens_spent',
        'generation_time',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tokens_spent' => 'integer',
        'generation_time' => 'decimal:3',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}


