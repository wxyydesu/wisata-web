<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;

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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'no_hp' => 'required|unique:users,no_hp',
            'password' => 'required|min:8|max:12',
            'level' => 'required|in:admin,bendahara,pemilik,pelanggan'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->password = Hash::make($request->password);
        $user->level = $request->level;

        if ($user->save()) {
            // ✅ Simpan data ke tabel pelanggan
            // $pelanggan = new Pelanggan();
            // $pelanggan->id_user = $user->id;
            // $pelanggan->nama_lengkap = $user->name;
            // $pelanggan->no_hp = $user->no_hp;
            // $pelanggan->alamat = '-';
            // $pelanggan->save();

            Auth::login($user);
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
            case 'bendahara':
                return redirect()->intended('/bendahara');
            case 'owner':
                return redirect()->intended('/pemilik');
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