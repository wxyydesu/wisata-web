<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelanggan;
use App\Models\Karyawan;


class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        $users = Auth::user();
        
        return view('auth.info', [
            'title' => 'Profile',
            'user' => $users,
        ]);
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
    public function update(Request $request)
    {
        $user = Auth::user();
        
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'no_hp' => 'required|string|max:15',
                'alamat' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Update user basic info (without foto)
            $user->name = $request->nama_lengkap;
            $user->email = $request->email;
            $user->no_hp = $request->no_hp;
            $user->alamat = $request->alamat;
            $user->save();
            
            // Handle photo upload and update specific table
            $path = null;
            if ($request->hasFile('foto')) {
                // Handle upload based on user level
                if ($user->level === 'pelanggan') {
                    $pelanggan = Pelanggan::where('id_user', $user->id)->firstOrFail();
                    
                    // Delete old photo if exists
                    if ($pelanggan->foto && Storage::exists($pelanggan->foto)) {
                        Storage::delete($pelanggan->foto);
                    }
                    
                    $path = $request->file('foto')->store('profile-photos');
                    $pelanggan->nama_lengkap = $request->nama_lengkap;
                    $pelanggan->no_hp = $request->no_hp;
                    $pelanggan->alamat = $request->alamat;
                    $pelanggan->foto = $path;
                    $pelanggan->save();
                    
                } elseif (in_array($user->level, ['admin', 'bendahara', 'owner'])) {
                    $karyawan = Karyawan::where('id_user', $user->id)->firstOrFail();
                    
                    // Delete old photo if exists
                    if ($karyawan->foto && Storage::exists($karyawan->foto)) {
                        Storage::delete($karyawan->foto);
                    }
                    
                    $path = $request->file('foto')->store('profile-photos');
                    $karyawan->nama_karyawan = $request->nama_lengkap;
                    $karyawan->no_hp = $request->no_hp;
                    $karyawan->alamat = $request->alamat;
                    $karyawan->foto = $path;
                    $karyawan->save();
                }
            } else {
                // Update without photo
                if ($user->level === 'pelanggan') {
                    $pelanggan = Pelanggan::where('id_user', $user->id)->firstOrFail();
                    $pelanggan->nama_lengkap = $request->nama_lengkap;
                    $pelanggan->no_hp = $request->no_hp;
                    $pelanggan->alamat = $request->alamat;
                    $pelanggan->save();
                } elseif (in_array($user->level, ['admin', 'bendahara', 'owner'])) {
                    $karyawan = Karyawan::where('id_user', $user->id)->firstOrFail();
                    $karyawan->nama_karyawan = $request->nama_lengkap;
                    $karyawan->no_hp = $request->no_hp;
                    $karyawan->alamat = $request->alamat;
                    $karyawan->save();
                }
            }
            
            return redirect()->route('home')->with([
                'swal' => [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Profile updated successfully!',
                    'timer' => 1500
                ]
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'swal' => [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Failed to update profile: ' . $e->getMessage(),
                    'timer' => 3000
                ]
            ])->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
