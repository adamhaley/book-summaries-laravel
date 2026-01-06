<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SummaryV2 extends Model
{
    use HasUuids;

    protected $table = 'summaries_v2';

    protected $fillable = [
        'book_id',
        'summary',
        'length',
        'style',
        'complete_book_summary',
        'formatted_summary',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'summary' => 'array',
        'formatted_summary' => 'array',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}


