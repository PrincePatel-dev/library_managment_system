<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     * Only allow student users to access this route.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is a student
        if (!auth()->check() || auth()->user()->role !== 'student') {
            return redirect()->route('login')->with('error', 'Access denied. Students only.');
        }

        return $next($request);
    }
}
