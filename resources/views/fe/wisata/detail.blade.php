{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\wisata\detail.blade.php --}}
@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('objekwisata.front') }}">Wisata</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($obyekWisata->nama_obyek_wisata ?? $obyekWisata->nama_wisata, 30) }}</li>
                </ol>
            </nav>
            <div class="detail-image mb-3">
                <img src="{{ asset('storage/' . ($obyekWisata->foto1 ?? '')) }}" alt="{{ $obyekWisata->nama_obyek_wisata ?? $obyekWisata->nama_wisata }}" class="img-fluid rounded">
            </div>
            <div class="row">
                @foreach (['foto2','foto3','foto4','foto5'] as $foto)
                    @if (!empty($obyekWisata->$foto))
                        <div class="col-4 mb-2">
                            <img src="{{ asset('storage/' . $obyekWisata->$foto) }}" class="img-fluid rounded" alt="Foto Wisata">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="detail-title">{{ $obyekWisata->nama_obyek_wisata ?? $obyekWisata->nama_wisata }}</h2>
            <p class="text-muted">
                Kategori: {{ $obyekWisata->kategoriWisata->nama_kategori ?? '-' }}
            </p>
            <p class="detail-description">
                {{ $obyekWisata->deskripsi_wisata }}
            </p>
            <h5 class="mt-4">Fasilitas</h5>
            <ul class="list-unstyled">
                @foreach(explode(',', $obyekWisata->fasilitas) as $fasilitas)
                    <li><i class="bi bi-check-circle"></i> {{ trim($fasilitas) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <a href="{{ route('objekwisata.front') }}" class="btn btn-dark mt-4">Kembali</a>
</div>

{{-- Related Paket Section --}}
@if(isset($relatedPaket) && count($relatedPaket) > 0)
<div class="container pb-5">
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="fw-bold">Paket Terkait</h4>
        </div>
        @foreach($relatedPaket as $rel)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <a href="{{ route('paket.detail', $rel->id) }}" class="text-decoration-none text-dark">
                    <img src="{{ asset('storage/' . $rel->foto1) }}" class="card-img-top" alt="{{ $rel->nama_paket }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $rel->nama_paket }}</h6>
                        <div class="small text-muted mb-2">
                            <i class="fas fa-clock me-1"></i> {{ $rel->durasi }} Hari
                        </div>
                        <div class="fw-bold text-primary">Rp{{ number_format($rel->harga, 0, ',', '.') }}</div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection