<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        if (!Auth::user()->isActive()) {
            // Allow login but show message
            return redirect()->route('login')->with('error', 'You are not approved yet. Please contact admin - 0707645303');
            
            // Alternatively, if you want to show this on every page until they're approved:
            // $response = $next($request);
            // return $response->with('error', 'You are not approved yet. Please contact admin - 0707645303');
        }

        return $next($request);
    }
}