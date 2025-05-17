@extends('be.master')
@section('sidebar')
  @include('be.sidebar')
@endsection
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12">
      <div class="home-tab">
        <div class="d-sm-flex align-items-center justify-content-between border-bottom">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="reservations-tab" data-bs-toggle="tab" href="#reservations" role="tab" aria-selected="false">Reservations</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="packages-tab" data-bs-toggle="tab" href="#packages" role="tab" aria-selected="false">Packages</a>
            </li>
          </ul>
          <div class="btn-wrapper">
            <a href="{{ route('exportPdf') }}" class="btn btn-otline-dark align-items-center"><i class="icon-printer"></i> PDF</a>
            <a href="{{ route('exportExcel') }}" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Excel</a>
          </div>
        </div>

        
        <div class="tab-content tab-content-basic">
          <!-- Overview Tab -->
          <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
            <div class="row">
              <div class="col-sm-12">
                <div class="statistics-details d-flex align-items-center justify-content-between">
                  <div>
                    <p class="statistics-title">Total Pendapatan</p>
                    <h3 class="rate-percentage">Rp {{ number_format($totalPendapatan,0,',','.') }}</h3>
                    <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+12%</span></p>
                  </div>
                  <div>
                    <p class="statistics-title">Reservasi Dibayar</p>
                    <h3 class="rate-percentage">{{ $totalReservasiDibayar }}</h3>
                    @php
                        $totalReservations = $totalReservasiDibayar + $totalReservasiMenunggu;
                        $paidPercentage = $totalReservations > 0 ? round(($totalReservasiDibayar / $totalReservations) * 100) : 0;
                    @endphp
                    <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+{{ $paidPercentage }}%</span></p>
                  </div>
                  <div>
                    <p class="statistics-title">Menunggu Pembayaran</p>
                    <h3 class="rate-percentage">{{ $totalReservasiMenunggu }}</h3>
                    @php
                        $waitingPercentage = $totalReservations > 0 ? round(($totalReservasiMenunggu / $totalReservations) * 100) : 0;
                    @endphp
                    <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-{{ $waitingPercentage }}%</span></p>
                  </div>
                  <div class="d-none d-md-block">
                    <p class="statistics-title">Paket Paling Laris</p>
                    <h3 class="rate-percentage">{{ $paketLaris->paketWisata->nama_paket ?? '-' }}</h3>
                    <p class="text-success d-flex"><i class="mdi mdi-star"></i><span>Top Seller</span></p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-lg-8 d-flex flex-column">
                <div class="row flex-grow">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card card-rounded">
                      <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-start">
                          <div>
                            <h4 class="card-title card-title-dash">Grafik Pendapatan Bulanan</h4>
                            <p class="card-subtitle card-subtitle-dash">Statistik pendapatan bulan {{ date('F Y') }}</p>
                          </div>
                          <div>
                            <div class="dropdown">
                              <button class="btn btn-light dropdown-toggle toggle-dark btn-lg mb-0 me-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Bulan {{ request('bulan', date('F')) }}
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <h6 class="dropdown-header">Pilih Bulan</h6>
                                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bulan)
                                  <a class="dropdown-item bulan-filter" href="#" data-bulan="{{ $bulan }}">{{ $bulan }}</a>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="chartjs-bar-wrapper mt-3">
                          <canvas id="revenueChart" height="300"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row flex-grow">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card card-rounded">
                      <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-start">
                          <div>
                            <h4 class="card-title card-title-dash">Reservasi Terbaru</h4>
                            <p class="card-subtitle card-subtitle-dash">5 reservasi terakhir</p>
                          </div>
                          {{-- <div>
                            <a href="#reservations" class="btn btn-primary btn-lg text-white mb-0 me-0"
                               data-bs-toggle="tab" role="tab" aria-controls="reservations" aria-selected="false">
                              Lihat Semua <i class="mdi mdi-arrow-right ms-1"></i>
                            </a>
                          </div> --}}
                        </div>
                        <div class="table-responsive mt-1">
                          <table class="table select-table">
                            <thead>
                              <tr>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($reservasi as $r)
                              <tr>
                                <td>
                                  <div class="d-flex">
                                    {{-- <div class="avatar avatar-sm me-3">
                                      <span class="avatar-title rounded-circle bg-light text-dark">
                                        {{ substr($r->pelanggan->nama_lengkap ?? '-', 0, 1) }}
                                      </span>
                                    </div> --}}
                                    <div>
                                      <h6>{{ $r->pelanggan->nama_lengkap ?? '-' }}</h6>
                                      <p>{{ $r->pelanggan->email ?? '-' }}</p>
                                    </div>
                                  </div>
                                </td>
                                <td>{{ $r->paketWisata->nama_paket ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($r->tgl_reservasi_wisata)->format('d M Y') }}</td>
                                <td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td>
                                <td>
                                  @if($r->status_reservasi == 'pesan')
                                      <div class="d-flex gap-1">
                                          <form method="POST" action="{{ route('reservasi.confirm', $r->id) }}">
                                              @csrf
                                              @method('PUT')
                                              <button type="submit" class="btn btn-success btn-sm">
                                                  <i class="mdi mdi-check"></i> Confirm
                                              </button>
                                          </form>
                                          <form method="POST" action="{{ route('reservasi.reject', $r->id) }}">
                                              @csrf
                                              @method('PUT')
                                              <button type="submit" class="btn btn-danger btn-sm">
                                                  <i class="mdi mdi-close"></i> Reject
                                              </button>
                                          </form>
                                      </div>
                                  @else
                                      <button class="btn btn-outline-secondary btn-sm" disabled>
                                          @if($r->status_reservasi == 'dibayar')
                                              <i class="mdi mdi-check-all"></i> Confirmed
                                          @elseif($r->status_reservasi == 'ditolak')
                                              <i class="mdi mdi-block-helper"></i> Rejected
                                          @else
                                              <i class="mdi mdi-history"></i> {{ $r->status_reservasi }}
                                          @endif
                                      </button>
                                  @endif
                              </td>
                                <td>
                                <button class="btn btn-detail-reservasi" 
                                      data-url="{{ route('be-reservasi.detail', ['reservasi' => $r->id]) }}">
                                <i class="mdi mdi-eye"></i>
                                </button>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-4 d-flex flex-column">
                <div class="row flex-grow">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card card-rounded">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title card-title-dash">Distribusi Reservasi</h4>
                            </div>
                            <div>
                              <canvas class="my-auto" id="reservationChart" height="250"></canvas>
                            </div>
                            <div id="reservationChart-legend" class="mt-5 text-center"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row flex-grow">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card card-rounded">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title card-title-dash">Top 3 Paket</h4>
                              {{-- <a href="#packages" class="btn btn-sm btn-link"
                                 data-bs-toggle="tab" role="tab" aria-controls="packages" aria-selected="false">
                                Lihat Semua
                              </a> --}}
                            </div>
                            <div class="mt-3">
                              @foreach($paket->take(3) as $p)
                              <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                <div class="d-flex">
                                  @php $foto = $p->foto1 ? 'storage/'.$p->foto1 : 'assets/images/default-package.jpg'; @endphp
                                  <img class="img-sm rounded-10" src="{{ asset($foto) }}" alt="{{ $p->nama_paket }}">
                                  <div class="wrapper ms-3">
                                    <p class="ms-1 mb-1 fw-bold">{{ $p->nama_paket }}</p>
                                    <small class="text-muted mb-0">Rp{{ number_format($p->harga_per_pack,0,',','.') }}</small>
                                  </div>
                                </div>
                                <div class="text-muted text-small">
                                  1/12/2020
                                </div>
                              </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Reservations Tab -->
          <div class="tab-pane fade" id="reservations" role="tabpanel" aria-labelledby="reservations-tab">
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-start">
                      <div>
                        <h4 class="card-title card-title-dash">Semua Reservasi</h4>
                        <p class="card-subtitle card-subtitle-dash">Daftar lengkap reservasi</p>
                      </div>
                      {{-- <div>
                        <button class="btn btn-primary btn-lg text-white mb-0 me-0" type="button">
                          <i class="mdi mdi-plus"></i> Tambah Reservasi
                        </button>
                      </div> --}}
                    </div>
                    <div class="table-responsive mt-1">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($reservasi as $i => $r)
                          <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                            <td>{{ $r->paketWisata->nama_paket ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->tgl_reservasi_wisata)->format('d M Y') }}</td>
                            <td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td>
                            <td>
                              @if($r->status_reservasi == 'pesan')
                                <div class="badge badge-opacity-warning">Menunggu</div>
                              @elseif($r->status_reservasi == 'dibayar')
                                <div class="badge badge-opacity-success">Dibayar</div>
                              @elseif($r->status_reservasi == 'selesai')
                                <div class="badge badge-opacity-info">Selesai</div>
                              @elseif($r->status_reservasi == 'ditolak')
                                <div class="badge badge-opacity-danger">Ditolak</div>
                              @endif
                            </td>
                            <td>
                              @if($r->status_reservasi == 'pesan')
                                <form method="POST" action="{{ route('reservasi.confirm', $r->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Confirm
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('reservasi.reject', $r->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="mdi mdi-close"></i> Reject
                                    </button>
                                </form>
                              @elseif($r->status_reservasi == 'ditolak')
                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="mdi mdi-close"></i>
                                </button>
                              @else
                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="mdi mdi-check-all"></i>
                                </button>
                              @endif
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
          </div>
          
          <!-- Packages Tab -->
          <div class="tab-pane fade" id="packages" role="tabpanel" aria-labelledby="packages-tab">
            <div class="row">
              @foreach($paket as $p)
              <div class="col-12 col-md-6 col-lg-4 grid-margin stretch-card">
                <div class="card card-rounded">
                  <div class="package-image">
                    @php $foto = $p->foto1 ? 'storage/'.$p->foto1 : 'assets/images/default-package.jpg'; @endphp
                    <img src="{{ asset($foto) }}" class="card-img-top" alt="{{ $p->nama_paket }}">
                    <span class="package-price">Rp{{ number_format($p->harga_per_pack,0,',','.') }}</span>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">{{ $p->nama_paket }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($p->deskripsi, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <i class="mdi mdi-account-group me-1"></i> 
                        <small>{{ $p->minimal_orang }} orang</small>
                      </div>
                      <a href="#" class="btn btn-sm btn-secondary btn-detail-paket" 
                        data-url="{{ route('be-paket.detail', ['paket' => $p->id]) }}">
                        Detail
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Reservasi -->
<div class="modal fade" id="reservasiDetailModal" tabindex="-1" aria-labelledby="reservasiDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reservasiDetailModalLabel">Detail Reservasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <h6>Informasi Pelanggan</h6>
            <table class="table table-sm">
              <tr>
                <th>Nama</th>
                <td id="detail-nama"></td>
              </tr>
              <tr>
                <th>Email</th>
                <td id="detail-email"></td>
              </tr>
              <tr>
                <th>No. Telepon</th>
                <td id="detail-telepon"></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <h6>Detail Reservasi</h6>
            <table class="table table-sm">
              <tr>
                <th>Paket</th>
                <td id="detail-paket"></td>
              </tr>
              <tr>
                <th>Tanggal</th>
                <td id="detail-tanggal"></td>
              </tr>
              <tr>
                <th>Jumlah Peserta</th>
                <td id="detail-peserta"></td>
              </tr>
            </table>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-md-12">
            <h6>Informasi Pembayaran</h6>
            <table class="table table-sm">
              <tr>
                <th>Total Bayar</th>
                <td id="detail-total"></td>
              </tr>
              <tr>
                <th>Status</th>
                <td id="detail-status"></td>
              </tr>
              <tr>
                <th>Metode Pembayaran</th>
                <td id="detail-metode"></td>
              </tr>
              <tr>
                <th>Waktu Reservasi</th>
                <td id="detail-waktu"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Paket -->
<div class="modal fade" id="paketDetailModal" tabindex="-1" aria-labelledby="paketDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paketDetailModalLabel">Detail Paket Wisata</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <div id="paketCarousel" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner" id="carousel-inner">
                <!-- Images will be inserted here -->
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#paketCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#paketCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
          <div class="col-md-4">
            <h4 id="paket-nama"></h4>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="text-primary">Rp <span id="paket-harga"></span></h5>
              <span class="badge bg-info">
                <i class="mdi mdi-account-group"></i> 
                Min. <span id="paket-min-orang"></span> orang
              </span>
            </div>
            <p id="paket-deskripsi" class="text-muted"></p>
            <h6>Fasilitas:</h6>
            <ul id="paket-fasilitas" class="list-unstyled">
              <!-- Facilities will be inserted here -->
            </ul>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueLabels = {!! json_encode($pendapatanBulanan->pluck('bulan')->map(function($item) {
  try {
    return \Carbon\Carbon::createFromFormat('Y-m', $item)->format('M Y');
  } catch (\Exception $e) {
    return $item;
  }
})) !!};
const revenueData = {!! json_encode($pendapatanBulanan->pluck('total')) !!};
const revenueChart = new Chart(revenueCtx, {
  type: 'bar',
  data: {
    labels: revenueLabels,
    datasets: [{
      label: 'Pendapatan',
      data: revenueData,
      backgroundColor: 'rgba(58, 123, 213, 0.7)',
      borderColor: 'rgba(58, 123, 213, 1)',
      borderWidth: 0,
      borderRadius: 4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        callbacks: {
          label: function(context) {
              return 'Rp ' + context.raw.toLocaleString('id-ID');
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
          }
        },
        grid: {
          drawBorder: false,
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

// Reservation Distribution Chart
const reservationCtx = document.getElementById('reservationChart').getContext('2d');
const reservationChart = new Chart(reservationCtx, {
  type: 'doughnut',
  data: {
    labels: ['Dibayar', 'Menunggu', 'Selesai'],
    datasets: [{
      data: [
        {{ $totalReservasiDibayar }}, 
        {{ $totalReservasiMenunggu }}, 
        {{ $totalReservasiSelesai ?? 0 }}
      ],
      backgroundColor: [
        'rgba(40, 167, 69, 0.7)',
        'rgba(255, 193, 7, 0.7)',
        'rgba(23, 162, 184, 0.7)'
      ],
      borderColor: [
        'rgba(40, 167, 69, 1)',
        'rgba(255, 193, 7, 1)',
        'rgba(23, 162, 184, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom'
      }
    },
    cutout: '70%'
  }
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.bulan-filter').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const bulan = this.getAttribute('data-bulan');
            const url = new URL(window.location.href);
            url.searchParams.set('bulan', bulan);
            window.location.href = url.toString();
        });

        

      // Detail Reservasi
      document.querySelectorAll('.btn-detail-reservasi').forEach(button => {
        button.addEventListener('click', function() {
        const url = this.dataset.url;
    
        fetch(url)
          .then(response => response.json())
          .then(data => {
           
          new bootstrap.Modal(document.getElementById('reservasiDetailModal')).show();
        });
      });
    });

    // Detail Paket
    document.querySelectorAll('.btn-detail-paket').forEach(button => {
        button.addEventListener('click', function(e) {e.preventDefault();
          const url = this.dataset.url;
  
          fetch(url)
            .then(response => response.json())
            .then(data => {

             
            new bootstrap.Modal(document.getElementById('paketDetailModal')).show();
            });
        });

        document.getElementById('paket-nama').textContent = data.nama_paket;
        document.getElementById('paket-harga').textContent = data.harga_per_pack.toLocaleString('id-ID');
        document.getElementById('paket-min-orang').textContent = data.minimal_orang;
        document.getElementById('paket-deskripsi').textContent = data.deskripsi;
        
        const fasilitasList = document.getElementById('paket-fasilitas');
        fasilitasList.innerHTML = '';
        data.fasilitas.split(',').forEach(fasilitas => {
          const li = document.createElement('li');
          li.className = 'mb-2';
          li.innerHTML = `<i class="mdi mdi-check-circle-outline text-success me-2"></i>${fasilitas.trim()}`;
          fasilitasList.appendChild(li);
        });

        new bootstrap.Modal(document.getElementById('paketDetailModal')).show();
      });
  });
});
</script>

<style>
  .package-image {
    position: relative;
    height: 160px;
    overflow: hidden;
  }
  .package-image img {
    object-fit: cover;
    height: 100%;
    width: 100%;
  }
  .package-price {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: rgba(0,0,0,0.7);
    color: white;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
  }
  .avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
  }
  .modal-body table tr th {
  width: 40%;
  white-space: nowrap;
  }

  .carousel-item img {
    border-radius: 10px;
  }

  #paket-fasilitas li {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
  }
</style>
@endsection