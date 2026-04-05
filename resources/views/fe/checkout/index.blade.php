@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Ringkasan Pesanan</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <!-- Package Detail -->
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-package me-2"></i>Detail Paket</h5>
                            <div class="card border-0 bg-light">
                                <img src="{{ asset('storage/' . $paket->foto1) }}" class="card-img-top" alt="{{ $paket->nama_paket }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $paket->nama_paket }}</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-clock me-2"></i>Durasi:</span>
                                        <strong>{{ $paket->durasi }} Hari</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-users me-2"></i>Peserta:</span>
                                        <strong>{{ $request->jumlah_peserta }} Orang</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reservation Detail -->
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-calendar me-2"></i>Jadwal Perjalanan</h5>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Tanggal Mulai</strong></label>
                                        <input type="date" id="tgl_mulai" class="form-control" value="{{ $request->tgl_mulai ?? '' }}" readonly>
                                        <small class="text-danger d-none" id="tgl_mulai_warning">Tanggal sudah dibooking</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Tanggal Selesai</strong></label>
                                        <input type="date" id="tgl_akhir" class="form-control" value="{{ $request->tgl_akhir ?? '' }}" readonly>
                                        <small class="text-danger d-none" id="tgl_akhir_warning">Tanggal sudah dibooking</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Ringkasan Harga</h5>
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span>Harga per Peserta:</span>
                                <span>Rp {{ number_format($paket->harga_per_pack, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span>Jumlah Peserta:</span>
                                <span>{{ $request->jumlah_peserta }} orang</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span>Subtotal:</span>
                                <span>Rp {{ number_format($paket->harga_per_pack * $request->jumlah_peserta, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($diskon > 0)
                            <div class="d-flex justify-content-between text-success mb-2 pb-2 border-bottom">
                                <span>Diskon {{ $diskon }}%:</span>
                                <span>- Rp {{ number_format($nilaiDiskon, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-between fs-5 fw-bold">
                                <span>Total Pembayaran:</span>
                                <span class="text-primary">Rp {{ number_format($totalBayar - $nilaiDiskon, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Info -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Metode Pembayaran</h6>
                        <p class="mb-0">Pembayaran dilakukan melalui <strong>Midtrans Snap</strong> yang mendukung berbagai metode:</p>
                        <ul class="mb-0 mt-2 ps-3">
                            <li>Kartu Kredit (Visa, Mastercard, JCB)</li>
                            <li>Transfer Bank (BCA, Mandiri, BNI, CIMB, dll)</li>
                            <li>E-Wallet (GCash, dll)</li>
                            <li>Dan metode pembayaran lainnya</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="id_paket" value="{{ $paket->id }}">
                            <input type="hidden" name="jumlah_peserta" value="{{ $request->jumlah_peserta }}">
                            <input type="hidden" name="tgl_mulai" value="{{ $request->tgl_mulai }}">
                            <input type="hidden" name="tgl_akhir" value="{{ $request->tgl_akhir }}">
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Lanjutkan ke Pembayaran (Rp {{ number_format($totalBayar - $nilaiDiskon, 0, ',', '.') }})
                            </button>
                        </form>
                        
                        <a href="{{ route('paket.detail', $paket->id) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Paket
                        </a>
                    </div>

                    <small class="text-muted d-block text-center mt-3">
                        <i class="fas fa-lock me-1"></i>Transaksi Anda dilindungi oleh Midtrans Payment Gateway
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const bookedDates = @json($bookedDates ?? []);
    
    document.addEventListener('DOMContentLoaded', function() {
        // Display read-only dates
        const tglMulai = document.getElementById('tgl_mulai').value;
        const tglAkhir = document.getElementById('tgl_akhir').value;
        
        // Verify dates are not booked
        if (bookedDates.includes(tglMulai) || bookedDates.includes(tglAkhir)) {
            document.getElementById('tgl_mulai_warning').classList.remove('d-none');
            document.getElementById('tgl_akhir_warning').classList.remove('d-none');
        }
    });
</script>
@endsection