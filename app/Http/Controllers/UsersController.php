<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with(['pelanggan', 'karyawan'])->get();
        return view('be.manage.index', [
            'users' => $users, 
            'greeting' => $this->getGreeting()
        ]);
    }

    public function create()
    {
        return view('be.manage.create', [
            'greeting' => $this->getGreeting()
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'level' => 'required',
            'aktif' => 'required|boolean',
            'name' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_hp' => 'required',
            'alamat' => 'required',
        ];

        // Validasi tambahan sesuai level
        if ($request->level == 'pelanggan') {
            $rules['nama_lengkap'] = 'required';
        } else {
            $rules['nama_karyawan'] = 'required';
            $rules['jabatan'] = 'required';
        }

        $data = $request->validate($rules);

        // Proses upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $directory = $request->level == 'pelanggan' ? 'pelanggans' : 'karyawans';
            $fotoPath = $request->file('foto')->store($directory, 'public');
        }

        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'level' => $data['level'],
            'aktif' => $data['aktif'],
            'name' => $data['name'],
            'no_hp' => $data['no_hp'],
        ]);
        
        // Buat data pelanggan/karyawan sesuai level
        if ($data['level'] == 'pelanggan') {
            Pelanggan::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
                'foto' => $fotoPath,
                'id_user' => $user->id
            ]);
        } else {
            Karyawan::create([
                'nama_karyawan' => $data['nama_karyawan'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
                'jabatan' => $data['jabatan'],
                'foto' => $fotoPath,
                'id_user' => $user->id
            ]);
        }

        return redirect()->route('user_manage')->with('success', 'User berhasil ditambah');
    }

    public function edit($id)
    {
        $user = User::with(['pelanggan', 'karyawan'])->findOrFail($id);
        return view('be.manage.edit', [
            'user' => $user, 
            'greeting' => $this->getGreeting()
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'email' => 'required|email',
            'level' => 'required',
            'aktif' => 'required|boolean',
            'name' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_hp' => 'required',
            'alamat' => 'required',
        ];

        if ($request->level == 'pelanggan') {
            $rules['nama_lengkap'] = 'required';
        } else {
            $rules['nama_karyawan'] = 'required';
            $rules['jabatan'] = 'required';
        }

        $data = $request->validate($rules);

        // Proses upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $directory = $user->level == 'pelanggan' ? 'pelanggans' : 'karyawans';
            
            // Hapus foto lama jika ada
            if ($user->pelanggan && $user->pelanggan->foto) {
                Storage::disk('public')->delete($user->pelanggan->foto);
            } elseif ($user->karyawan && $user->karyawan->foto) {
                Storage::disk('public')->delete($user->karyawan->foto);
            }
            
            $fotoPath = $request->file('foto')->store($directory, 'public');
        }

        $user->update([
            'email' => $data['email'],
            'level' => $data['level'],
            'aktif' => $data['aktif'],
            'name' => $data['name'],
            'no_hp' => $data['no_hp'],
        ]);

        // Update data pelanggan/karyawan
        if ($user->level == 'pelanggan') {
            $updateData = [
                'nama_lengkap' => $data['nama_lengkap'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
            ];
            if ($fotoPath) {
                $updateData['foto'] = $fotoPath;
            }
            $user->pelanggan->update($updateData);
        } else {
            $updateData = [
                'nama_karyawan' => $data['nama_karyawan'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
                'jabatan' => $data['jabatan'],
            ];
            if ($fotoPath) {
                $updateData['foto'] = $fotoPath;
            }
            $user->karyawan->update($updateData);
        }

        return redirect()->route('user_manage')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Hapus foto dan relasi
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
}