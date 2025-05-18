<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;
use App\Models\Karyawan;

class AuthController extends Controller
{
    // Show login form
    public function login()
    {
        return view("auth.login");
    }

    // Show registration form
    public function register()
    {
        return view("auth.register");
    }              

    // Handle user registration
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'level' => 'required|in:admin,bendahara,pelanggan,owner',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat ?? '',
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'aktif' => 1,
        ]);
    
        if ($request->level == 'pelanggan') {
            Pelanggan::create([
                'id_user' => $user->id,
                'nama_lengkap' => $request->name,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat ?? '',
            ]);
        } else {
            $mappingLevelToJabatan = [
                'admin' => 'administrasi',
                'bendahara' => 'bendahara',
                'owner' => 'pemilik',
            ];

            $jabatan = $mappingLevelToJabatan[$request->level] ?? null;

            if (!$jabatan) {
                return back()->with('error', 'Invalid role for karyawan.');
            }

            Karyawan::create([
                'id_user' => $user->id,
                'nama_karyawan' => $request->name,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat ?? '',
                'jabatan' => $jabatan,
            ]);
        }

        Auth::login($user);
        $request->session()->put('loginId', $user->id);

        switch ($user->level) {
            case 'admin':
                return redirect()->intended('/dashboard/admin')->with('success', 'Registrasi berhasil!');
            case 'bendahara':
                return redirect()->intended('/dashboard/bendahara')->with('success', 'Registrasi berhasil!');
            case 'owner':
                return redirect()->intended('/dashboard/owner')->with('success', 'Registrasi berhasil!');
            case 'pelanggan':
                return redirect('/home')->with('success', 'Registrasi berhasil!');
            default:
                return redirect('/')->with('success', 'Registrasi berhasil!');
        }
    }

    // Handle login request
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->put('loginId', $user->id);

            switch ($user->level) {
                case 'admin':
                    return redirect()->intended('/dashboard/admin');
                case 'bendahara':
                    return redirect()->intended('/dashboard/bendahara');
                case 'owner':
                    return redirect()->intended('/dashboard/owner');
                case 'pelanggan':
                    return redirect()->intended('/home');
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('loginId');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}