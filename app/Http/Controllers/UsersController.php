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
        $users = User::all();
        $now = Carbon::now();
        $greeting = $now->hour >= 5 && $now->hour < 12 ? 'Good Morning' : ($now->hour >= 12 && $now->hour < 18 ? 'Good Evening' : 'Good Night');
        return view('be.manage.create', [
            'title' => 'User Management Create',
            'users' => $users,
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
            'level' => 'required|in:admin,bendahara,owner,pelanggan',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('user_photos', 'public');
            $user->update(['foto' => $path]);
        }

        return redirect()->route('user.manage')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // Fetch the user by ID
        $now = Carbon::now();
        $greeting = $now->hour >= 5 && $now->hour < 12 ? 'Good Morning' : ($now->hour >= 12 && $now->hour < 18 ? 'Good Evening' : 'Good Night');
        return view('be.manage.edit', [
            'title' => 'User Management Edit',
            'user' => $user, // Pass the user data to the view
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
            'level' => 'required|in:admin,bendahara,owner,pelanggan',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'level' => $request->level,
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('user_photos', 'public');
            $user->update(['foto' => $path]);
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