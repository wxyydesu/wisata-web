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

            <form class="forms-sample" method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Nama --}}
                <div class="form-group">
                    <label for="exampleInputName1">Name</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Name" name="name" required value="{{ old('name') }}">
                    @error('name')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="exampleInputEmail3">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Email" name="email" required value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- No HP --}}
                <div class="form-group">
                    <label for="exampleInputPhone1">Phone Number</label>
                    <input type="text" name="no_hp" class="form-control" id="exampleInputPhone1" placeholder="Phone Number" required value="{{ old('no_hp') }}">
                    @error('no_hp')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="exampleInputPassword4">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword4" placeholder="Password" name="password" required>
                    @error('password')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation" required>
                    @error('password_confirmation')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="form-group">
                    <label for="exampleSelectGender">Role</label>
                    <select class="form-select" id="level" name="level" onchange="toggleJabatan()" required>
                        <option selected disabled>Select Role</option>
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

                {{-- Jabatan (Muncul kalau admin, bendahara, owner) --}}
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

                {{-- Foto --}}
                <div class="form-group">
                    <label>Image Profile Upload</label>
                    <input type="file" name="foto" class="form-control">
                    @error('foto')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label for="exampleInputCity1">Address</label>
                    <input type="text" class="form-control" id="exampleInputCity1" placeholder="Location" name="alamat" required value="{{ old('alamat') }}">
                    @error('alamat')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
            </form>

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
