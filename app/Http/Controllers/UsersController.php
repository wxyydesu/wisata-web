<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $now = Carbon::now();

        $greeting = '';

        if ($now->hour >= 5 && $now->hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($now->hour >= 12 && $now->hour < 18) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }
        return view('be.manage.index', [
            'title' => 'User Management',
            'users' => $users,
            'greeting' => $greeting,
            'datas' => User::all(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $now = Carbon::now();

        $greeting = '';

        if ($now->hour >= 5 && $now->hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($now->hour >= 12 && $now->hour < 18) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }
        return view('be.manage.create', [
            'title' => 'Create User',
            'greeting' => $greeting,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'level' => 'required|in:admin,bendahara,pelanggan,owner',
            'nama' => 'required|string|max:255',
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
                'nama_lengkap' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        } else {
            Karyawan::create([
                'id_user' => $user->id,
                'nama_karyawan' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'jabatan' => $request->level,
            ]);
        }

        return redirect('user-manage')->with('success', 'User berhasil ditambahkan.');
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
    public function edit(User $user)
    {
        return view('be.manage.edit', [
            'user' => $user,
            'title' => 'Edit User',
            'data' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user_manage)
    {
        $user = User::findOrFail($user_manage);

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user_manage->id,
            'password' => 'nullable|min:6|confirmed',
            'level' => 'required|in:admin,bendahara,pelanggan,owner',
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->level = $request->level;
        $user->save();

        // Update Pelanggan atau Karyawan
        if ($user->level == 'pelanggan') {
            $pelanggan = Pelanggan::where('id_user', $user->id)->first();
            if ($pelanggan) {
                $pelanggan->update([
                    'nama_lengkap' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            }
        } else {
            $karyawan = Karyawan::where('id_user', $user->id)->first();
            if ($karyawan) {
                $karyawan->update([
                    'nama_karyawan' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'jabatan' => $request->level,
                ]);
            }
        }

        return redirect()->back()->with('success', 'User berhasil diupdate.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Hapus data terkait di Pelanggan atau Karyawan
        Pelanggan::where('id_user', $user->id)->delete();
        Karyawan::where('id_user', $user->id)->delete();

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

}
