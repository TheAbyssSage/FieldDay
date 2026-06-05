<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldTrip extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'begin_date',
        'end_date',
        'departure_time',
        'return_time',
        'cost',
        'payment_deadline',
        'classroom_id',
        'status',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function casts(): array
    {
        return [
            'begin_date' => 'date',
            'end_date' => 'date',
            'payment_deadline' => 'date',
            'departure_time' => 'datetime',
            'return_time' => 'datetime',
            'cost' => 'decimal:2',
        ];
    }
}
