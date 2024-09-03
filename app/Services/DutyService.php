<?php

namespace App\Services;

use App\Models\Duty;
use Carbon\Carbon;

class DutyService
{
    public function getDutyByDepartment($department)
    {
        return Duty::where('department_id', $department)->latest()->first();
    }

    public function getCurrentDuties(Carbon $now)
    {
        return Duty::where('start_at', '<=', $now)
            ->where('end_at', '>=', $now)
            ->get();
    }
}
