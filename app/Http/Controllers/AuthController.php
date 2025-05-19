<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
            'terms' => 'accepted',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat ?? '',
            'password' => Hash::make($request->password),
            'level' => 'pelanggan',
            'aktif' => 1,
        ]);

        Pelanggan::create([
            'id_user' => $user->id,
            'nama_lengkap' => $request->name,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat ?? '',
        ]);

        Auth::login($user);
        $request->session()->put('loginId', $user->id);

        // Redirect ke halaman tambahan informasi user
        return redirect()->route('auth.info')->with('success', 'Registrasi berhasil! Silakan lengkapi data profil Anda.');
    }

    // Handle login request
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->has('remember');

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        if (Auth::attempt($credentials, $remember)) {
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

    // Show forgot password form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot');
    }

    // Send reset link email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
        // Kirim email manual (atau gunakan Mail::to()->send() jika ada view email)
        Mail::raw("Klik link berikut untuk reset password Anda: $resetLink", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Reset Password');
        });

        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    // Show reset password form
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.reset', compact('token', 'email'));
    }

    // Handle reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah digunakan.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
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