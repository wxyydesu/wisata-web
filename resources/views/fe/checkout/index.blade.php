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
                                    <p><strong>Tanggal Mulai:</strong> {{ $request->tgl_mulai }}</p>
                                    <p><strong>Tanggal Selesai:</strong> {{ $request->tgl_akhir }}</p>
                                    <p><strong>Total Harga:</strong> Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
                                    @if($diskon > 0)
                                        <p class="text-success"><strong>Diskon:</strong> {{ $diskon }}% (Rp{{ number_format($nilaiDiskon, 0, ',', '.') }})</p>
                                        <p><strong>Total Bayar:</strong> Rp{{ number_format($totalBayar - $nilaiDiskon, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_paket" value="{{ $paket->id }}">
                        <input type="hidden" name="tgl_mulai" value="{{ $request->tgl_mulai }}">
                        <input type="hidden" name="tgl_akhir" value="{{ $request->tgl_akhir }}">
                        <input type="hidden" name="jumlah_peserta" value="{{ $request->jumlah_peserta }}">

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
@endsection