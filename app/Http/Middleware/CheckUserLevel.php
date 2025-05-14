<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
    public function handle(Request $request, Closure $next, ...$levels)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userLevel = Auth::user()->level;

        // Jika level user tidak ada di parameter middleware
        if (!in_array($userLevel, $levels)) {
            // Cegah redirect loop: jika sudah di dashboard sesuai level, lanjutkan saja
            $dashboard = match($userLevel) {
                'admin' => '/admin',
                'bendahara' => '/bendahara',
                'pemilik' => '/owner', // level 'pemilik' route '/owner'
                'pelanggan' => '/',
                default => '/login'
            };

            if ($request->is(ltrim($dashboard, '/'))) {
                return $next($request);
            }

            return redirect($dashboard);
        }

        return $next($request);
    }        
}