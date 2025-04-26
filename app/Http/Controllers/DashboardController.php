<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek level pengguna dan arahkan ke halaman dashboard yang sesuai
        if ($user->level === 'admin') {
            return view('dashboard.admin');
        } elseif ($user->level === 'bendahara') {
            return view('dashboard.bendahara');
        } elseif ($user->level === 'pemilik') {
            return view('dashboard.pemilik');
        }

        // Jika level tidak sesuai, arahkan ke halaman error atau logout
        return redirect()->route('logout')->withErrors('Unauthorized access.');
    }
}