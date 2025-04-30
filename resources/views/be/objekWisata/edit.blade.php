@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Objek Wisata</h4>
                        <p class="card-description">
                            Perbarui data objek wisata
                        </p>

                        <form class="forms-sample" action="{{ route('objek_wisata.update', $objekWisata->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama">Nama Objek Wisata</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $objekWisata->nama) }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori">
                                    <option value="">Pilih Kategori</option>
                                    <option value="Alam" {{ old('kategori', $objekWisata->kategori) == 'Alam' ? 'selected' : '' }}>Alam</option>
                                    <option value="Budaya" {{ old('kategori', $objekWisata->kategori) == 'Budaya' ? 'selected' : '' }}>Budaya</option>
                                    <option value="Sejarah" {{ old('kategori', $objekWisata->kategori) == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                                    <option value="Religi" {{ old('kategori', $objekWisata->kategori) == 'Religi' ? 'selected' : '' }}>Religi</option>
                                    <option value="Kuliner" {{ old('kategori', $objekWisata->kategori) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $objekWisata->lokasi) }}">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="harga_tiket">Harga Tiket (Rp)</label>
                                        <input type="number" class="form-control @error('harga_tiket') is-invalid @enderror" id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket', $objekWisata->harga_tiket) }}">
                                        @error('harga_tiket')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_operasional">Jam Operasional</label>
                                        <input type="text" class="form-control @error('jam_operasional') is-invalid @enderror" id="jam_operasional" name="jam_operasional" value="{{ old('jam_operasional', $objekWisata->jam_operasional) }}">
                                        @error('jam_operasional')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="gambar">Gambar Objek Wisata</label>
                                <input type="file" class="form-control-file @error('gambar') is-invalid @enderror" id="gambar" name="gambar">
                                @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($objekWisata->gambar)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $objekWisata->gambar) }}" alt="Gambar Wisata" style="max-width: 200px;">
                                        <p class="text-muted">Gambar saat ini</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $objekWisata->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fasilitas">Fasilitas</label>
                                <textarea class="form-control @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="3">{{ old('fasilitas', $objekWisata->fasilitas) }}</textarea>
                                @error('fasilitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">Perbarui</button>
                            <a href="{{ route('objek_wisata.index') }}" class="btn btn-light">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection