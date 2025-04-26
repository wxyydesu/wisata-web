<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); // <- Ditaruh di atas
        return view('be.manage.index', [
            'title' => 'User Management',
            'users' => $users, // <- Ini, kasih key 'users' dan value $users
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('be.manage.create', [
            'title' => 'Create User'
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
            'password' => 'required|min:8',
            'level' => 'required|in:admin,bendahara,pemilik,pelanggan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
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
        return view('be.manage.edit', ['user' => $user]);

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
        $user->delete();
        return redirect()->route('user.manage')->with('success', 'User deleted successfully.');
    }
}
