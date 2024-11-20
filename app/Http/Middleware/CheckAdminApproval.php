<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminApproval
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->admin_sign) {
            return redirect()->route('member.dashboard')
                ->with('warning', 'Your account is pending admin approval.');
        }

        return $next($request);
    }
}
