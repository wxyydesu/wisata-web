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
                    <h4 class="mb-0">Konfirmasi Pembayaran</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Detail Paket</h5>
                            <div class="card">
                                <img src="{{ asset('storage/' . $paket->foto1) }}" class="card-img-top" alt="{{ $paket->nama_paket }}" style="height: 180px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $paket->nama_paket }}</h5>
                                    <p class="card-text">
                                        <i class="fas fa-clock me-2"></i>{{ $paket->durasi }} Hari<br>
                                        <i class="fas fa-users me-2"></i>{{ $request->jumlah_peserta }} Orang
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Detail Reservasi</h5>
                            <div class="card">
                                <div class="card-body">
                                    {{-- Tanggal Mulai --}}
                                    <p>
                                        <strong>Tanggal Mulai:</strong>
                                        <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control" value="{{ $request->tgl_mulai ?? '' }}">
                                        <small class="text-muted text-danger" id="tgl_mulai_warning" style="display:none;">Tanggal ini sudah dibooking, silakan pilih tanggal lain.</small>
                                    </p>
                                    {{-- Tanggal Selesai --}}
                                    <p>
                                        <strong>Tanggal Selesai:</strong>
                                        <input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" value="{{ $request->tgl_akhir ?? '' }}">
                                        <small class="text-muted text-danger" id="tgl_akhir_warning" style="display:none;">Tanggal ini sudah dibooking, silakan pilih tanggal lain.</small>
                                    </p>
                                    <p><strong>Total Harga:</strong> Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
                                    @if($diskon > 0)
                                        <p class="text-success"><strong>Diskon:</strong> {{ $diskon }}% (Rp{{ number_format($nilaiDiskon, 0, ',', '.') }})</p>
                                        <p><strong>Total Bayar:</strong> Rp{{ number_format($totalBayar - $nilaiDiskon, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="id_paket" value="{{ $paket->id }}">
                        <input type="hidden" name="jumlah_peserta" value="{{ $request->jumlah_peserta }}">
                        {{-- Tanggal akan diisi oleh JS --}}
                        <input type="hidden" name="tgl_mulai" id="form_tgl_mulai" value="{{ $request->tgl_mulai ?? '' }}">
                        <input type="hidden" name="tgl_akhir" id="form_tgl_akhir" value="{{ $request->tgl_akhir ?? '' }}">

                        <div class="mb-4">
                            <h5>Metode Pembayaran</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="bank_id" class="form-label">Pilih Bank</label>
                                        <select class="form-select" id="bank_id" name="bank_id" required>
                                            <option value="">-- Pilih Bank --</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->nama_bank }} - {{ $bank->no_rekening }} ({{ $bank->atas_nama }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="file_bukti_tf" class="form-label">Upload Bukti Transfer</label>
                                        <input type="file" class="form-control" id="file_bukti_tf" name="file_bukti_tf" accept="image/*,.pdf" required>
                                        <small class="text-muted">Format: JPG, PNG, PDF (Max: 2MB)</small>
                                    </div>
                                    <div class="alert alert-info">
                                        <h6>Instruksi Pembayaran:</h6>
                                        <ol>
                                            <li>Transfer sesuai total pembayaran</li>
                                            <li>Upload bukti transfer</li>
                                            <li>Kami akan memverifikasi dalam 1x24 jam</li>
                                            <li>Anda akan menerima email konfirmasi</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Script untuk disable tanggal yang sudah dibooking --}}
<script>
    const bookedDates = @json($bookedDates ?? []);
    function isBooked(dateStr) {
        return bookedDates.includes(dateStr);
    }

    function setMinMaxDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tgl_mulai').setAttribute('min', today);
        document.getElementById('tgl_akhir').setAttribute('min', today);
    }

    function disableBookedDates(inputId, warningId) {
        const input = document.getElementById(inputId);
        const warning = document.getElementById(warningId);

        // Disable all booked dates
        input.addEventListener('focus', function() {
            // Save current value
            const prev = input.value;
            input.addEventListener('input', function handler() {
                if (isBooked(input.value)) {
                    warning.style.display = '';
                    input.value = '';
                } else {
                    warning.style.display = 'none';
                }
                input.removeEventListener('input', handler);
            });
        });

        // On page load, disable booked dates by setting max/min and step
        input.addEventListener('keydown', function(e) {
            // Prevent manual typing
            e.preventDefault();
        });

        // Prevent picking booked date via mouse
        input.addEventListener('change', function() {
            if (isBooked(input.value)) {
                warning.style.display = '';
                input.value = '';
            } else {
                warning.style.display = 'none';
            }
        });
    }

    function disableBookedDatesNative(inputId) {
        // For native input[type=date], we can't truly disable dates, but we can block on change
        const input = document.getElementById(inputId);
        input.addEventListener('change', function() {
            if (isBooked(input.value)) {
                input.value = '';
                document.getElementById(inputId + '_warning').style.display = '';
            } else {
                document.getElementById(inputId + '_warning').style.display = 'none';
            }
        });
    }

    function updateDateInputs() {
        setMinMaxDate();
        disableBookedDatesNative('tgl_mulai');
        disableBookedDatesNative('tgl_akhir');
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateDateInputs();
    });
</script>
@endsection