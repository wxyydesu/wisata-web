@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="main">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Pesanan</h2>
            <div class="d-flex">
                <button class="btn btn-outline-secondary me-2">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-download me-1"></i> Export
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Order Cards (Mobile View) -->
        <div class="d-block d-md-none">
            @foreach($reservasis as $reservasi)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between">
                    <span class="fw-bold">#{{ $reservasi->id }}</span>
                    <span class="badge bg-{{ $reservasi->status_reservasi == 'pesan' ? 'warning' : 'success' }}">
                        {{ $reservasi->status_reservasi }}
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $reservasi->paketWisata->nama_paket }}</h5>
                    <p class="card-text text-muted mb-1">
                        <i class="far fa-calendar-alt me-2"></i>
                        {{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->format('d M Y') }}
                    </p>
                    <p class="card-text text-muted mb-2">
                        <i class="fas fa-users me-2"></i>
                        {{ $reservasi->jumlah_peserta }} orang
                    </p>
                    <h5 class="text-primary mb-3">
                        Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}
                    </h5>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pesanan.detail', $reservasi->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-print me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Table (Desktop View) -->
        <div class="d-none d-md-block">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Paket Wisata</th>
                                    <th>Tanggal</th>
                                    <th>Peserta</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservasis as $reservasi)
                                <tr>
                                    <td class="fw-bold">#{{ $reservasi->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $reservasi->paketWisata->foto1) }}" 
                                                 class="rounded me-3" width="60" height="40" style="object-fit: cover;">
                                            <span>{{ $reservasi->paketWisata->nama_paket }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->format('d M Y') }}<br>
                                        <small class="text-muted">{{ $reservasi->lama_reservasi }} hari</small>
                                    </td>
                                    <td>{{ $reservasi->jumlah_peserta }} orang</td>
                                    <td class="fw-bold">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reservasi->status_reservasi == 'pesan' ? 'warning' : ($reservasi->status_reservasi == 'ditolak' ? 'danger' : 'success')}}">
                                            {{ $reservasi->status_reservasi }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('pesanan.detail', $reservasi->id) }}" 
                                               class="btn btn-sm btn-outline-primary me-1"
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    title="Cetak">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            @if($reservasis->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $reservasis->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .badge.bg-warning {
        color: #000;
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endsection