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
                        <h4 class="card-title">{{ $greeting }}, Edit User</h4>
                        <p class="card-description">Form Edit Data User</p>

                        <form class="forms-sample" method="POST" action="{{ route('user_update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Basic Info --}}
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Name" name="name" required value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email" name="email" required value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="level">Role</label>
                                <select name="level" class="form-control @error('level') is-invalid @enderror" id="level" required onchange="toggleForm()">
                                    <option value="" disabled>Pilih Role</option>
                                    <option value="admin" {{ old('level', $user->level) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="bendahara" {{ old('level', $user->level) == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    <option value="owner" {{ old('level', $user->level) == 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="karyawan" {{ old('level', $user->level) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="pelanggan" {{ old('level', $user->level) == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                </select>
                                @error('level')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', $user->pelanggan->alamat ?? ($user->karyawan->alamat ?? '')) }}">
                                @error('alamat')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="aktif">Status</label>
                                <select name="aktif" class="form-control @error('aktif') is-invalid @enderror" id="aktif" required>
                                    <option value="1" {{ old('aktif', $user->aktif) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('aktif', $user->aktif) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('aktif')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Foto --}}
                            <div class="form-group">
                                <label>Foto Profil</label>
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                @error('foto')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                                
                                {{-- Tampilkan foto saat ini jika ada --}}
                                @if($user->pelanggan && $user->pelanggan->foto)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $user->pelanggan->foto) }}" width="100" class="img-thumbnail">
                                        <p class="text-muted">Current Photo</p>
                                    </div>
                                @elseif($user->karyawan && $user->karyawan->foto)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $user->karyawan->foto) }}" width="100" class="img-thumbnail">
                                        <p class="text-muted">Current Photo</p>
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
function toggleForm() {
    const level = document.getElementById('level').value;
    const karyawanFields = document.getElementById('karyawan-fields');
    const pelangganFields = document.getElementById('pelanggan-fields');
    
    // Hide all first
    karyawanFields.style.display = 'none';
    pelangganFields.style.display = 'none';

    // Show based on selection
    if (level === 'pelanggan') {
        document.getElementById('nama_lengkap').required = true;
        document.getElementById('nama_karyawan').required = false;
        document.getElementById('jabatan').required = false;
        pelangganFields.style.display = 'block';
    } else if (['admin', 'bendahara', 'owner', 'karyawan'].includes(level)) {
        document.getElementById('nama_karyawan').required = true;
        document.getElementById('jabatan').required = true;
        document.getElementById('nama_lengkap').required = false;
        karyawanFields.style.display = 'block';
    }
}
</script>
@endsection