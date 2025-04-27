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
     public function register()
     {
          return view("auth.register");
     }              

    // Handle user registration
    public function registerUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'level' => 'required|in:admin,bendahara,pelanggan,pemilik',
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'aktif' => 1,
        ]);
    
        if ($request->level == 'pelanggan') {
            // Insert ke tabel pelanggan
            Pelanggan::create([
                'id_user' => $user->id,
                'nama_lengkap' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        } else {
            // Insert ke tabel karyawan
            Karyawan::create([
                'id_user' => $user->id,
                'nama_karyawan' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'jabatan' => $request->level,
            ]);
        }

        if ($user->save()) {
            // Auth::login($user);
            $request->session()->put('loginId', $user->id);
            switch (Auth::user()->level) {
                case 'admin':
                return redirect()->intended('/admin')->with('success', 'Registrasi berhasil!');
            case 'bendahara':
                return redirect()->intended('/bendahara')->with('success', 'Registrasi berhasil!');
            case 'owner':
                return redirect()->intended('/pemilik')->with('success', 'Registrasi berhasil!');
            }
        } else {
            return back()->withErrors(['email' => 'Email atau Password Salah.']);
            return back()->withErrors('password', 'Password minimal 8 karakter.');
        }
    }


    // Handle login request
    public function loginUser(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email','=', $request->email)->first();
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->put('loginId', $user->id);

            // Redirect based on user role
            switch (Auth::user()->level) {
            case 'admin':
                return redirect()->intended('/admin');
            default:
                return redirect('/home');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->forget('loginId');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}