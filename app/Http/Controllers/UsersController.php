<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersController extends Controller
{
    // Display a listing of users
    public function index()
    {
        $now = Carbon::now();
        $greeting = $now->hour >= 5 && $now->hour < 12 ? 'Good Morning'
                  : ($now->hour >= 12 && $now->hour < 18 ? 'Good Evening' : 'Good Night');

        $users = User::all();

        return view('be.manage.index', [
            'title' => 'User Management',
            'users' => $users,
            'greeting' => $greeting,
        ]);
    }

    // Show the form for creating a new user
    public function create()
    {
        $now = Carbon::now();
        $greeting = $now->hour >= 5 && $now->hour < 12 ? 'Good Morning'
                  : ($now->hour >= 12 && $now->hour < 18 ? 'Good Evening' : 'Good Night');

        return view('be.manage.create', [
            'title' => 'Create User',
            'greeting' => $greeting,
        ]);
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'level' => 'required|in:admin,bendahara,pelanggan,owner',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'aktif' => 1,
        ]);

        if ($request->level == 'pelanggan') {
            Pelanggan::create([
                'id_user' => $user->id,
                'nama_lengkap' => $request->name,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        } else {
            Karyawan::create([
                'id_user' => $user->id,
                'nama_karyawan' => $request->name,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'jabatan' => $request->level,
            ]);
        }

        return redirect('user-manage')->with('success', 'User berhasil ditambahkan.');
    }

    // Show the form for editing the specified user
    public function edit(User $user)
    {
        return view('be.manage.edit', [
            'title' => 'Edit User',
            'user' => $user,
        ]);
    }

    // Update the specified user in storage
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'level' => 'required|in:admin,bendahara,pelanggan,owner',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->level = $request->level;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update data pelanggan atau karyawan
        if ($user->level == 'pelanggan') {
            $pelanggan = Pelanggan::firstOrNew(['id_user' => $user->id]);
            $pelanggan->nama_lengkap = $request->name;
            $pelanggan->no_hp = $request->no_hp;
            $pelanggan->alamat = $request->alamat;
            $pelanggan->save();
        } else {
            $karyawan = Karyawan::firstOrNew(['id_user' => $user->id]);
            $karyawan->nama_karyawan = $request->name;
            $karyawan->no_hp = $request->no_hp;
            $karyawan->alamat = $request->alamat;
            $karyawan->jabatan = $request->level;
            $karyawan->save();
        }

        return redirect()->route('user-manage.index')->with('success', 'User berhasil diupdate.');
    }

    // Remove the specified user from storage
    public function destroy(User $user)
    {
        // Hapus relasi dulu
        Pelanggan::where('id_user', $user->id)->delete();
        Karyawan::where('id_user', $user->id)->delete();

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
