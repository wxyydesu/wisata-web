@extends('fe.master')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Konfirmasi Pemesanan Penginapan</h2>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Terjadi Kesalahan!</h4>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Penginapan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('storage/' . $penginapan->foto1) }}" alt="{{ $penginapan->nama_penginapan }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $penginapan->nama_penginapan }}</h4>
                            <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $penginapan->lokasi ?? 'Lokasi tidak tersedia' }}</p>
                            <p><strong>Harga per Malam:</strong> Rp {{ number_format($penginapan->harga_per_malam, 0, ',', '.') }}</p>
                            <p class="text-muted">{{ Str::limit($penginapan->deskripsi, 150) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Ringkasan Pemesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Check-in:</strong> <span id="checkInDisplay">{{ $bookingData['check_in'] ?? '-' }}</span></p>
                            <p><strong>Check-out:</strong> <span id="checkOutDisplay">{{ $bookingData['check_out'] ?? '-' }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jumlah Kamar:</strong> <span id="jumlahKamarDisplay">{{ $bookingData['jumlah_kamar'] ?? 1 }}</span></p>
                            <p><strong>Lama Menginap:</strong> <span id="lamaMalamDisplay">{{ $bookingData['lama_malam'] ?? 1 }}</span> malam</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p>Harga per Malam</p>
                            <p>Jumlah Kamar</p>
                            <p>Lama Menginap</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>Rp {{ number_format($penginapan->harga_per_malam, 0, ',', '.') }}</p>
                            <p><span id="totKamarDisplay">{{ $bookingData['jumlah_kamar'] ?? 1 }}</span> Kamar</p>
                            <p><span id="totMalamDisplay">{{ $bookingData['lama_malam'] ?? 1 }}</span> Malam</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-9"><h5>Total Pembayaran</h5></div>
                        <div class="col-md-3 text-end">
                            <h5 class="text-primary">Rp <span id="totalDisplay">{{ isset($bookingData['total']) ? number_format($bookingData['total'], 0, ',', '.') : '0' }}</span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    <i class="bi bi-credit-card"></i> Lanjutkan ke Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="midtrans" value="midtrans" checked>
                    <label class="form-check-label" for="midtrans">
                        <strong>Midtrans (Kartu Kredit, E-wallet, Bank Transfer)</strong>
                        <div class="text-muted small">Pembayaran aman melalui Midtrans</div>
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="transfer" value="transfer">
                    <label class="form-check-label" for="transfer">
                        <strong>Transfer Bank Manual</strong>
                        <div class="text-muted small">Transfer langsung ke rekening kami</div>
                    </label>
                </div>

                <div class="alert alert-info mt-3">
                    <strong>Total Pembayaran:</strong> Rp <span id="totalPaymentDisplay">0</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitPayment()">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Get booking details from URL params or session
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($bookingData))
            const bookingData = {!! json_encode($bookingData) !!};
            updateDisplay(bookingData);
            document.getElementById('totalPaymentDisplay').textContent = formatCurrency(bookingData.total);
        @endif
    });

    function submitPayment() {
        const reservasiId = '{{ session("booking_data.reservasi_id") }}';
        if (!reservasiId) {
            alert('ID Reservasi tidak ditemukan. Silakan coba lagi.');
            return;
        }
        
        // Redirect to penginapan payment page  
        window.location.href = '/dashboard/penginapan-reservasi/' + reservasiId + '/payment';
    }

    function updateDisplay(data) {
        // Update display with booking data
        document.getElementById('checkInDisplay').textContent = data.check_in;
        document.getElementById('checkOutDisplay').textContent = data.check_out;
        document.getElementById('jumlahKamarDisplay').textContent = data.jumlah_kamar;
        document.getElementById('lamaMalamDisplay').textContent = data.lama_malam;
        document.getElementById('totKamarDisplay').textContent = data.jumlah_kamar;
        document.getElementById('totMalamDisplay').textContent = data.lama_malam;
        document.getElementById('totalDisplay').textContent = formatCurrency(data.total);
        document.getElementById('totalPaymentDisplay').textContent = formatCurrency(data.total);
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }
</script>

<style>
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0;
    }
</style>
@endsection
