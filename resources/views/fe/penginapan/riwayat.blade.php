@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="main">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Pemesanan Penginapan</h2>
            <div>
                <form method="GET" action="{{ route('penginapan.riwayat') }}" class="d-flex gap-2">
                    <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="menunggu konfirmasi" {{ request('status') == 'menunggu konfirmasi' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="booking" {{ request('status') == 'booking' ? 'selected' : '' }}>Terkonfirmasi</option>
                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Mobile View -->
        <div class="d-block d-md-none">
            @forelse($reservasis as $reservasi)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">#{{ $reservasi->id }}</span>
                    <span class="badge bg-{{ 
                        $reservasi->status_reservasi === 'menunggu konfirmasi' ? 'warning' :
                        ($reservasi->status_reservasi === 'booking' ? 'success' :
                        ($reservasi->status_reservasi === 'selesai' ? 'info' : 'danger'))
                    }}">
                        {{ ucfirst($reservasi->status_reservasi) }}
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $reservasi->penginapan->nama_penginapan }}</h5>
                    <p class="card-text text-muted mb-1">
                        <i class="far fa-calendar-alt me-2"></i>
                        {{ \Carbon\Carbon::parse($reservasi->tgl_check_in)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($reservasi->tgl_check_out)->format('d M Y') }}
                    </p>
                    <p class="card-text text-muted mb-1">
                        <i class="fas fa-door-open me-2"></i>
                        {{ $reservasi->jumlah_kamar }} kamar × {{ $reservasi->lama_malam }} malam
                    </p>
                    <h5 class="text-primary mb-3">
                        Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}
                    </h5>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($reservasi->status_reservasi === 'menunggu konfirmasi')
                        <a href="{{ route('penginapan.customer.payment', $reservasi->id) }}" class="btn btn-sm btn-outline-success w-100">
                            <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                        </a>
                        @else
                        <div class="btn btn-sm btn-light w-100 text-muted">
                            Sudah {{ $reservasi->status_reservasi === 'booking' ? 'Terkonfirmasi' : 'Diselesaikan' }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info text-center">
                <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                <p class="mt-3 mb-0">Anda belum ada pemesanan penginapan</p>
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
                                    <th>ID</th>
                                    <th>Nama Penginapan</th>
                                    <th>Check-in / Check-out</th>
                                    <th>Kamar / Malam</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservasis as $reservasi)
                                <tr>
                                    <td><strong>#{{ $reservasi->id }}</strong></td>
                                    <td>{{ $reservasi->penginapan->nama_penginapan }}</td>
                                    <td>
                                        <small>
                                            {{ \Carbon\Carbon::parse($reservasi->tgl_check_in)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($reservasi->tgl_check_out)->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $reservasi->jumlah_kamar }} kamar / {{ $reservasi->lama_malam }} malam</small>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $reservasi->status_reservasi === 'menunggu konfirmasi' ? 'warning' :
                                            ($reservasi->status_reservasi === 'booking' ? 'success' :
                                            ($reservasi->status_reservasi === 'selesai' ? 'info' : 'danger'))
                                        }}">
                                            {{ ucfirst($reservasi->status_reservasi) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($reservasi->status_reservasi === 'menunggu konfirmasi')
                                            <a href="{{ route('penginapan.customer.payment', $reservasi->id) }}" class="btn btn-outline-success" title="Lanjut Pembayaran">
                                                <i class="fas fa-credit-card"></i> Bayar
                                            </a>
                                            @endif
                                            <a href="{{ route('penginapan') }}" class="btn btn-outline-primary" title="Kembali ke Penginapan">
                                                <i class="fas fa-arrow-left"></i> Kembali
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
            @if($reservasis->count() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $reservasis->render('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .main {
        min-height: calc(100vh - 200px);
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
        transform: translateY(-4px);
    }
    
    .table-responsive {
        border-radius: 12px;
    }
    
    .badge {
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .btn-group-sm .btn {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
</style>
@endsection
