<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelanggan;
use App\Models\Karyawan;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Auth::user();
        
        return view('fe.profile.index', [
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        
        // Handle upload foto
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists($user->foto)) {
                Storage::delete($user->foto);
            }
            $path = $request->file('foto')->store('profile-photos');
            $user->foto = $path;
        }
        
        $user->save();
        
        // Update data tambahan berdasarkan level
        if ($user->level === 'pelanggan') {
            $pelanggan = Pelanggan::where('id_user', $user->id)->firstOrFail();
            $pelanggan->nama_lengkap = $request->name;
            $pelanggan->no_hp = $request->no_hp;
            $pelanggan->alamat = $request->alamat;
            
            if ($request->hasFile('foto')) {
                if ($pelanggan->foto && Storage::exists($pelanggan->foto)) {
                    Storage::delete($pelanggan->foto);
                }
                $pelanggan->foto = $path;
            }
            
            $pelanggan->save();
        } elseif (in_array($user->level, ['admin', 'bendahara', 'owner'])) {
            $karyawan = Karyawan::where('id_user', $user->id)->firstOrFail();
            $karyawan->nama_karyawan = $request->name;
            $karyawan->no_hp = $request->no_hp;
            $karyawan->alamat = $request->alamat;
            
            if ($request->hasFile('foto')) {
                if ($karyawan->foto && Storage::exists($karyawan->foto)) {
                    Storage::delete($karyawan->foto);
                }
                $karyawan->foto = $path;
            }
            
            $karyawan->save();
        }
        
            return redirect()->route('profile.index')->with([
                'swal' => [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'profile updated!',
                    'timer' => 1500
                ]
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'swal' => [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Fail to update profile: ' . $e->getMessage(),
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
