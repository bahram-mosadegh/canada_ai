<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'ai_data' => 'array',
    ];

    public function case(): BelongsTo
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }
}


