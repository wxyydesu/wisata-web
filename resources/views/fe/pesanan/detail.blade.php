@extends('fe.master')

@section('navbar')
@include('fe.navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-gradient-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Pesanan #{{ $reservasi->id }}</h4>
                <span class="badge bg-{{ $reservasi->status_reservasi == 'pesan' ? 'warning' : ($reservasi->status_reservasi == 'selesai' ? 'success' : 'primary') }} fs-6">
                    {{ ucfirst($reservasi->status_reservasi) }}
                </span>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row g-4">
                <!-- Package Information -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-suitcase-rolling me-2"></i>Informasi Paket Wisata</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . $reservasi->paketWisata->foto1) }}" 
                                     class="img-fluid rounded-3 shadow" 
                                     style="max-height: 250px; width: 100%; object-fit: cover;">
                            </div>
                            <h4 class="text-dark">{{ $reservasi->paketWisata->nama_paket }}</h4>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Durasi:</span>
                                <span class="fw-bold">{{ $reservasi->paketWisata->durasi }} Hari</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Harga per Paket:</span>
                                <span class="fw-bold">Rp {{ number_format($reservasi->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Jumlah Peserta:</span>
                                <span class="fw-bold">{{ $reservasi->jumlah_peserta }} Orang</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Total Harga:</span>
                                <span class="fw-bold text-success">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reservation Details -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Detail Reservasi</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-icon bg-primary text-white">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Tanggal Perjalanan</h6>
                                        <p class="mb-0">
                                            {{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->translatedFormat('l, d F Y') }} 
                                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->translatedFormat('l, d F Y') }}
                                        </p>
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->diffInDays($reservasi->tgl_akhir) + 1 }} hari)</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-icon bg-info text-white">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Pembayaran</h6>
                                        @if($reservasi->file_bukti_tf)
                                        <p class="mb-1">Status: 
                                            <span class="badge bg-success">Sudah Upload Bukti</span>
                                        </p>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" 
                                                data-bs-toggle="modal" data-bs-target="#proofModal">
                                            <i class="fas fa-eye me-1"></i> Lihat Bukti Transfer
                                        </button>
                                        @else
                                        <p class="mb-0">Status: 
                                            <span class="badge bg-warning">Belum Upload Bukti</span>
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-icon bg-secondary text-white">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Catatan</h6>
                                        @if($reservasi->catatan)
                                        <p class="mb-0">{{ $reservasi->catatan }}</p>
                                        @else
                                        <p class="text-muted mb-0">Tidak ada catatan</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-4">
                                <h6><i class="fas fa-info-circle me-2"></i>Informasi Penting</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Harap datang 30 menit sebelum waktu keberangkatan</li>
                                    <li>Simpan bukti reservasi ini untuk ditunjukkan saat check-in</li>
                                    <li>Hubungi kami jika ada perubahan atau pertanyaan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pesanan
                </a>
                <div>
                    @if($reservasi->status_reservasi == 'pesan' && !$reservasi->file_bukti_tf)
                    <a href="#" class="btn btn-primary me-2">
                        <i class="fas fa-upload me-1"></i> Upload Bukti Transfer
                    </a>
                    @endif
                    <a href="{{ route('pesanan.print', $reservasi->id) }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-print me-1"></i> Cetak Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Proof Modal -->
@if($reservasi->file_bukti_tf)
<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proofModalLabel">Bukti Transfer #{{ $reservasi->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ route('pesanan.transfer-proof', $reservasi->id) }}" 
                     class="img-fluid" 
                     alt="Bukti Transfer">
            </div>
            <div class="modal-footer">
                <a href="{{ route('pesanan.transfer-proof', $reservasi->id) }}" 
                   class="btn btn-primary" 
                   download="bukti-transfer-{{ $reservasi->id }}.jpg">
                   <i class="fas fa-download me-1"></i> Unduh
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .card-header.bg-gradient-primary {
        /* background: linear-gradient(135deg, #3f51b5 0%, #2196f3 100%); */
        background: rgb(70, 70, 70)
    }
    
    .timeline {
        position: relative;
        padding-left: 50px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-icon {
        position: absolute;
        left: -50px;
        top: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .timeline-content {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .timeline-content h6 {
        margin-top: 0;
        color: #3f51b5;
    }
</style>
@endsection