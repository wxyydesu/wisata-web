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
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Checkout & Pembayaran</h4>
                </div>
                <div class="card-body">
                    {{-- Checkout Form (if reservasi not yet created) --}}
                    @if(!$reservasi)
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

                    @else
                    {{-- Payment Interface (after reservation created) --}}
                    <!-- Reservation Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="detail-box">
                                <h6 class="text-muted mb-2">Detail Reservasi</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">ID Reservasi:</dt>
                                    <dd class="col-sm-7"><strong>RES-{{ $reservasi->id }}</strong></dd>
                                    
                                    <dt class="col-sm-5">Paket:</dt>
                                    <dd class="col-sm-7">{{ $reservasi->paketWisata->nama_paket }}</dd>
                                    
                                    <dt class="col-sm-5">Nama Pelanggan:</dt>
                                    <dd class="col-sm-7">{{ $reservasi->pelanggan->user->name }}</dd>
                                    
                                    <dt class="col-sm-5">Tanggal Mulai:</dt>
                                    <dd class="col-sm-7">{{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->format('d M Y') }}</dd>
                                    
                                    <dt class="col-sm-5">Tanggal Akhir:</dt>
                                    <dd class="col-sm-7">{{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->format('d M Y') }}</dd>
                                    
                                    <dt class="col-sm-5">Jumlah Peserta:</dt>
                                    <dd class="col-sm-7">{{ $reservasi->jumlah_peserta }} orang</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box bg-light p-3 rounded">
                                <h6 class="text-muted mb-3">Ringkasan Pembayaran</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Harga per Peserta:</span>
                                    <span>Rp {{ number_format($reservasi->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal ({{ $reservasi->jumlah_peserta }} x):</span>
                                    <span>Rp {{ number_format($reservasi->harga * $reservasi->jumlah_peserta, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($reservasi->diskon > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Diskon {{ $reservasi->diskon }}%:</span>
                                    <span>- Rp {{ number_format($reservasi->nilai_diskon, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Total Bayar:</strong>
                                    <strong class="text-primary" style="font-size: 1.2rem;">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Status --}}
                    @if($reservasi->status_reservasi === 'booking')
                    <div class="alert alert-success mb-4">
                        <strong><i class="fas fa-check-circle me-2"></i>Pembayaran Berhasil!</strong><br>
                        Reservasi Anda telah dikonfirmasi. ID Pesanan: <strong>RES-{{ $reservasi->id }}</strong>
                    </div>
                    <a href="{{ route('pesanan.index') }}" class="btn btn-primary">Lihat Pesanan Saya</a>
                    @else
                    <!-- Payment Methods -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Metode Pembayaran</h6>
                        <p class="mb-0">Pembayaran dilakukan melalui <strong>Midtrans Snap</strong>. Kami menerima:</p>
                        <ul class="mb-0 mt-2 ps-3">
                            <li>💳 Kartu Kredit (Visa, Mastercard, JCB)</li>
                            <li>🏦 Transfer Bank (BCA, Mandiri, BNI, CIMB, BRI, dll)</li>
                            <li>📱 E-Wallet</li>
                        </ul>
                    </div>

                    <!-- Snap Payment Button -->
                    <div class="d-grid gap-2 mb-3">
                        <button type="button" class="btn btn-success btn-lg" id="pay-button" 
                                onclick="processPayment()">
                            <i class="fas fa-lock me-2"></i> Lanjutkan Pembayaran - Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}
                        </button>
                        <a href="{{ route('pesanan.detail', $reservasi->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>

                    <!-- Loading State -->
                    <div id="loading" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memproses pembayaran...</p>
                    </div>
                    @endif

                    <small class="text-muted d-block text-center mt-3">
                        <i class="fas fa-shield-alt me-1"></i>Transaksi Anda dijamin aman dan terlindungi
                    </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
@if($reservasi && $reservasi->status_reservasi !== 'booking')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtrans_client_key }}"></script>

<script>
function processPayment() {
    const payButton = document.getElementById('pay-button');
    const loading = document.getElementById('loading');
    
    payButton.disabled = true;
    loading.classList.remove('d-none');

    // Get snap token from server
    fetch('{{ route("pesanan.snap-token", $reservasi->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Server error: ' + response.status);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success && data.snap_token) {
            // Open Snap payment page
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Payment success
                    console.log('Payment success:', result);
                    showAlert('success', 'Pembayaran Berhasil!', 'Terima kasih telah melakukan pembayaran. Pesanan Anda sedang diproses.');
                    setTimeout(() => {
                        window.location.href = '{{ route("pesanan.detail", $reservasi->id) }}';
                    }, 2000);
                },
                onPending: function(result) {
                    // Payment pending
                    console.log('Payment pending:', result);
                    showAlert('info', 'Pembayaran Pending', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.');
                    setTimeout(() => {
                        window.location.href = '{{ route("pesanan.detail", $reservasi->id) }}';
                    }, 2000);
                },
                onError: function(result) {
                    // Payment error
                    console.error('Payment error:', result);
                    showAlert('error', 'Pembayaran Gagal', result.status_message || 'Terjadi kesalahan saat memproses pembayaran');
                    payButton.disabled = false;
                    loading.classList.add('d-none');
                },
                onClose: function() {
                    // User closes payment page
                    console.log('User closed payment page');
                    payButton.disabled = false;
                    loading.classList.add('d-none');
                }
            });
        } else {
            const errorMsg = data.message || data.error || 'Unknown error';
            showAlert('error', 'Gagal Membuat Token Pembayaran', 'Error: ' + errorMsg);
            payButton.disabled = false;
            loading.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showAlert('error', 'Kesalahan Koneksi', 'Error: ' + error.message);
        payButton.disabled = false;
        loading.classList.add('d-none');
    });
}

function showAlert(type, title, message) {
    // Simple alert implementation - you can replace with SweetAlert or Bootstrap modal
    if (type === 'success') {
        alert('✓ ' + title + '\n\n' + message);
    } else if (type === 'info') {
        alert('ℹ ' + title + '\n\n' + message);
    } else {
        alert('✗ ' + title + '\n\n' + message);
    }
}
</script>
@endif

@if(!$reservasi)
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
@endif

<style>
.detail-box {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}
</style>
@endsection
