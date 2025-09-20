<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientCase extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $guarded = [];

    protected $casts = [
        'applicant_names' => 'array',
        'required_documents' => 'array'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(CaseFile::class, 'case_id');
    }
}


