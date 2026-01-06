<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GermanCompany extends Model
{
    protected $fillable = [
        'company',
        'industry',
        'ceo_name',
        'phone',
        'email',
        'website',
        'address',
        'district',
        'city',
        'state',
        'analysis',
        'populated_by',
        'location_link',
        'exported_to_instantly',
        'email_status',
        'first_contact_sent',
        'is_duplicate',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'exported_to_instantly' => 'boolean',
        'first_contact_sent' => 'boolean',
        'is_duplicate' => 'boolean',
    ];

    public function execution(): BelongsTo
    {
        return $this->belongsTo(FinderFelixExecution::class, 'populated_by');
    }
}


