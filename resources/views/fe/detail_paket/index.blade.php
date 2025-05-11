@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div id="packageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach([$paket->foto1, $paket->foto2, $paket->foto3, $paket->foto4, $paket->foto5] as $index => $foto)
                            @if($foto)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $foto) }}" class="d-block w-100" alt="Foto Paket {{ $index + 1 }}">
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                
                <div class="card-body">
                    <h1 class="card-title">{{ $paket->nama_paket }}</h1>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="rating">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                            <span class="ms-1">4.8 (120 reviews)</span>
                        </div>
                        <div>
                            <i class="fas fa-share-alt text-muted me-2"></i>
                            <i class="far fa-heart text-muted"></i>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Deskripsi Paket</h4>
                    <p class="card-text">{{ $paket->deskripsi }}</p>
                    
                    <h4 class="mb-3">Fasilitas</h4>
                    <div class="row">
                        @foreach(explode("\n", $paket->fasilitas) as $fasilitas)
                            @if(trim($fasilitas))
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i> {{ trim($fasilitas) }}
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-3">Paket Lainnya</h4>
                    <div class="row">
                        @foreach($related as $relatedPaket)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <img src="{{ $relatedPaket->foto1 ? asset('storage/' . $relatedPaket->foto1) : asset('fe/images/default-package.jpg') }}" class="card-img-top" alt="{{ $relatedPaket->nama_paket }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedPaket->nama_paket }}</h5>
                                    <p class="card-text text-primary fw-bold">Rp {{ number_format($relatedPaket->harga_per_pack, 0, ',', '.') }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="{{ route('detail-paket', $relatedPaket->id) }}" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h3 class="card-title">Pesan Sekarang</h3>
                    <div class="mb-3">
                        <span class="text-muted">Harga mulai dari</span>
                        <h2 class="text-primary">Rp {{ number_format($paket->harga_per_pack, 0, ',', '.') }}</h2>
                        <small class="text-muted">*Harga per paket</small>
                    </div>
                    
                    <form id="reservationForm" action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_paket" value="{{ $paket->id }}">
                        
                        <div class="mb-3">
                            <label for="tgl_reservasi" class="form-label">Tanggal Reservasi</label>
                            <input type="date" class="form-control" id="tgl_reservasi" name="tgl_reservasi" required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                            <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan Khusus</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i> Pesan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Calculate total price when jumlah peserta changes
        $('#jumlah_peserta').on('change', function() {
            const hargaPerPack = {{ $paket->harga_per_pack }};
            const jumlahPeserta = $(this).val();
            const totalHarga = hargaPerPack * jumlahPeserta;
            
            $('#totalHarga').text('Rp ' + totalHarga.toLocaleString('id-ID'));
        });
    });
</script>
@endpush