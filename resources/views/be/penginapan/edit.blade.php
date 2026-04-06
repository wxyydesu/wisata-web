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
                        <h4 class="card-title">Edit Data Penginapan</h4>
                        <p class="card-description">
                            Formulir pengeditan data penginapan
                        </p>

                        <form class="forms-sample" method="POST" action="{{ route('penginapan.update', $penginapan->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Nama Penginapan</label>
                                <input type="text" name="nama_penginapan" class="form-control" 
                                       value="{{ old('nama_penginapan', $penginapan->nama_penginapan) }}" 
                                       placeholder="Masukkan nama penginapan" required>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" 
                                          placeholder="Masukkan deskripsi penginapan" required>{{ old('deskripsi', $penginapan->deskripsi) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Fasilitas</label>
                                <textarea name="fasilitas" class="form-control" rows="3" 
                                          placeholder="Masukkan fasilitas yang tersedia" required>{{ old('fasilitas', $penginapan->fasilitas) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" 
                                       value="{{ old('lokasi', $penginapan->lokasi) }}" 
                                       placeholder="Masukkan lokasi penginapan" required>
                            </div>

                            <div class="form-group">
                                <label>Harga per Malam (Rp)</label>
                                <input type="number" name="harga_per_malam" class="form-control" 
                                       value="{{ old('harga_per_malam', $penginapan->harga_per_malam) }}" 
                                       placeholder="Masukkan harga per malam" min="0" required>
                            </div>

                            <div class="form-group">
                                <label>Kapasitas (Jumlah Kamar)</label>
                                <input type="number" name="kapasitas" class="form-control" 
                                       value="{{ old('kapasitas', $penginapan->kapasitas) }}" 
                                       placeholder="Masukkan kapasitas kamar" min="1" required>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="tersedia" {{ old('status', $penginapan->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="tidak tersedia" {{ old('status', $penginapan->status) === 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                </select>
                            </div>

                            @for($i = 1; $i <= 5; $i++)
                                @php 
                                    $foto = 'foto'.$i;
                                    $deleteField = 'delete_foto'.$i;
                                @endphp
                                <div class="form-group">
                                    <label>Foto {{ $i }}</label>
                                    <input type="file" name="{{ $foto }}" class="form-control @error($foto) is-invalid @enderror">
                                    @error($foto)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($penginapan->$foto)
                                        <div class="mt-2 photo-container" data-foto="{{ $foto }}">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $penginapan->$foto) }}" alt="Foto {{ $i }}" style="max-width: 200px; margin-right: 15px;">
                                                <button type="button" class="btn btn-danger btn-sm delete-photo" data-field="{{ $deleteField }}">
                                                    <i class="mdi mdi-delete"></i> Hapus Foto
                                                </button>
                                            </div>
                                            <input type="hidden" name="{{ $deleteField }}" id="{{ $deleteField }}" value="0">
                                        </div>
                                    @endif
                                </div>
                            @endfor
                            
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <a href="{{ route('penginapan.index') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imgPreview" src="" alt="Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function showImgPreview(src) {
    document.getElementById('imgPreview').src = src;
    var modal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    modal.show();
}
</script>
@endsection