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
        // If trainer is logged in → don't let him go back to login/register pages
        if (Auth::guard('trainer')->check()) {
            if (
                $request->is('trainer/login') ||
                $request->is('trainer/register') ||
                $request->is('user') ||
                $request->is('user/*') ||
                $request->is('admin') ||
                $request->is('admin/*')
            ) {
                return redirect()->route('trainer.dashboard');
            }
        }

        $response = $next($request);

        // YE HAI SABSE SAFE TAREEKA — StreamedResponse ke saath bhi kaam karega!
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }
}