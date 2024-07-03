<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_delete == 1) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tài khoản hiện đang bị xóa. Liên hệ admin để làm rõ.');
            }
            if ($user->is_active == 0) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tài khoản đang bị khóa.');
            }
        }
        return $next($request);
    }
}