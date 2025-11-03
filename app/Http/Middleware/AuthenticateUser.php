<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser
{
    public function handle(Request $request, Closure $next, string $guard = 'web'): Response
    {
        Auth::shouldUse($guard);

        if (!Auth::guard($guard)->check()) {
            return match ($guard) {
                'trainer' => redirect()->route('trainer.login'),
                default => redirect()->route('user.login'),
            };
        }

        return $next($request);
    }
}
