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
            'password' => 'required|min:5|max:12',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->password = Hash::make($request->password);
        $user->level = 'pelanggan';

        if ($user->save()) {

            // ✅ Simpan data ke tabel pelanggan
            $pelanggan = new Pelanggan();
            $pelanggan->id_user = $user->id;
            $pelanggan->nama_lengkap = $user->name;
            $pelanggan->no_hp = $user->no_hp; // default / bisa dari input request juga
            $pelanggan->alamat = '-';
            $pelanggan->save();

            // ✅ Login langsung
            Auth::login($user);
            $request->session()->put('loginId', $user->id);

            return redirect()->route('login')->with('success', 'Registrasi berhasil!');
        } else {
            return back()->with('fail', 'Terjadi kesalahan.');
        }
    }


    // Handle login request
    public function loginUser(Request $request)
    {
        // Validate credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);
    
        $user = User::where('email','=', $request->email)->first();
          if($user){
               if(Hash::check($request->password, $user->password)) {
                    $request->session()->put('loginId', $user->id);
                    
                    // Redirect berdasarkan level user
                    switch($user->level) {
                        case 'admin':
                            return redirect()->route('be.admin.index');
                        case 'kurir':
                            return redirect()->route('fe.home.index');
                        case 'owner':
                            return redirect()->route('fe.home.index');
                        case 'pelanggan':
                            return redirect()->route('fe.home.index');
                        default:
                            return redirect()->route('fe.home.index');
                    }
               }else{
                    return back()->with('fail','Password not matches.'); 
               }
          }else{
               return back()->with('fail','This email is not registered.');
          }
    }
    // Handle logout
    public function logout(Request $request)
    {
        // Clear user session data
        $request->session()->forget('loginId');
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Redirect to login page instead of home
        return redirect()->route('login')->with('success', 'Logged out successfully');
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