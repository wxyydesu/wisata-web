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
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab === 'paket' ? 'active' : '' }}" 
                   href="{{ route('pesanan.index', ['tab' => 'paket']) }}" 
                   role="tab">
                    <i class="fas fa-suitcase me-2"></i> Paket Wisata 
                    <span class="badge bg-primary ms-2">{{ $reservasis->total() }}</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab === 'penginapan' ? 'active' : '' }}" 
                   href="{{ route('pesanan.index', ['tab' => 'penginapan']) }}" 
                   role="tab">
                    <i class="fas fa-bed me-2"></i> Penginapan 
                    <span class="badge bg-success ms-2">{{ $penginapanReservasis->total() }}</span>
                </a>
            </li>
        </ul>

        <!-- Filter Section -->
        <div class="mb-4">
            <form method="GET" action="{{ route('pesanan.index') }}" class="row g-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @if($tab === 'paket')
                            <option value="pesan" {{ $status == 'pesan' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="dibayar" {{ $status == 'dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                            <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        @else
                            <option value="menunggu konfirmasi" {{ $status == 'menunggu konfirmasi' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="booking" {{ $status == 'booking' ? 'selected' : '' }}>Terkonfirmasi</option>
                            <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-auto ms-auto">
                    @if($tab === 'paket')
                        <a href="{{ route('pesanan.printAll', request()->only('status')) }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-1"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- TAB PAKET WISATA -->
        @if($tab === 'paket')
            <!-- Mobile View -->
            <div class="d-block d-md-none">
                @forelse($reservasis as $reservasi)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light d-flex justify-content-between">
                        <span class="fw-bold">#{{ $reservasi->id }}</span>
                        <span class="badge bg-{{ 
                            $reservasi->status_reservasi === 'pesan' ? 'warning' :
                            ($reservasi->status_reservasi === 'dibayar' ? 'success' :
                            ($reservasi->status_reservasi === 'ditolak' ? 'danger' : 'info'))
                        }}">
                            {{ $reservasi->status_reservasi === 'pesan' ? 'Menunggu Bayar' : ucfirst($reservasi->status_reservasi) }}
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
                        <div class="d-flex justify-content-between gap-2 flex-wrap">
                            <a href="{{ route('pesanan.detail', $reservasi->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            @if($reservasi->status_reservasi === 'pesan')
                            <a href="{{ route('pesanan.payment', $reservasi->id) }}" class="btn btn-sm btn-outline-success flex-grow-1">
                                <i class="fas fa-credit-card me-1"></i> Bayar
                            </a>
                            @endif
                            <a href="{{ route('pesanan.print', $reservasi->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary flex-grow-1">
                                <i class="fas fa-print me-1"></i> Cetak
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-info text-center">
                    <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                    <p class="mt-3 mb-0">Anda belum ada pesanan paket wisata</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop View -->
            <div class="d-none d-md-block">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            @if($reservasis->count() > 0)
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Paket</th>
                                        <th>Tanggal</th>
                                        <th>Peserta</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasis as $reservasi)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $reservasi->paketWisata->foto1) }}" 
                                                     class="rounded me-3" width="60" height="40" style="object-fit: cover;">
                                                <span>{{ $reservasi->paketWisata->nama_paket }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->format('d M Y') }}</small>
                                        </td>
                                        <td>{{ $reservasi->jumlah_peserta }} orang</td>
                                        <td class="fw-bold">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $reservasi->status_reservasi === 'pesan' ? 'warning' :
                                                ($reservasi->status_reservasi === 'dibayar' ? 'success' :
                                                ($reservasi->status_reservasi === 'ditolak' ? 'danger' : 'info'))
                                            }}">
                                                {{ $reservasi->status_reservasi === 'pesan' ? 'Menunggu Bayar' : ucfirst($reservasi->status_reservasi) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('pesanan.detail', $reservasi->id) }}" class="btn btn-outline-primary" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($reservasi->status_reservasi === 'pesan')
                                                <a href="{{ route('pesanan.payment', $reservasi->id) }}" class="btn btn-outline-success" title="Bayar">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                                @endif
                                                <a href="{{ route('pesanan.print', $reservasi->id) }}" target="_blank" class="btn btn-outline-secondary" title="Cetak">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-3 text-muted mb-0">Anda belum ada pesanan paket wisata</p>
                                <a href="{{ route('paket') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-search me-2"></i> Cari Paket
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($reservasis->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $reservasis->render('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        @endif

        <!-- TAB PENGINAPAN -->
        @if($tab === 'penginapan')
            <!-- Mobile View -->
            <div class="d-block d-md-none">
                @forelse($penginapanReservasis as $penginapan)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light d-flex justify-content-between">
                        <span class="fw-bold">#{{ $penginapan->id }}</span>
                        <span class="badge bg-{{ 
                            $penginapan->status_reservasi === 'menunggu konfirmasi' ? 'warning' :
                            ($penginapan->status_reservasi === 'booking' ? 'success' :
                            ($penginapan->status_reservasi === 'batal' ? 'danger' : 'info'))
                        }}">
                            {{ ucfirst($penginapan->status_reservasi) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $penginapan->penginapan->nama_penginapan }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="far fa-calendar-alt me-2"></i>
                            {{ \Carbon\Carbon::parse($penginapan->tgl_check_in)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($penginapan->tgl_check_out)->format('d M Y') }}
                        </p>
                        <p class="card-text text-muted mb-2">
                            <i class="fas fa-door-open me-2"></i>
                            {{ $penginapan->jumlah_kamar }} kamar × {{ $penginapan->lama_malam }} malam
                        </p>
                        <h5 class="text-primary mb-3">
                            Rp {{ number_format($penginapan->total_bayar, 0, ',', '.') }}
                        </h5>
                        <div class="d-flex justify-content-between gap-2 flex-wrap">
                            @if($penginapan->status_reservasi === 'menunggu konfirmasi')
                            <a href="{{ route('penginapan.customer.payment', $penginapan->id) }}" class="btn btn-sm btn-outline-success flex-grow-1">
                                <i class="fas fa-credit-card me-1"></i> Bayar
                            </a>
                            @endif
                            <a href="{{ route('penginapan') }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-info text-center">
                    <i class="fas fa-bed" style="font-size: 2rem;"></i>
                    <p class="mt-3 mb-0">Anda belum ada pemesanan penginapan</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop View -->
            <div class="d-none d-md-block">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            @if($penginapanReservasis->count() > 0)
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Penginapan</th>
                                        <th>Check-in / Check-out</th>
                                        <th>Kamar / Malam</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penginapanReservasis as $penginapan)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $penginapan->penginapan->foto1) }}" 
                                                     class="rounded me-3" width="60" height="40" style="object-fit: cover;">
                                                <span>{{ $penginapan->penginapan->nama_penginapan }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($penginapan->tgl_check_in)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($penginapan->tgl_check_out)->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $penginapan->jumlah_kamar }} kamar / {{ $penginapan->lama_malam }} malam</small>
                                        </td>
                                        <td class="fw-bold">Rp {{ number_format($penginapan->total_bayar, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $penginapan->status_reservasi === 'menunggu konfirmasi' ? 'warning' :
                                                ($penginapan->status_reservasi === 'booking' ? 'success' :
                                                ($penginapan->status_reservasi === 'batal' ? 'danger' : 'info'))
                                            }}">
                                                {{ ucfirst($penginapan->status_reservasi) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($penginapan->status_reservasi === 'menunggu konfirmasi')
                                                <a href="{{ route('penginapan.customer.payment', $penginapan->id) }}" class="btn btn-outline-success" title="Bayar">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                                @endif
                                                <a href="{{ route('penginapan') }}" class="btn btn-outline-primary" title="Kembali">
                                                    <i class="fas fa-arrow-left"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-bed" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-3 text-muted mb-0">Anda belum ada pemesanan penginapan</p>
                                <a href="{{ route('penginapan') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-search me-2"></i> Cari Penginapan
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($penginapanReservasis->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $penginapanReservasis->render('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    .main {
        min-height: calc(100vh - 200px);
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .nav-tabs .nav-link {
        color: #495057;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        color: #0d6efd;
        border-bottom-color: rgba(13, 110, 253, 0.2);
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
        background: none;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .badge.bg-warning {
        color: #000;
    }
    
    .card {
        border-radius: 10px;
        overflow: hidden;
        border: none;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .btn-group-sm .btn {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
</style>
@endsection