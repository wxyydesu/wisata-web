<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with(['pelanggan', 'karyawan'])->get();

        $now = Carbon::now();
        $greeting = $now->hour >= 5 && $now->hour < 12 ? 'Good Morning' : ($now->hour >= 12 && $now->hour < 18 ? 'Good Evening' : 'Good Night');

        return view('be.manage.index', [
            'title' => 'User Management',
            'users' => $users,
            'greeting' => $greeting,
        ]);
    }

    public function create()
    {
        $now = Carbon::now();
        $greeting = $now->hour >= 5 && $now->hour < 12 ? 'Good Morning' : ($now->hour >= 12 && $now->hour < 18 ? 'Good Evening' : 'Good Night');

        return view('be.manage.create', [
            'title' => 'User Management Create',
            'greeting' => $greeting,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:15|unique:users,no_hp',
            'password' => 'required|min:6|confirmed',
            'level' => ['required', Rule::in(['admin', 'bendahara', 'pelanggan', 'pemilik'])],
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('user_photos', 'public');
            
            if ($request->level == 'pelanggan') {
                Pelanggan::create([
                    'id_user' => $user->id,
                    'nama_lengkap' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'foto' => $path,
                ]);
            } else {
                Karyawan::create([
                    'id_user' => $user->id,
                    'nama_karyawan' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'jabatan' => $request->level,
                    'foto' => $path,
                ]);
            }
        } else {
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
        }

        return redirect()->route('user.manage')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::with(['pelanggan', 'karyawan'])->findOrFail($id);
        return view('be.manage.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'no_hp' => 'required|string|max:15|unique:users,no_hp,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
            'level' => ['required', Rule::in(['admin', 'bendahara', 'pelanggan', 'pemilik'])],
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'level' => $request->level,
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('user_photos', 'public');
            
            if ($user->level == 'pelanggan') {
                $user->pelanggan->update([
                    'nama_lengkap' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'foto' => $path,
                ]);
            } else {
                $user->karyawan->update([
                    'nama_karyawan' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'jabatan' => $request->level,
                    'foto' => $path,
                ]);
            }
        } else {
            if ($user->level == 'pelanggan') {
                $user->pelanggan->update([
                    'nama_lengkap' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            } else {
                $user->karyawan->update([
                    'nama_karyawan' => $request->name,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'jabatan' => $request->level,
                ]);
            }
        }

        return redirect()->route('user.manage')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Delete related records first
        if ($user->pelanggan) {
            $user->pelanggan->delete();
        }
        if ($user->karyawan) {
            $user->karyawan->delete();
        }
        
        $user->delete();

        return redirect()->route('user.manage')->with('success', 'User deleted successfully.');
    }
}