<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'classroom_id',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'guardian_student');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}