{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\wisata\index.blade.php --}}
@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('objekwisata.front') }}">Wisata</a></li>
        </ol>
    </nav>
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Objek Wisata Terbaru</h2>
            <p class="text-muted">Informasi terupdate seputar wisata dan travel</p>
        </div>
    </div>
    <div class="row">
        @forelse($wisata as $wisata)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $wisata->foto1) }}" class="card-img-top" alt="{{ $wisata->nama_wisata }}" style="height:200px;object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $wisata->nama_wisata }}</h5>
                        <p class="card-text text-muted mb-2">
                            Kategori: {{ $wisata->kategoriWisata->nama_kategori ?? '-' }}
                        </p>
                        <p class="card-text" style="min-height:60px;">
                            {{ Str::limit($wisata->deskripsi_wisata, 60) }}
                        </p>
                        <a href="{{ route('detail.objekwisata', $wisata->id) }}" class="btn btn-dark btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada data objek wisata.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection