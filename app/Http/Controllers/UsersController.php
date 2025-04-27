<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'no_hp' => 'required|unique:users,no_hp',
            'password' => 'required|min:8',
            'level' => 'required|in:admin,bendahara,pemilik,pelanggan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);

        return redirect()->route('user.manage')->with('success', 'User created successfully.');
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
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'level' => 'required|in:admin,bendahara,pemilik,pelanggan',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
        ]);

        return redirect()->route('user.manage')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('user.manage')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('user.manage')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
