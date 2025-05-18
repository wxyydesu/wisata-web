@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-5">
    <h2>Lengkapi Data Profil Anda</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', Auth::user()->pelanggan->nama_lengkap ?? Auth::user()->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control" maxlength="15" value="{{ old('no_hp', Auth::user()->pelanggan->no_hp ?? Auth::user()->no_hp) }}" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', Auth::user()->pelanggan->alamat ?? Auth::user()->alamat) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto Profil</label>
            <input type="file" name="foto" class="form-control">
            @if(Auth::user()->pelanggan && Auth::user()->pelanggan->foto)
                <img src="{{ asset('storage/' . Auth::user()->pelanggan->foto) }}" alt="Foto Profil" width="100" class="mt-2">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
