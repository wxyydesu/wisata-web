{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\penginapan\index.blade.php --}}
@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penginapan') }}">Penginapan</a></li>
        </ol>
    </nav>
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Penginapan Terbaru</h2>
            <p class="text-muted">Informasi terupdate seputar wisata dan travel</p>
        </div>
    </div>
    <div class="row">
        @forelse($penginapan as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <a href="{{ route('detail.penginapan', $item->id) }}">
                        <img src="{{ $item->foto1 ? asset('storage/'.$item->foto1) : 'https://source.unsplash.com/400x250/?hotel' }}" class="card-img-top" alt="{{ $item->nama_penginapan }}" style="height:200px; object-fit:cover;">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ $item->nama_penginapan }}</h5>
                        <div class="mb-2 text-muted" style="font-size:14px;">
                            <i class="bi bi-geo-alt"></i> {{ $item->lokasi ?? '-' }}
                        </div>
                        <div class="mb-2" style="font-size:15px;">
                            {{ \Illuminate\Support\Str::limit($item->deskripsi, 70) }}
                        </div>
                        @if(isset($item->harga_per_malam))
                        <div class="fw-bold text-primary mb-2">
                            Rp {{ number_format($item->harga_per_malam, 0, ',', '.') }} / malam
                        </div>
                        @endif
                        <a href="{{ route('detail.penginapan', $item->id) }}" class="btn btn-success btn-sm rounded-pill">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Tidak ada penginapan tersedia.</div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $penginapan->links() }}
    </div>
</div>
@endsection