<?php

// app/Models/Duty.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    protected $fillable = [
        'id',
        'name',
        'contact',
        'telegram_link',
        'department_id',
    ];

    protected $casts = [
        'duty_schedule' => 'array',
    ];

    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
