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
    public function handle(Request $request, Closure $next, $guard = null)
{
    $guard = $guard ?? 'web';

    // Debug
    logger('AuthenticateUser Middleware', [
        'guard' => $guard,
        'url' => $request->url(),
        'auth_web' => Auth::guard('web')->check(),
        'auth_trainer' => Auth::guard('trainer')->check(),
    ]);

    if (!Auth::guard($guard)->check()) {
        if ($guard === 'web') return redirect()->route('user.login');
        if ($guard === 'trainer') return redirect()->route('trainer.login');
    }

    return $next($request);
}

}





