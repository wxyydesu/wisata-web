<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index()
{
    $users = User::with(['pelanggan', 'karyawan'])->latest()->paginate(10);
    $greeting = $this->getGreeting();
    
    return view('be.manage.index', [
        'users' => $users,
        'greeting' => $greeting
    ]);
}

    public function create()
    {
        $greeting = $this->getGreeting();
        return view('be.manage.create', [
            'greeting' => $greeting,
            'levels' => ['admin', 'bendahara', 'owner', 'pelanggan'],
            'jabatans' => ['administrasi', 'bendahara', 'pemilik']
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'level' => 'required|in:admin,bendahara,owner,pelanggan',
            'aktif' => 'required|boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jabatan' => 'required_if:level,admin,bendahara,owner',
            'nama_lengkap' => 'required_if:level,pelanggan'
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'level' => $request->level,
            'aktif' => $request->aktif
        ]);

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profile-photos', 'public');
        }

        // Create related record based on level
        if ($request->level === 'pelanggan') {
            Pelanggan::create([
                'nama_lengkap' => $request->nama_lengkap,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
                'id_user' => $user->id
            ]);
        } else {
            Karyawan::create([
                'nama_karyawan' => $request->name,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'jabatan' => $request->jabatan,
                'foto' => $fotoPath,
                'id_user' => $user->id
            ]);
        }

        return redirect()->route('user_manage')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::with(['pelanggan', 'karyawan'])->findOrFail($id);
        $greeting = $this->getGreeting();
        
        return view('be.manage.edit', [
            'user' => $user,
            'greeting' => $greeting,
            'levels' => ['admin', 'bendahara', 'owner', 'pelanggan'],
            'jabatans' => ['administrasi', 'bendahara', 'pemilik']
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'level' => 'required|in:admin,bendahara,owner,pelanggan',
            'aktif' => 'required|boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jabatan' => 'required_if:level,admin,bendahara,owner',
            'nama_lengkap' => 'required_if:level,pelanggan'
        ]);

        // Update User
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'level' => $request->level,
            'aktif' => $request->aktif
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->level === 'pelanggan' && $user->pelanggan && $user->pelanggan->foto) {
                Storage::disk('public')->delete($user->pelanggan->foto);
            } elseif ($user->karyawan && $user->karyawan->foto) {
                Storage::disk('public')->delete($user->karyawan->foto);
            }
            
            $fotoPath = $request->file('foto')->store('profile-photos', 'public');
        }

        // Update related record based on level
        if ($request->level === 'pelanggan') {
            $pelangganData = [
                'nama_lengkap' => $request->nama_lengkap,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'id_user' => $user->id
            ];

            if ($fotoPath) {
                $pelangganData['foto'] = $fotoPath;
            }

            if ($user->pelanggan) {
                $user->pelanggan->update($pelangganData);
            } else {
                // Delete karyawan record if exists
                if ($user->karyawan) {
                    $user->karyawan->delete();
                }
                Pelanggan::create($pelangganData);
            }
        } else {
            $karyawanData = [
                'nama_karyawan' => $request->name,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'jabatan' => $request->jabatan,
                'id_user' => $user->id
            ];

            if ($fotoPath) {
                $karyawanData['foto'] = $fotoPath;
            }

            if ($user->karyawan) {
                $user->karyawan->update($karyawanData);
            } else {
                // Delete pelanggan record if exists
                if ($user->pelanggan) {
                    $user->pelanggan->delete();
                }
                Karyawan::create($karyawanData);
            }
        }

        return redirect()->route('user_manage')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::with(['pelanggan', 'karyawan'])->findOrFail($id);

        // Delete related photo files
        if ($user->pelanggan && $user->pelanggan->foto) {
            Storage::disk('public')->delete($user->pelanggan->foto);
        } elseif ($user->karyawan && $user->karyawan->foto) {
            Storage::disk('public')->delete($user->karyawan->foto);
        }

        // Delete related records
        if ($user->pelanggan) {
            $user->pelanggan->delete();
        }
        if ($user->karyawan) {
            $user->karyawan->delete();
        }

        // Delete user
        $user->delete();

        return redirect()->route('user_manage')->with('success', 'User deleted successfully.');
    }

    public function show($id)
    {
        $user = User::with(['pelanggan', 'karyawan'])->findOrFail($id);
        $greeting = $this->getGreeting();
        
        return view('be.manage.show', [
            'user' => $user,
            'greeting' => $greeting,
            'levels' => ['admin', 'bendahara', 'owner', 'pelanggan'],
            'jabatans' => ['administrasi', 'bendahara', 'pemilik']
        ]);
    }

    private function getGreeting()
    {
        $hour = now()->hour;
        
        if ($hour < 12) {
            return 'Good Morning';
        } elseif ($hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }
}