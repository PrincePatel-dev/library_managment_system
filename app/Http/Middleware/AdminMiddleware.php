<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Only allow admin users to access this route.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is an admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Access denied. Admins only.');
        }

        return $next($request);
    }
}
