<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldTrip extends Model
{
    // softdelete gebruiken om de trips te kunnen verwijderen zonder ze echt uit de DB te verwijderen, we kunnen ze later nog herstellen als dat nodig is.
    use SoftDeletes;
    // STATUS_CANCELLED constant toevoegen aan de lijst van mogelijke statussen voor een trip, zodat we ook trips kunnen markeren als geannuleerd, en deze kunnen filteren of tonen in de UI.
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUSES = ['open', 'completed', 'cancelled'];
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
    // de relatie tussen een trip en een classroom, een trip hoort bij één classroom, en een classroom kan meerdere trips hebben
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
    // de relatie tussen een trip en payments, een trip kan meerdere payments hebben, en een payment hoort bij één trip
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    protected function casts(): array
    {
        // de casts zorgen ervoor dat de data automatisch wordt omgezet naar het juiste type wanneer we deze uit de database halen, bijvoorbeeld begin_date wordt automatisch een Carbon instance, en cost wordt automatisch een float met 2 decimalen
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
