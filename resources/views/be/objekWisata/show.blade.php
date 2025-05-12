@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Detail Objek Wisata</h4>
                    <a href="{{ route('objek_wisata_manage') }}" class="btn btn-primary">
                        <i class="fa fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h3>{{ $obyekWisata->nama_objek }}</h3>
                            <p class="text-muted">{{ $obyekWisata->alamat }}</p>
                        </div>

                        <div class="mb-4">
                            <h5>Fasilitas:</h5>
                            <div class="bg-light p-3 rounded">
                                {!! $obyekWisata->fasilitas !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Deskripsi:</h5>
                            <p class="text-justify">{!! $obyekWisata->deskripsi_wisata !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Foto Objek Wisata</h5>
                        <div class="row">
                            @for($i = 1; $i <= 5; $i++)
                                @php $foto = 'foto'.$i; @endphp
                                @if($obyekWisata->$foto)
                                    <div class="col-md-6 mb-3">
                                        <img 
                                            src="{{ asset('storage/' . $obyekWisata->$foto) }}" 
                                            alt="Foto {{ $i }}" 
                                            class="img-fluid rounded shadow-sm"
                                            style="cursor: pointer;"
                                            onclick="showImageModal('{{ asset('storage/' . $obyekWisata->$foto) }}')"
                                        >
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Informasi Tambahan</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Harga Tiket:</span>
                                <span class="fw-bold">Rp {{ number_format($obyekWisata->harga_tiket, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Kategori:</span>
                                <span class="fw-bold">{{ $obyekWisata->kategori }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Jam Operasional:</span>
                                <span class="fw-bold">{{ $obyekWisata->jam_operasional }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Status:</span>
                                <span class="badge bg-{{ $obyekWisata->status == 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($obyekWisata->status) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Preview">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endsection
