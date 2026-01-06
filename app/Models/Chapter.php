<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasUuids;

    protected $fillable = [
        'book_id',
        'title',
        'content',
        'summary_md',
        'token_count',
        'status',
        'metadata',
        'chapter_index',
        // embedding intentionally omitted from mass assignment by default
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'token_count' => 'integer',
        'metadata' => 'array',
        'chapter_index' => 'integer',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(ChapterChunk::class);
    }
}


