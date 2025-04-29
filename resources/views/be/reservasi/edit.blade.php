@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($user) ? 'Edit User' : 'Create User' }}</h4>
                        <p class="card-description">{{ isset($user) ? 'Update user information' : 'Fill the form to create a new user' }}</p>

                        <form class="forms-sample" 
                              method="POST" 
                              action="{{ route('user.update', $user->id) }}" 
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama">Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nama" 
                                       name="name" 
                                       placeholder="Name" 
                                       value="{{ old('name', $user->name ?? '') }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Email" 
                                       value="{{ old('email', $user->email ?? '') }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="no_hp">Phone Number</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="no_hp" 
                                       name="no_hp" 
                                       placeholder="Phone Number" 
                                       value="{{ old('no_hp', $user->no_hp ?? '') }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="exampleSelectGender">Role</label>
                                <select class="form-select" id="level" name="level" onchange="toggleJabatan()" required>
                                    <option selected disabled>Select Role</option>
                                    <option value="admin" {{ old('level', $user->level ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="bendahara" {{ old('level', $user->level ?? '') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    <option value="owner" {{ old('level', $user->level ?? '') == 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="karyawan" {{ old('level', $user->level ?? '') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="pelanggan"{{ old('level', $user->level ?? '') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                </select>
                                @error('level')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group" id="jabatan-wrapper" style="display:none;">
                                <label for="jabatan">Jabatan</label>
                                <select class="form-select" name="jabatan" id="jabatan">
                                    <option selected disabled>Select Jabatan</option>
                                    <option value="administrasi" {{ old('jabatan') == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                                    <option value="bendahara" {{ old('jabatan') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    <option value="pemilik" {{ old('jabatan') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                                </select>
                                @error('jabatan')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="alamat">Address</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="alamat" 
                                       name="alamat" 
                                       placeholder="Address" 
                                       value="{{ old('alamat', $user->alamat ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label for="foto">Image Profile Upload</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="foto" 
                                       name="foto">

                                @if(!empty($user->foto))
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $user->foto) }}" 
                                             alt="User Profile" 
                                             width="100" 
                                             height="100" 
                                             style="object-fit: cover; border-radius: 8px;">
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleJabatan() {
        const level = document.getElementById('level').value;
        const jabatanWrapper = document.getElementById('jabatan-wrapper');
        if (level === 'karyawan') {
            jabatanWrapper.style.display = 'block';
        } else {
            jabatanWrapper.style.display = 'none';
        }
    }
    
    // Biar pas reload/edit tetep bener tampilin jabatannya
    document.addEventListener('DOMContentLoaded', function() {
        toggleJabatan();
    });
    </script>
@endsection
