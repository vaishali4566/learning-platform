<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user already logged in → block login/register pages
        if (Auth::guard('web')->check()) {
            if ($request->is('user/login') || $request->is('user/register')) {
                return redirect()->route('user.dashboard');
            }
        }

        $response = $next($request);

        // ❗ If response is for video streaming, header() is NOT allowed
        if ($response instanceof StreamedResponse) {
            return $response; // Return as-is, no header changes
        }

        // Safe header modification for normal responses
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }
}
