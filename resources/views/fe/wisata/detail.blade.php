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
@endsection