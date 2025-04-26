<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
    public function handle(Request $request, Closure $next, ...$level)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $userLevel = Auth::user()->level;

            // Check if the user's level matches the allowed levels
            if (!in_array($userLevel, $level)) {
                // Redirect to the appropriate dashboard based on their role
                switch ($userLevel) {
                    case 'admin':
                        return redirect('/admin');
                    case 'bendahara':
                        return redirect('/bendahara');
                    case 'pemilik':
                        return redirect('/pemilik');
                    case 'pelanggan':
                        return redirect('/pelanggan');
                    default:
                        return redirect('/home'); // Default fallback
                }
            }
        }

        return $next($request);
    }                               
}