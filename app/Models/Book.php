<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'author',
        'status',
        'book_genre_id',
        'summary',
        'isbn',
        'cover_image_url',
        'publication_year',
        'page_count',
        'default_summary_pdf_url',
        'live',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'publication_year' => 'integer',
        'page_count' => 'integer',
        'live' => 'boolean',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(BookGenre::class, 'book_genre_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(Summary::class);
    }

    public function summariesV2(): HasMany
    {
        return $this->hasMany(SummaryV2::class);
    }
}


