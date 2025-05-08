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

                        <form class="forms-sample" method="POST" action="{{ route('penginapan_update', $penginapan->id) }}" enctype="multipart/form-data">
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
                                <label>Foto Saat Ini</label>
                                <div class="row mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @php $foto = 'foto'.$i; @endphp
                                        @if($penginapan->$foto)
                                        <div class="col-md-4 mb-2">
                                            <img src="{{ asset('storage/'.$penginapan->$foto) }}" 
                                                 class="img-thumbnail" 
                                                 width="150"
                                                 onclick="showImgPreview('{{ asset('storage/'.$penginapan->$foto) }}')">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" 
                                                       name="hapus_foto{{ $i }}" id="hapus_foto{{ $i }}">
                                                <label class="form-check-label" for="hapus_foto{{ $i }}">
                                                    Hapus foto ini
                                                </label>
                                            </div>
                                            <input type="hidden" name="foto_lama{{ $i }}" value="{{ $penginapan->$foto }}">
                                        </div>
                                        @endif
                                    @endfor
                                </div>

                                <label>Upload Foto Baru</label>
                                <div class="row">
                                    @for($i = 1; $i <= 5; $i++)
                                    <div class="col-md-4 mb-3">
                                        <input type="file" class="form-control" name="foto{{ $i }}" accept="image/*">
                                        <small class="text-muted">Foto {{ $i }} (Opsional)</small>
                                    </div>
                                    @endfor
                                </div>
                            </div>
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
                            <a href="{{ route('penginapan_manage') }}" class="btn btn-light">Cancel</a>
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