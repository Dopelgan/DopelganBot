<?php

// app/Models/Guard.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guard extends Model
{
    protected $fillable = [
        'structure',
        'name',
        'contact',
        'telegram_link',
        'duty_schedule',
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
