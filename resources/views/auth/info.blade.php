@extends('auth.master')

@section('content')
    @php
        $user = Auth::user();
    @endphp
    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
        <h2>Lengkapi Data Profil Anda</h2>
        {{-- @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        <form action="{{ route('info.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->pelanggan->nama_lengkap ?? $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control" maxlength="15" value="{{ old('no_hp', $user->pelanggan->no_hp ?? $user->no_hp) }}" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="2">{{ old('alamat', $user->pelanggan->alamat ?? $user->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Profil</label>
                <input type="file" id="foto" name="foto" class="form-control">
                @if($user->pelanggan && $user->pelanggan->foto)
                    <img src="{{ asset('storage/' . $user->pelanggan->foto) }}" alt="Foto Profil" width="100" class="mt-2">
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
