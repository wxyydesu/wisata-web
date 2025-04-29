<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with(['pelanggan', 'karyawan'])->get();
        $greeting = $this->getGreeting();

        return view('be.manage.index', [
            'title' => 'User Management',
            'users' => $users,
            'greeting' => $greeting,
        ]);
    }

    public function create()
    {
        $greeting = $this->getGreeting();
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
            'level' => 'required|in:admin,bendahara,owner,pelanggan,karyawan', // added karyawan here
            'jabatan' => 'required_if:level,admin,bendahara,owner|nullable|string|in:administrasi,bendahara,pemilik',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads/profile', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('user.manage')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $greeting = $this->getGreeting();

        return view('be.manage.edit', [
            'title' => 'User Management Edit',
            'user' => $user,
            'greeting' => $greeting,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'required|string|max:15|unique:users,no_hp,' . $user->id,
            'jabatan' => 'required_if:level,admin,bendahara,owner|nullable|string|in:administrasi,bendahara,pemilik',
            'level' => 'required|in:admin,bendahara,owner,pelanggan,karyawan',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'level' => $request->level,
            'alamat' => $request->alamat,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama kalau ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $fotoPath = $request->file('foto')->store('uploads/profile', 'public');
            $data['foto'] = $fotoPath;
        }

        $user->update($data);

        return redirect()->route('user.manage')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus foto profile kalau ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // Hapus data relasi
        if ($user->pelanggan) {
            $user->pelanggan->delete();
        }
        if ($user->karyawan) {
            $user->karyawan->delete();
        }

        $user->delete();

        return redirect()->route('user.manage')->with('success', 'User deleted successfully.');
    }

    private function getGreeting()
    {
        $now = Carbon::now();
        if ($now->hour >= 5 && $now->hour < 12) {
            return 'Good Morning';
        } elseif ($now->hour >= 12 && $now->hour < 18) {
            return 'Good Evening';
        } else {
            return 'Good Night';
        }
    }
}
