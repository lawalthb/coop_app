<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminApproval
{
    public function handle(Request $request, Closure $next)
    {
        // Only check admin approval if user is logged in
        if (auth()->check() && auth()->user()->admin_sign === 'No') {
            return redirect()->route('member.dashboard')
                ->with('warning', 'Your account is pending admin approval.');
        }

        return $next($request);
    }
}
