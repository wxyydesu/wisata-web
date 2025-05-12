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
                        <h4 class="card-title">Tambah Objek Wisata</h4>
                        <p class="card-description">Form Tambah Data Objek Wisata</p>

                        <form class="forms-sample" method="POST" action="{{ route('objek_wisata_store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="nama_wisata">Nama Wisata</label>
                                <input type="text" class="form-control @error('nama_wisata') is-invalid @enderror" id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata') }}" required>
                                @error('nama_wisata')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="id_kategori_wisata">Kategori Wisata</label>
                                <select class="form-control @error('id_kategori_wisata') is-invalid @enderror" id="id_kategori_wisata" name="id_kategori_wisata" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('id_kategori_wisata') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kategori_wisata }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kategori_wisata')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="deskripsi_wisata">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi_wisata') is-invalid @enderror" id="deskripsi_wisata" name="deskripsi_wisata" rows="3" required>{{ old('deskripsi_wisata') }}</textarea>
                                @error('deskripsi_wisata')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fasilitas">Fasilitas</label>
                                <textarea class="form-control @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="3" required>{{ old('fasilitas') }}</textarea>
                                @error('fasilitas')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Foto 1</label>
                                <input type="file" name="foto1" class="form-control @error('foto1') is-invalid @enderror">
                                @error('foto1')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Foto 2</label>
                                <input type="file" name="foto2" class="form-control @error('foto2') is-invalid @enderror">
                                @error('foto2')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Foto 3</label>
                                <input type="file" name="foto3" class="form-control @error('foto3') is-invalid @enderror">
                                @error('foto3')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Foto 4</label>
                                <input type="file" name="foto4" class="form-control @error('foto4') is-invalid @enderror">
                                @error('foto4')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Foto 5</label>
                                <input type="file" name="foto5" class="form-control @error('foto5') is-invalid @enderror">
                                @error('foto5')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <button type="button" class="btn btn-light" onclick="window.history.back()">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection