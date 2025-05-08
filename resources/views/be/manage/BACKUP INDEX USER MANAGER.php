Method Illuminate\Database\Eloquent\Collection::hasPages does not exist.

userscontroller untuk user manager
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
        $users = User::with(['pelanggan', 'karyawan'])->latest()->get();
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

index.blade.php :
@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">{{ $greeting }}, User Management</h4>
            <a href="{{ route('user_create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add User
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Photo</th>
                                        <th width="15%">Name</th>
                                        <th width="15%">Email</th>
                                        <th width="10%">Phone</th>
                                        <th width="15%">Role</th>
                                        <th width="15%">Address</th>
                                        <th width="5%">Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                
                                        {{-- Photo --}}
                                        <td>
                                            @php
                                                $photo = null;
                                                if ($user->level == 'pelanggan' && $user->pelanggan && $user->pelanggan->foto) {
                                                    $photo = asset('storage/' . $user->pelanggan->foto);
                                                } elseif ($user->karyawan && $user->karyawan->foto) {
                                                    $photo = asset('storage/' . $user->karyawan->foto);
                                                } else {
                                                    $photo = asset('images/default-user.jpg');
                                                }
                                            @endphp
                                            <img src="{{ $photo }}" 
                                                 alt="User Photo" 
                                                 class="rounded-circle border"
                                                 width="40" 
                                                 height="40"
                                                 style="cursor:pointer; object-fit: cover;"
                                                 onclick="showImgPreview('{{ $photo }}')">
                                        </td>
                                
                                        {{-- Name --}}
                                        <td>{{ $user->name }}</td>

                                        {{-- Email --}}
                                        <td>{{ $user->email }}</td>
                                
                                        {{-- Phone --}}
                                        <td>{{ $user->no_hp ?? '-' }}</td>
                                
                                        {{-- Role --}}
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ ucfirst($user->level) }}
                                            </span>
                                            @if (in_array($user->level, ['admin', 'bendahara', 'owner']) && $user->karyawan)
                                                <br>
                                                <small class="text-muted">
                                                    {{ ucfirst($user->karyawan->jabatan) }}
                                                </small>
                                            @endif
                                        </td>
                                
                                        {{-- Address --}}
                                        <td>
                                            @if($user->level == 'pelanggan' && $user->pelanggan)
                                                {{ Str::limit($user->pelanggan->alamat ?? '-', 30) }}
                                            @elseif($user->karyawan)
                                                {{ Str::limit($user->karyawan->alamat ?? '-', 30) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            <span class="badge bg-{{ $user->aktif ? 'success' : 'danger' }}">
                                                {{ $user->aktif ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                
                                        {{-- Actions --}}
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('user_edit', $user->id) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('user_destroy', $user->id) }}" 
                                                      method="POST" 
                                                      class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger delete-btn"
                                                            title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                                <h5>No users found</h5>
                                                <p class="text-muted">Click the "Add User" button to create a new user</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($users->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imgPreview" src="" alt="Preview" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Image preview function
function showImgPreview(src) {
    const previewImg = document.getElementById('imgPreview');
    previewImg.src = src;
    
    const modal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    modal.show();
}

// Delete confirmation
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const form = this.closest('form');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection