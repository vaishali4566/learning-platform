<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventTrainerBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ If trainer is logged in
        if (Auth::guard('trainer')->check()) {

            // ⚠️ Only block login/register/user/admin routes — not trainer dashboard
            if (
                $request->is('trainer/login') ||
                $request->is('trainer/register') ||
                $request->is('user/*') ||
                $request->is('admin/*') ||
                $request->is('user/login') ||
                $request->is('user/register')
            ) {
                return redirect()->route('trainer.dashboard');
            }
        }

        $response = $next($request);

        // 🚫 Prevent cached back navigation
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}
