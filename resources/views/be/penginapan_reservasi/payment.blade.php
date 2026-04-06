@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-credit-card me-2"></i>{{ $title }}</h5>
                </div>
                
                <div class="card-body">
                    <!-- Order Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="detail-box mb-3">
                                <h6 class="text-muted mb-2"><i class="fa fa-receipt me-2"></i>Detail Reservasi</h6>
                                <dl class="row mb-0 small">
                                    <dt class="col-sm-8">ID Reservasi:</dt>
                                    <dd class="col-sm-4"><strong>#{{ $reservasi->id }}</strong></dd>
                                    
                                    <dt class="col-sm-8">Penginapan:</dt>
                                    <dd class="col-sm-4">{{ $reservasi->penginapan->nama_penginapan }}</dd>
                                    
                                    <dt class="col-sm-8">Check In:</dt>
                                    <dd class="col-sm-4">{{ date('d-m-Y', strtotime($reservasi->tgl_check_in)) }}</dd>
                                    
                                    <dt class="col-sm-8">Check Out:</dt>
                                    <dd class="col-sm-4">{{ date('d-m-Y', strtotime($reservasi->tgl_check_out)) }}</dd>

                                    <dt class="col-sm-8">Jumlah Kamar:</dt>
                                    <dd class="col-sm-4">{{ $reservasi->jumlah_kamar }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box bg-light p-3 rounded">
                                <h6 class="text-muted mb-3"><i class="fa fa-calculator me-2"></i>Ringkasan Biaya</h6>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                                    <span>Harga per Malam:</span>
                                    <span>Rp {{ number_format($reservasi->harga_per_malam, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                                    <span>Lama Malam x Jumlah Kamar:</span>
                                    <span>{{ $reservasi->lama_malam }} x {{ $reservasi->jumlah_kamar }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($reservasi->harga_per_malam * $reservasi->lama_malam * $reservasi->jumlah_kamar, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($reservasi->diskon > 0)
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small text-success">
                                    <span>Diskon ({{ $reservasi->diskon }}%):</span>
                                    <span>- Rp {{ number_format($reservasi->nilai_diskon, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between">
                                    <strong>Total Bayar:</strong>
                                    <strong class="text-success" style="font-size: 1.1rem;">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($reservasi->status_reservasi === 'booking')
                    <div class="alert alert-success mb-4">
                        <strong><i class="fa fa-check-circle me-2"></i>Pembayaran Berhasil!</strong><br>
                        Reservasi Anda telah dikonfirmasi dan dibayar.
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('penginapan_reservasi.show', $reservasi->id) }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left me-2"></i>Kembali ke Detail Reservasi
                        </a>
                    </div>
                    @else
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="fa fa-info-circle me-2"></i>Metode Pembayaran</h6>
                        <p class="mb-0">Pembayaran dilakukan melalui <strong>Midtrans Snap</strong>. Kami menerima:</p>
                        <ul class="mb-0 mt-2 ps-3">
                            <li>💳 Kartu Kredit (Visa, Mastercard, JCB)</li>
                            <li>🏦 Transfer Bank (BCA, Mandiri, BNI, CIMB, BRI, dll)</li>
                            <li>📱 E-Wallet</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="button" class="btn btn-success btn-lg" id="pay-button" 
                                onclick="processPayment()">
                            <i class="fa fa-lock me-2"></i> Lanjutkan Pembayaran - Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}
                        </button>
                        <a href="{{ route('penginapan_reservasi.show', $reservasi->id) }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times me-2"></i>Batal
                        </a>
                    </div>

                    <div id="loading" class="text-center d-none">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Processing payment...</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtrans_client_key }}"></script>
<script>
async function processPayment() {
    const button = document.getElementById('pay-button');
    const loading = document.getElementById('loading');
    
    button.disabled = true;
    loading.classList.remove('d-none');

    try {
        const response = await fetch('{{ route("penginapan_reservasi.snap-token", $reservasi->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                payment_type: 'snap'
            })
        });

        const data = await response.json();

        if (data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    console.log('Payment success:', result);
                    // Reload to show updated status
                    setTimeout(() => {
                        window.location.href = '{{ route("penginapan_reservasi.show", $reservasi->id) }}';
                    }, 2000);
                },
                onPending: function (result) {
                    console.log('Payment pending:', result);
                    alert('Pembayaran tertunda. Silakan cek email Anda untuk instruksi pembayaran.');
                },
                onError: function (result) {
                    console.error('Payment error:', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    button.disabled = false;
                    loading.classList.add('d-none');
                },
                onClose: function () {
                    console.log('Payment popup closed');
                    button.disabled = false;
                    loading.classList.add('d-none');
                }
            });
        } else {
            alert('Gagal mendapatkan snap token. Silakan coba lagi.');
            button.disabled = false;
            loading.classList.add('d-none');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
        button.disabled = false;
        loading.classList.add('d-none');
    }
}
</script>
@endsection
