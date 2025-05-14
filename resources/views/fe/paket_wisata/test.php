<!-- resources/views/fe/paket_wisata/index.blade.php -->
@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Paket Wisata</h2>
            <p class="text-muted">Temukan paket wisata menarik untuk liburan Anda</p>
        </div>
    </div>

    <div class="row">
        @foreach($paketWisata as $paket)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $paket->foto1 ? asset('storage/' . $paket->foto1) : asset('images/default-paket.jpg') }}" class="card-img-top" alt="{{ $paket->nama_paket }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $paket->nama_paket }}</h5>
                    <p class="card-text text-success fw-bold">Rp {{ number_format($paket->harga_per_pack, 0, ',', '.') }}</p>
                    <p class="card-text text-muted">{{ Str::limit($paket->deskripsi, 100) }}</p>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('detail-paket', $paket->id) }}" class="btn btn-primary w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            {{ $paketWisata->links() }}
        </div>
    </div>
</div>
@endsection