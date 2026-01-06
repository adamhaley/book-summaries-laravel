<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterChunk extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'chapter_id',
        'content',
        'metadata',
        'chunk_index',
        // embedding intentionally omitted from mass assignment by default
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'metadata' => 'array',
        'chunk_index' => 'integer',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}


