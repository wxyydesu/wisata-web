<!-- resources/views/fe/berita/index.blade.php -->
@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Berita Terbaru</h2>
            <p class="text-muted">Informasi terupdate seputar wisata dan travel</p>
        </div>
    </div>

    <div class="row">
        @foreach($berita as $b)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $b->foto ? asset('storage/' . $b->foto) : asset('images/default-berita.jpg') }}" 
                     class="card-img-top" 
                     alt="{{ $b->judul }}" 
                     style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">
                            <i class="far fa-calendar-alt me-1"></i> 
                            {{ \Carbon\Carbon::parse($b->tgl_post)->format('d M Y') }}
                        </small>
                        <small class="text-primary">{{ $b->kategori->nama_kategori ?? 'Umum' }}</small>
                    </div>
                    <h5 class="card-title">{{ $b->judul }}</h5>
                    <p class="card-text">{{ Str::limit(strip_tags($b->berita), 100) }}</p>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('detail-berita', $b->id) }}" class="btn btn-outline-primary w-100">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            {{ $berita->links() }}
        </div>
    </div>
</div>
@endsection