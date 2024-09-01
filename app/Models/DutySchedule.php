<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'guard_id',
        'date',
        'start',
        'end',
    ];

    // Определите связь с моделью Guard
    public function guardRelation()
    {
        return $this->belongsTo(Guard::class);
    }
}


