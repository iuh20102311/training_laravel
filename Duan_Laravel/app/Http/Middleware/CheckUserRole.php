<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !in_array($request->user()->group_role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}





