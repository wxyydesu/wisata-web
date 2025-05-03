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
                        <h4 class="card-title">Tambah User</h4>
                        <p class="card-description">Form Tambah Data User</p>

                        <form class="forms-sample" method="POST" action="{{ route('user_store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Basic Info --}}
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Name" name="name" required value="{{ old('name') }}">
                                @error('name')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email" name="email" required value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="form-group">
                                <label for="no_hp">No HP</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" placeholder="No HP" name="no_hp" required value="{{ old('no_hp') }}">
                                @error('no_hp')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="aktif">Status</label>
                                <select name="aktif" class="form-control @error('aktif') is-invalid @enderror" id="aktif" required>
                                    <option value="1" {{ old('aktif', 1) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('aktif') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                @error('aktif')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Role Selection --}}
                            <div class="form-group">
                                <label for="level">Role</label>
                                <select name="level" class="form-control @error('level') is-invalid @enderror" id="level" required onchange="toggleForm()">
                                    <option value="" disabled selected>Pilih Role</option>
                                    <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="bendahara" {{ old('level') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    <option value="owner" {{ old('level') == 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="karyawan" {{ old('level') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="pelanggan" {{ old('level') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                </select>
                                @error('level')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Alamat (Common for all) --}}
                            <div class="form-group">
                                <label for="no_hp">No HP</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? ($user->pelanggan->no_hp ?? ($user->karyawan->no_hp ?? ''))) }}">
                                @error('no_hp')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Karyawan Specific Fields --}}
                            <div id="karyawan-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="nama_karyawan">Nama Karyawan</label>
                                    <input type="text" class="form-control @error('nama_karyawan') is-invalid @enderror" id="nama_karyawan" name="nama_karyawan" value="{{ old('nama_karyawan') }}">
                                    @error('nama_karyawan')
                                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jabatan">Jabatan</label>
                                    <select name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan">
                                        <option value="" disabled selected>Pilih Jabatan</option>
                                        <option value="administrasi" {{ old('jabatan') == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                                        <option value="bendahara" {{ old('jabatan') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                        <option value="pemilik" {{ old('jabatan') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                                    </select>
                                    @error('jabatan')
                                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Pelanggan Specific Fields --}}
                            <div id="pelanggan-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                                    @error('nama_lengkap')
                                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Foto --}}
                            <div class="form-group">
                                <label>Foto Profil</label>
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                @error('foto')
                                    <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                            </div>

                            <button type="submit" class="btn btn-primary me-2">Submit</button>
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

// Initialize form on load
document.addEventListener('DOMContentLoaded', function() {
    // If returning with validation errors, show appropriate fields
    const level = document.getElementById('level').value;
    if (level) {
        toggleForm();
    }
});
</script>
@endsection