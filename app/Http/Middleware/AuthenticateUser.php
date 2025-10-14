<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated via the 'web' guard
        
        if (!Auth::guard('web')->check()) {
            
            // If the request is for user routes, redirect to user login
            if ($request->is('user/*')) {
                dd("User authentication middleware");
                return redirect()->route('user.login');
            }
            
            // (Optional) If you have trainer routes too, handle them
            if ($request->is('trainer/*')) {
                return redirect()->route('trainer.login');
            }
        }

        // If authenticated, allow access
        return $next($request);
    }
}





