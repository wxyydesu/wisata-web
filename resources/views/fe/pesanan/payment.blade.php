@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>{{ $title }}</h5>
                </div>
                
                <div class="card-body">
                    <!-- Order Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="detail-box mb-3">
                                <h6 class="text-muted mb-2"><i class="fas fa-receipt me-2"></i>Detail Pesanan</h6>
                                <dl class="row mb-0 small">
                                    <dt class="col-sm-6">ID Pesanan:</dt>
                                    <dd class="col-sm-6"><strong>#{{ $reservasi->id }}</strong></dd>
                                    
                                    <dt class="col-sm-6">Paket:</dt>
                                    <dd class="col-sm-6">{{ $reservasi->paketWisata->nama_paket }}</dd>
                                    
                                    <dt class="col-sm-6">Tanggal:</dt>
                                    <dd class="col-sm-6">{{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->format('d M Y') }}</dd>
                                    
                                    <dt class="col-sm-6">Peserta:</dt>
                                    <dd class="col-sm-6">{{ $reservasi->jumlah_peserta }} orang</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box bg-light p-3 rounded">
                                <h6 class="text-muted mb-3"><i class="fas fa-calculator me-2"></i>Ringkasan Biaya</h6>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                                    <span>Harga per Peserta:</span>
                                    <span>Rp {{ number_format($reservasi->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                                    <span>Subtotal ({{ $reservasi->jumlah_peserta }} x):</span>
                                    <span>Rp {{ number_format($reservasi->harga * $reservasi->jumlah_peserta, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($reservasi->diskon > 0)
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small text-success">
                                    <span>Diskon {{ $reservasi->diskon }}%:</span>
                                    <span>- Rp {{ number_format($reservasi->nilai_diskon, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between">
                                    <strong>Total Bayar:</strong>
                                    <strong class="text-primary" style="font-size: 1.1rem;">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Status --}}
                    @if($reservasi->status_reservasi === 'booking')
                    <div class="alert alert-success mb-4">
                        <strong><i class="fas fa-check-circle me-2"></i>Pembayaran Berhasil!</strong><br>
                        Pesanan Anda telah dikonfirmasi dan dibayar.
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('pesanan.detail', $reservasi->id) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Pesanan
                        </a>
                    </div>
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtrans_client_key }}"></script>

@if($reservasi->status_reservasi !== 'booking')
<script>
function processPayment() {
    const payButton = document.getElementById('pay-button');
    const loading = document.getElementById('loading');
    
    console.log('Starting payment process for reservation ID: {{ $reservasi->id }}');
    
    payButton.disabled = true;
    loading.classList.remove('d-none');

    // Get snap token from server
    const csrfElement = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfElement ? csrfElement.content : '';
    
    fetch('{{ route("pesanan.snap-token", $reservasi->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({})
    })
    .then(response => {
        console.log('Response status:', response.status);
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
            console.log('Snap token received, opening payment modal');
            // Open Snap payment page
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Payment success
                    console.log('Payment success:', result);
                    loading.classList.remove('d-none');
                    showAlert('success', 'Pembayaran Berhasil!', 'Terima kasih telah melakukan pembayaran. Pesanan Anda sedang diproses. Sistem sedang mengkonfirmasi pembayaran...');
                    
                    // Verify payment status with server before redirecting
                    fetch('{{ route("pesanan.verify-payment", $reservasi->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({})
                    })
                    .then(verifyResponse => verifyResponse.json())
                    .then(verifyData => {
                        console.log('Payment verification:', verifyData);
                        // Redirect after verification completes
                        setTimeout(() => {
                            window.location.href = '{{ route("pesanan.detail", $reservasi->id) }}';
                        }, 2000);
                    })
                    .catch(verifyError => {
                        console.error('Verification error:', verifyError);
                        // Still redirect even if verification fails
                        setTimeout(() => {
                            window.location.href = '{{ route("pesanan.detail", $reservasi->id) }}';
                        }, 3000);
                    });
                },
                onPending: function(result) {
                    // Payment pending
                    console.log('Payment pending:', result);
                    loading.classList.remove('d-none');
                    showAlert('info', 'Pembayaran Pending', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi dari sistem.');
                    // Wait for webhook to process
                    setTimeout(() => {
                        window.location.href = '{{ route("pesanan.detail", $reservasi->id) }}';
                    }, 4000);
                },
                onError: function(result) {
                    // Payment error
                    console.error('Payment error:', result);
                    loading.classList.add('d-none');
                    showAlert('error', 'Pembayaran Gagal', 'Terjadi kesalahan: ' + (result.status_message || 'Coba lagi'));
                    payButton.disabled = false;
                },
                onClose: function() {
                    // User closes payment page
                    console.log('User closed payment page');
                    loading.classList.add('d-none');
                    payButton.disabled = false;
                }
            });
        } else {
            console.error('Invalid response:', data);
            loading.classList.add('d-none');
            const errorMsg = data.message || data.error || 'Unknown error';
            showAlert('error', 'Gagal Membuat Token Pembayaran', 'Error: ' + errorMsg);
            payButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        loading.classList.add('d-none');
        showAlert('error', 'Kesalahan Koneksi', 'Error: ' + error.message);
        payButton.disabled = false;
    });
}

function showAlert(type, title, message) {
    // Display alert using browser alert
    // You can replace this with a better modal/toast implementation
    const fullMessage = title + '\n\n' + message;
    if (type === 'error') {
        alert('❌ ' + fullMessage);
    } else if (type === 'success') {
        alert('✅ ' + fullMessage);
    } else {
        alert('ℹ️ ' + fullMessage);
    }
}
</script>
@endif

<style>
.detail-box {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.detail-box.bg-light {
    border-color: #dee2e6;
}
</style>
@endsection
