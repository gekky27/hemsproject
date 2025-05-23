<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if (empty($roles) || in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        if (auth()->user()->role == 'admin') {
            return redirect()->back()->with('error', 'Admin cannot purchase tickets.');
        }

        if (auth()->user()->role == 'organizer') {
            return redirect()->back()->with('error', 'Organizer cannot purchase tickets.');
        }

        abort('404');
    }
}
