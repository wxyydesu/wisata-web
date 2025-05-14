@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="detail-image">
                <img src="{{ asset('storage/' . $penginapan->foto1) }}" alt="{{ $penginapan->nama_penginapan }}" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="detail-title">{{ $penginapan->nama_penginapan }}</h2>
            <p class="detail-location"><i class="bi bi-geo-alt"></i> {{ $penginapan->lokasi ?? 'Lokasi tidak tersedia' }}</p>
            <p class="detail-description">
                {{ $penginapan->deskripsi }}
            </p>
            <p class="detail-price text-primary">Rp {{ number_format($penginapan->harga_per_malam, 0, ',', '.') }} / malam</p>
            <button class="btn btn-success btn-lg">Pesan Sekarang</button>
        </div>
    </div>
    <div class="row mt-5">
        <h3 class="section-title">Fasilitas</h3>
        <ul class="list-unstyled">
            @foreach(explode(',', $penginapan->fasilitas) as $fasilitas)
                <li><i class="bi bi-check-circle"></i> {{ trim($fasilitas) }}</li>
            @endforeach
        </ul>
    </div>
    <div class="row mt-5">
        <h3 class="section-title">Ulasan</h3>
        @forelse($penginapan->ulasan as $ulasan)
            <div class="review">
                <p><strong>{{ $ulasan->user->name }}</strong> <span class="text-warning"><i class="bi bi-star-fill"></i> {{ $ulasan->rating }}</span></p>
                <p>{{ $ulasan->komentar }}</p>
            </div>
        @empty
            <p>Belum ada ulasan untuk penginapan ini.</p>
        @endforelse
    </div>
</div>
@endsection