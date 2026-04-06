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

function processPayment() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const reservasiId = '{{ $reservasi->id }}';
    const totalBayar = {{ $reservasi->total_bayar }};

    if (paymentMethod === 'midtrans') {
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
        .then(response => response.json())
        .then(data => {
            Swal.close();
            if (data.token) {
                snap.pay(data.token, {
                    onSuccess: function(result) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Terima kasih telah melakukan pembayaran.',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = '{{ route("penginapan") }}';
                        });
                    },
                    onPending: function(result) {
                        Swal.fire('Pembayaran Tertunda', 'Mohon selesaikan pembayaran Anda', 'warning');
                    },
                    onError: function(result) {
                        Swal.fire('Pembayaran Gagal', 'Terjadi kesalahan saat memproses pembayaran', 'error');
                    },
                    onClose: function() {
                        Swal.fire('Pembayaran Dibatalkan', 'Anda membatalkan proses pembayaran', 'info');
                    }
                });
            } else {
                Swal.fire('Error', data.message || 'Gagal mendapatkan token pembayaran', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan saat memproses pembayaran', 'error');
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
