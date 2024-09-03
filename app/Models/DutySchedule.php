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
        'start_at',
        'end_at',
    ];

    public function duty()
    {
        return $this->belongsTo(Duty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}


