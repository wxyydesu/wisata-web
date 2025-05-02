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
                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" required value="{{ old('name') }}">
                    @error('name')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                    @error('password')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="aktif">Status</label>
                    <select name="aktif" class="form-control" id="aktif" required>
                        <option value="1" {{ old('aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('aktif') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                {{-- Role Selection --}}
                <div class="form-group">
                    <label for="level">Role</label>
                    <select name="level" class="form-control" id="level" required onchange="toggleForm()">
                        <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="bendahara" {{ old('level') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                        <option value="pemilik" {{ old('level') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                        <option value="pelanggan" {{ old('level') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                    </select>
                </div>

                {{-- Karyawan Fields --}}
                <div id="karyawan-fields" style="display: none;">
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="{{ old('nama_karyawan') }}">
                    </div>
                    <div class="form-group">
                        <label for="no_hp_karyawan">No HP</label>
                        <input type="text" class="form-control" id="no_hp_karyawan" name="no_hp_karyawan" value="{{ old('no_hp_karyawan') }}">
                    </div>
                    <div class="form-group">
                        <label for="alamat_karyawan">Alamat</label>
                        <input type="text" class="form-control" id="alamat_karyawan" name="alamat_karyawan" value="{{ old('alamat_karyawan') }}">
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <select name="jabatan" class="form-control" id="jabatan">
                            <option value="administrasi" {{ old('jabatan') == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="bendahara" {{ old('jabatan') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                            <option value="pemilik" {{ old('jabatan') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                        </select>
                    </div>
                </div>

                {{-- Pelanggan Fields --}}
                <div id="pelanggan-fields" style="display: none;">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                    </div>
                    <div class="form-group">
                        <label for="no_hp_pelanggan">No HP</label>
                        <input type="text" class="form-control" id="no_hp_pelanggan" name="no_hp_pelanggan" value="{{ old('no_hp_pelanggan') }}">
                    </div>
                    <div class="form-group">
                        <label for="alamat_pelanggan">Alamat</label>
                        <input type="text" class="form-control" id="alamat_pelanggan" name="alamat_pelanggan" value="{{ old('alamat_pelanggan') }}">
                    </div>
                </div>

                {{-- Foto --}}
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
            </form>

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
    
    if (level === 'pelanggan') {
        pelangganFields.style.display = 'block';
        karyawanFields.style.display = 'none';
    } else {
        pelangganFields.style.display = 'none';
        karyawanFields.style.display = 'block';
    }
}

// Initialize form on load
document.addEventListener('DOMContentLoaded', function() {
    toggleForm();
});
</script>
@endsection