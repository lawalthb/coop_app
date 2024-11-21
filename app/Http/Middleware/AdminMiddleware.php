<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('member.dashboard')
                ->with('warning', 'Unauthorized access.');
        }

        return $next($request);
    }
}