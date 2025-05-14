@extends('be.master')
@section('sidebar')
  @include('be.sidebar')
@endsection
@section('content')
<div class="content">
<div class="mb-3">
    <a href="{{ route('exportPdf') }}" class="btn btn-danger">Export PDF</a>
    <a href="{{ route('exportExcel') }}" class="btn btn-success">Export Excel</a>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            {{-- Statistik --}}
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon blue mb-2"><i class="bi bi-cash-stack fs-3"></i></div>
                            <h6 class="text-muted">Total Pendapatan</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($totalPendapatan,0,',','.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon green mb-2"><i class="bi bi-check-circle fs-3"></i></div>
                            <h6 class="text-muted">Reservasi Dibayar</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalReservasiDibayar }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon red mb-2"><i class="bi bi-clock fs-3"></i></div>
                            <h6 class="text-muted">Menunggu Pembayaran</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalReservasiMenunggu }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon purple mb-2"><i class="bi bi-star fs-3"></i></div>
                            <h6 class="text-muted">Paket Paling Laris</h6>
                            <h6 class="font-extrabold mb-0">{{ $paketLaris->paket->nama_paket ?? '-' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Grafik pendapatan bulanan --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header"><h4>Grafik Pendapatan Bulanan</h4></div>
                        <div class="card-body">
                            <canvas id="chartPendapatan"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Tabel Reservasi --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header"><h4>Daftar Reservasi</h4></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pelanggan</th>
                                        <th>Paket Wisata</th>
                                        <th>Tgl Reservasi</th>
                                        <th>Harga</th>
                                        <th>Jumlah Peserta</th>
                                        <th>Diskon</th>
                                        <th>Total Bayar</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasi as $i => $r)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $r->paket->nama_paket ?? '-' }}</td>
                                        <td>{{ $r->tgl_reservasi_wisata }}</td>
                                        <td>Rp{{ number_format($r->harga,0,',','.') }}</td>
                                        <td>{{ $r->jumlah_peserta }}</td>
                                        <td>
                                            @if($r->diskon)
                                                {{ $r->diskon }}% (Rp{{ number_format($r->nilai_diskon,0,',','.') }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td>
                                        <td>
                                            @if($r->status_reservasi == 'pesan')
                                                <span class="badge bg-warning">Pesan</span>
                                            @elseif($r->status_reservasi == 'dibayar')
                                                <span class="badge bg-success">Dibayar</span>
                                            @elseif($r->status_reservasi == 'selesai')
                                                <span class="badge bg-info">Selesai</span>
                                            @endif
                                        </td>
                                        <td>{{ $r->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Tabel Paket Wisata --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header"><h4>Daftar Paket Wisata</h4></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Paket</th>
                                        <th>Deskripsi</th>
                                        <th>Fasilitas</th>
                                        <th>Harga/Pack</th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paket as $i => $p)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $p->nama_paket }}</td>
                                        <td>{{ $p->deskripsi }}</td>
                                        <td>{{ $p->fasilitas }}</td>
                                        <td>Rp{{ number_format($p->harga_per_pack,0,',','.') }}</td>
                                        <td>
                                            @for($f=1;$f<=5;$f++)
                                                @php $foto = 'foto'.$f; @endphp
                                                @if($p->$foto)
                                                    <img src="{{ asset('storage/'.$p->$foto) }}" width="40">
                                                @endif
                                            @endfor
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
{{-- Grafik JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPendapatan').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($pendapatanBulanan->pluck('bulan')) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode($pendapatanBulanan->pluck('total')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection