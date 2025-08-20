<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user's status is 'inactive'
        if (Auth::check() && Auth::user()->status === 'inactive') {

            // Log the user out for security
            Auth::logout();

            // Redirect them to the login page with a message
            return redirect()->route('login')->with('status', 'Your account is currently inactive - Contact Administrator');
        }

        // If the status is not 'inactive', continue to the next middleware or the controller
        return $next($request);
    }
}
