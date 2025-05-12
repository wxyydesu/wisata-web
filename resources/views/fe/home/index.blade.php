@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection
@section('slider')
    @include('fe.slider')
@endsection

@section('content')
<div class="main">
    <div class="container py-5">
        <!-- Paket Wisata Section -->
        <section class="mb-5">
            <h2 class="section-title">Paket Wisata Populer</h2>
            <div class="package-container">
                @foreach($paketWisata as $paket)
                <div class="package-card">
                    <div class="package-image">
                        <img src="{{ $paket->foto1 ? asset('storage/' . $paket->foto1) : asset('fe/images/default-package.jpg') }}" alt="{{ $paket->nama_paket }}">
                    </div>
                    <div class="package-details">
                        <h3 class="package-title">{{ $paket->nama_paket }}</h3>
                        <div class="package-price">Rp {{ number_format($paket->harga_per_pack, 0, ',', '.') }}</div>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 3 Hari 2 Malam</span>
                            <span class="package-rating"><i class="fas fa-star"></i> 4.8</span>
                        </div>
                        <a href="{{ route('detail-paket', $paket->id) }}" class="book-now">Lihat Detail</a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('package') }}" class="btn btn-outline-primary">Lihat Semua Paket</a>
            </div>
        </section>
        
        <!-- Penginapan Section -->
        <section class="mb-5">
            <h2 class="section-title">Penginapan Terbaik</h2>
            <div class="package-container">
                @foreach($penginapan as $penginapan)
                <div class="package-card">
                    <div class="package-image">
                        <img src="{{ $penginapan->foto1 ? asset('storage/' . $penginapan->foto1) : asset('fe/images/default-hotel.jpg') }}" alt="{{ $penginapan->nama_penginapan }}">
                    </div>
                    <div class="package-details">
                        <h3 class="package-title">{{ $penginapan->nama_penginapan }}</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-map-marker-alt"></i> Lokasi Strategis</span>
                            <span class="package-rating"><i class="fas fa-star"></i> 4.5</span>
                        </div>
                        <p class="text-muted mb-2">{{ Str::limit($penginapan->deskripsi, 100) }}</p>
                        <a href="{{ route('detail-penginapan', $penginapan->id) }}" class="book-now">Lihat Detail</a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('penginapan') }}" class="btn btn-outline-primary">Lihat Semua Penginapan</a>
            </div>
        </section>
        
        <!-- Kategori Wisata Section -->
        <section>
            <h2 class="section-title">Kategori Wisata</h2>
            <div class="row">
                @foreach($kategoriWisata as $kategori)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-mountain fa-3x text-primary mb-3"></i>
                            <h3 class="card-title">{{ $kategori->kategori_wisata }}</h3>
                            <p class="card-text">{{ Str::limit($kategori->deskripsi, 100) }}</p>
                            <a href="{{ route('obyek-wisata') }}" class="btn btn-sm btn-outline-primary">Jelajahi</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection