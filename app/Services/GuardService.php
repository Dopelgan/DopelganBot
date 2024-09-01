<?php

namespace App\Services;

use App\Models\Guard;

class GuardService
{
    public function getGuardByStructure($structure)
    {
        return Guard::where('structure', $structure)->latest()->first();
    }
}
