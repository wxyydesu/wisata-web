@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Detail Reservasi Penginapan</h4>
                    <a href="{{ route('penginapan_reservasi.index') }}" class="btn btn-primary">
                        <i class="fa fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h3>{{ $reservasi->penginapan->nama_penginapan }}</h3>
                            <p class="text-muted">Lokasi: {{ $reservasi->penginapan->lokasi ?? 'Tidak tersedia' }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Data Pelanggan</h5>
                                <div class="bg-light p-3 rounded">
                                    <p><strong>Nama:</strong> {{ $reservasi->pelanggan->nama_lengkap }}</p>
                                    <p><strong>No HP:</strong> {{ $reservasi->pelanggan->no_hp }}</p>
                                    <p><strong>Email:</strong> {{ $reservasi->pelanggan->user->email ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Tanggal Menginap</h5>
                                <div class="bg-light p-3 rounded">
                                    <p><strong>Check In:</strong> {{ date('d-m-Y', strtotime($reservasi->tgl_check_in)) }}</p>
                                    <p><strong>Check Out:</strong> {{ date('d-m-Y', strtotime($reservasi->tgl_check_out)) }}</p>
                                    <p><strong>Lama Malam:</strong> {{ $reservasi->lama_malam }} malam</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Fasilitas</h5>
                            <div class="bg-light p-3 rounded">
                                {!! $reservasi->penginapan->fasilitas !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Informasi Pembayaran</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Harga/Malam:</span>
                                <span class="fw-bold">Rp {{ number_format($reservasi->harga_per_malam, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Jumlah Kamar:</span>
                                <span class="fw-bold">{{ $reservasi->jumlah_kamar }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Lama Malam:</span>
                                <span class="fw-bold">{{ $reservasi->lama_malam }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span class="fw-bold">Rp {{ number_format($reservasi->harga_per_malam * $reservasi->jumlah_kamar * $reservasi->lama_malam, 0, ',', '.') }}</span>
                            </li>
                            @if($reservasi->diskon > 0)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Diskon ({{ $reservasi->diskon }}%):</span>
                                    <span class="fw-bold text-danger">-Rp {{ number_format($reservasi->nilai_diskon, 0, ',', '.') }}</span>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Total Bayar:</strong></span>
                                <span class="fw-bold" style="color: green;">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Status:</span>
                                <span class="badge bg-{{ $reservasi->status_reservasi == 'booking' ? 'success' : ($reservasi->status_reservasi == 'menunggu konfirmasi' ? 'warning' : 'danger') }}">
                                    {{ $reservasi->status_reservasi }}
                                </span>
                            </li>
                        </ul>

                        @if($reservasi->midtrans_status)
                            <div class="mt-3">
                                <h6>Midtrans Status</h6>
                                <p><small>Transaction ID: {{ $reservasi->midtrans_transaction_id }}</small></p>
                                <p><small>Payment Type: {{ $reservasi->midtrans_payment_type }}</small></p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="mb-3">Aksi</h5>
                        <a href="{{ route('penginapan_reservasi.edit', $reservasi->id) }}" class="btn btn-primary btn-block mb-2">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        @if(in_array($reservasi->status_reservasi, ['menunggu konfirmasi', 'booking']))
                            <a href="{{ route('penginapan_reservasi.payment', $reservasi->id) }}" class="btn btn-success btn-block">
                                <i class="fa fa-credit-card"></i> Proses Pembayaran
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
