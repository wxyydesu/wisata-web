<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        $greeting = $this->getGreeting();
        $users = User::with(['pelanggan', 'karyawan'])->get();
        return view('be.manage.index', [
            'users' => $users,
            'greeting' => $greeting
        ]);
    }

    public function create()
    {
        $greeting = $this->getGreeting();
        return view('be.manage.create', [
            'greeting' => $greeting
        ]);
    }

    public function edit($id)
    {
        $greeting = $this->getGreeting();
        $user = User::with(['pelanggan', 'karyawan'])->findOrFail($id);
        return view('be.manage.edit', [
            'user' => $user,
            'greeting' => $greeting
        ]);
    }

    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'level' => 'required|in:pelanggan,admin,bendahara,owner,karyawan',
            'aktif' => 'required|boolean',
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_hp' => 'required|string|max:15',
        ];

        // Additional validation based on level
        if ($request->level == 'pelanggan') {
            $rules['nama_lengkap'] = 'required|string|max:255';
            $rules['alamat'] = 'required|string|max:500';
        } else {
            $rules['nama_karyawan'] = 'required|string|max:255';
            $rules['alamat'] = 'required|string|max:500';
            $rules['jabatan'] = 'required|in:administrasi,bendahara,pemilik,karyawan';
        }

        $validated = $request->validate($rules);

        try {
            // Process photo upload
            $fotoPath = $this->handlePhotoUpload($request);

            // Create user
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'level' => $validated['level'],
                'aktif' => $validated['aktif'],
                'name' => $validated['name'],
                'no_hp' => $validated['no_hp'],
            ]);

            // Create related record
            $this->createRelatedRecord($user, $validated, $fotoPath);

            return redirect()->route('user_manage')->with('success', 'User berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan user: '.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Base validation rules
        $rules = [
            'email' => 'required|email|unique:users,email,'.$user->id,
            'level' => 'required|in:pelanggan,admin,bendahara,owner,karyawan',
            'aktif' => 'required|boolean',
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_hp' => 'required|string|max:15',
        ];

        // Additional validation based on level
        if ($request->level == 'pelanggan') {
            $rules['nama_lengkap'] = 'required|string|max:255';
            $rules['alamat'] = 'required|string|max:500';
        } else {
            $rules['nama_karyawan'] = 'required|string|max:255';
            $rules['alamat'] = 'required|string|max:500';
            $rules['jabatan'] = 'required|in:administrasi,bendahara,pemilik,karyawan';
        }

        $validated = $request->validate($rules);

        try {
            // Process photo upload
            $fotoPath = $this->handlePhotoUpload($request, $user);

            // Update user
            $user->update([
                'email' => $validated['email'],
                'level' => $validated['level'],
                'aktif' => $validated['aktif'],
                'name' => $validated['name'],
                'no_hp' => $validated['no_hp'],
            ]);

            // Update related record
            $this->updateRelatedRecord($user, $validated, $fotoPath);

            return redirect()->route('user_manage')->with('success', 'User berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui user: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Hapus relasi jika perlu
        if ($user->pelanggan) {
            if ($user->pelanggan->foto) {
                Storage::disk('public')->delete($user->pelanggan->foto);
            }
            $user->pelanggan->delete();
        }
        if ($user->karyawan) {
            if ($user->karyawan->foto) {
                Storage::disk('public')->delete($user->karyawan->foto);
            }
            $user->karyawan->delete();
        }
        $user->delete();
        return redirect()->route('user_manage')->with('success', 'User berhasil dihapus');
    }


    // PRIVATE FUNCTION START

    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        
        if ($hour < 12) {
            return 'Good Morning';
        } elseif ($hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }

    private function handlePhotoUpload(Request $request, ?User $user = null): ?string
    {
        $fotoPath = null;
        
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user) {
                if ($user->pelanggan && $user->pelanggan->foto) {
                    Storage::disk('public')->delete($user->pelanggan->foto);
                } elseif ($user->karyawan && $user->karyawan->foto) {
                    Storage::disk('public')->delete($user->karyawan->foto);
                }
            }
            
            $fotoPath = $request->file('foto')->store('user_photos', 'public');
        }
        
        return $fotoPath;
    }

    private function createRelatedRecord(User $user, array $validated, ?string $fotoPath): void
    {
        if ($validated['level'] == 'pelanggan') {
            Pelanggan::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'foto' => $fotoPath,
                'id_user' => $user->id
            ]);
        } else {
            Karyawan::create([
                'nama_karyawan' => $validated['nama_karyawan'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'jabatan' => $validated['jabatan'],
                'foto' => $fotoPath,
                'id_user' => $user->id
            ]);
        }
    }

    private function updateRelatedRecord(User $user, array $validated, ?string $fotoPath): void
    {
        $updateData = [
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
        ];

        if ($fotoPath) {
            $updateData['foto'] = $fotoPath;
        }

        if ($validated['level'] == 'pelanggan') {
            $updateData['nama_lengkap'] = $validated['nama_lengkap'];
            
            if ($user->pelanggan) {
                $user->pelanggan->update($updateData);
            } else {
                // If changing from karyawan to pelanggan
                if ($user->karyawan) {
                    $user->karyawan->delete();
                }
                Pelanggan::create(array_merge($updateData, ['id_user' => $user->id]));
            }
        } else {
            $updateData['nama_karyawan'] = $validated['nama_karyawan'];
            $updateData['jabatan'] = $validated['jabatan'];
            
            if ($user->karyawan) {
                $user->karyawan->update($updateData);
            } else {
                // If changing from pelanggan to karyawan
                if ($user->pelanggan) {
                    $user->pelanggan->delete();
                }
                Karyawan::create(array_merge($updateData, ['id_user' => $user->id]));
            }
        }
    }
}