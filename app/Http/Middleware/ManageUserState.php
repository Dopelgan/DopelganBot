<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ManageUserState
{
    public function handle($request, Closure $next)
    {
        // Управление состоянием пользователя может быть здесь
        return $next($request);
    }
}
