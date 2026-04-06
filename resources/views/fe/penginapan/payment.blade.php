@extends('fe.master')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Pembayaran Penginapan</h2>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Reservasi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama Penginapan:</strong> {{ $reservasi->penginapan->nama_penginapan }}</p>
                            <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($reservasi->tgl_check_in)->format('d/m/Y') }}</p>
                            <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($reservasi->tgl_check_out)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jumlah Kamar:</strong> {{ $reservasi->jumlah_kamar }}</p>
                            <p><strong>Lama Menginap:</strong> {{ $reservasi->lama_malam }} malam</p>
                            <p><strong>Status:</strong> 
                                <span class="badge {{ $reservasi->status_reservasi === 'menunggu konfirmasi' ? 'bg-warning' : 'bg-info' }}">
                                    {{ ucfirst($reservasi->status_reservasi) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Rincian Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>Harga per Malam</p>
                            <p>Jumlah Kamar</p>
                            <p>Lama Menginap</p>
                            <p><strong>Subtotal</strong></p>
                            @if($reservasi->diskon > 0)
                                <p>Diskon ({{ $reservasi->diskon }}%)</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <p>Rp {{ number_format($reservasi->harga_per_malam, 0, ',', '.') }}</p>
                            <p>{{ $reservasi->jumlah_kamar }} Kamar</p>
                            <p>{{ $reservasi->lama_malam }} Malam</p>
                            <p><strong>Rp {{ number_format($reservasi->harga_per_malam * $reservasi->lama_malam * $reservasi->jumlah_kamar, 0, ',', '.') }}</strong></p>
                            @if($reservasi->diskon > 0)
                                <p class="text-danger">- Rp {{ number_format($reservasi->nilai_diskon, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Total Pembayaran</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <h4 class="text-primary">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Pilih Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        @csrf
                        <div class="payment-options">
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" id="midtrans" value="midtrans" checked>
                                <label class="form-check-label w-100" for="midtrans">
                                    <strong>💳 Midtrans (Bank Transfer, Kartu Kredit, E-wallet)</strong>
                                    <div class="text-muted small mt-1">Pembayaran aman melalui Midtrans. Berbagai metode pembayaran tersedia.</div>
                                </label>
                            </div>

                            @if($banks && count($banks) > 0)
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="transfer">
                                    <label class="form-check-label w-100" for="transfer">
                                        <strong>🏦 Transfer Bank Manual</strong>
                                        <div class="text-muted small mt-1">Transfer langsung ke rekening kami. Konfirmasi manual diperlukan.</div>
                                    </label>
                                </div>

                                <div id="bankDetails" style="display: none;" class="mb-4 p-3 bg-light border rounded">
                                    <h6>Rekening Bank Kami:</h6>
                                    <div id="bankList"></div>
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-info mt-4">
                            <strong>⏰ Penting:</strong> Selesaikan pembayaran dalam 24 jam untuk mengamankan reservasi Anda.
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-primary btn-lg" id="payButton" onclick="processPayment()">
                                <i class="bi bi-credit-card"></i> Proses Pembayaran - Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}
                            </button>
                            <a href="{{ route('penginapan') }}" class="btn btn-light">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtrans_client_key }}"></script>
<script>
// Ensure Snap is loaded
var snapToken = null;
var snapReady = false;

// Check if Snap is loaded
window.addEventListener('load', function() {
    if (window.snap) {
        snapReady = true;
        console.log('Snap script loaded successfully');
    } else {
        console.error('Snap script failed to load');
    }
});

// Fallback check after 3 seconds
setTimeout(function() {
    if (!snapReady && window.snap) {
        snapReady = true;
        console.log('Snap loaded (after timeout)');
    }
}, 3000);

document.addEventListener('DOMContentLoaded', function() {
    // Show/hide bank details based on selection
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const bankDetails = document.getElementById('bankDetails');
            if (this.value === 'transfer' && bankDetails) {
                bankDetails.style.display = 'block';
                loadBankDetails();
            } else if (bankDetails) {
                bankDetails.style.display = 'none';
            }
        });
    });

    // Load banks on page load if transfer was pre-selected
    @if($banks && count($banks) > 0)
        loadBankDetails();
    @endif
});

function loadBankDetails() {
    const bankList = document.getElementById('bankList');
    if (!bankList) return;

    @if($banks)
        let html = '';
        const banks = {!! json_encode($banks) !!};
        banks.forEach(bank => {
            html += `
                <div class="mb-2 p-2 bg-white rounded border-left border-4" style="border-left-color: #007bff;">
                    <div><strong>${bank.nama_bank}</strong></div>
                    <div class="text-muted small">No. Rekening: <code>${bank.no_rekening}</code></div>
                    <div class="text-muted small">A.n: ${bank.nama_pemilik}</div>
                </div>
            `;
        });
        bankList.innerHTML = html;
    @endif
}

function verifyPayment(orderId, reservasiId) {
    // Send order_id to backend to verify payment status with Midtrans
    fetch('{{ route("penginapan.verify-payment", "") }}/' + reservasiId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            order_id: orderId
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        console.log('Payment verification:', data);
        
        if (data.success && (data.status === 'settlement' || data.status === 'capture')) {
            // Payment successful
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                text: 'Terima kasih telah melakukan pembayaran. Reservasi Anda telah dikonfirmasi.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = '{{ route("penginapan") }}';
            });
        } else if (data.success && data.status === 'pending') {
            // Payment still pending
            Swal.fire({
                icon: 'warning',
                title: 'Pembayaran Tertunda',
                text: 'Status pembayaran Anda sedang diproses. Anda akan menerima notifikasi ketika pembayaran dikonfirmasi.',
                showConfirmButton: true
            });
        } else if (data.success && data.status === 'deny') {
            // Payment denied
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Ditolak',
                text: 'Pembayaran Anda ditolak oleh sistem pembayaran. Silakan coba lagi dengan metode pembayaran lain.',
                showConfirmButton: true
            });
        } else {
            // Unknown status or error
            Swal.fire({
                icon: 'error',
                title: 'Verifikasi Gagal',
                text: data.message || 'Terjadi kesalahan saat memverifikasi pembayaran.',
                showConfirmButton: true
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Verification error:', error);
        // If verification fails, still show success since Snap already processed it
        Swal.fire({
            icon: 'warning',
            title: 'Pembayaran Mungkin Berhasil',
            text: 'Pembayaran Anda telah diproses. Kami sedang memverifikasi status. Anda akan menerima notifikasi segera.',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.location.href = '{{ route("penginapan") }}';
        });
    });
}

function processPayment() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const reservasiId = '{{ $reservasi->id }}';
    const totalBayar = {{ $reservasi->total_bayar }};

    if (paymentMethod === 'midtrans') {
        // Check if Snap is loaded
        if (!window.snap) {
            Swal.fire('Error', 'Snap payment script belum siap. Silakan refresh halaman dan coba lagi.', 'error');
            return;
        }

        // Process via Midtrans
        Swal.fire({
            title: 'Memproses Pembayaran...',
            html: 'Harap tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("penginapan.snap-token", $reservasi->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                reservation_id: reservasiId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error, status = ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            console.log('Token response:', data);
            
            if (data.success && data.token) {
                // Use snap.pay to open modal
                window.snap.pay(data.token, {
                    onSuccess: function(result) {
                        console.log('Payment success result:', result);
                        
                        // Verify transaction on backend before confirming
                        Swal.fire({
                            title: 'Memverifikasi Pembayaran...',
                            html: 'Harap tunggu sebentar',
                            icon: 'info',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Verify the transaction status from Midtrans
                        verifyPayment(result.order_id, '{{ $reservasi->id }}');
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        Swal.fire('Pembayaran Tertunda', 'Status pembayaran masih tertunda. Anda akan menerima notifikasi ketika pembayaran dikonfirmasi.', 'warning');
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        Swal.fire('Pembayaran Gagal', 'Terjadi kesalahan saat memproses pembayaran. ' + (result.status_message || ''), 'error');
                    },
                    onClose: function() {
                        console.log('Payment modal closed without completing transaction');
                        Swal.fire('Pembayaran Dibatalkan', 'Anda menutup halaman pembayaran tanpa menyelesaikan transaksi.', 'info');
                    }
                });
            } else {
                const errorMsg = data.message || 'Gagal mendapatkan token pembayaran';
                console.error('Midtrans error:', data);
                Swal.fire('Error', errorMsg, 'error');
            }
        })
        .catch(error => {
            Swal.close();
            console.error('API Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan saat memproses pembayaran: ' + error.message, 'error');
        });
    } else if (paymentMethod === 'transfer') {
        // Manual transfer instructions
        Swal.fire({
            icon: 'info',
            title: 'Instruksi Transfer Bank',
            html: `
                <div class="text-start">
                    <p><strong>Total yang harus ditransfer:</strong></p>
                    <h4 class="text-primary">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</h4>
                    <p class="text-muted mt-3">Silakan transfer kerekening yang tertera di atas.</p>
                    <p class="text-danger"><strong>⏰ Batas Waktu:</strong> 24 Jam dari sekarang</p>
                    <p class="small"><strong>Catatan:</strong> Setelah transfer, konfirmasi pembayaran akan diproses dalam 1-2 jam kerja.</p>
                </div>
            `,
            confirmButtonText: 'Saya Sudah Transfer',
            showCancelButton: true,
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Terima Kasih', 'Pembayaran Anda sedang diproses.', 'success').then(() => {
                    window.location.href = '{{ route("penginapan") }}';
                });
            }
        });
    }
}
</script>

<style>
    .form-check {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .form-check:hover {
        background-color: #f8f9fa !important;
    }
    .form-check input:checked + label {
        color: #0d6efd;
    }
</style>
@endsection
