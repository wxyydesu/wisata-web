@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('reservasi_create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Tambah Reservasi
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manajemen Reservasi</h4>
                        <p class="card-description">
                            Tabel Reservasi <code>Tambah | Edit | Hapus</code>
                        </p>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Pelanggan</th>
                                        <th scope="col">Paket Wisata</th>
                                        <th scope="col">Tanggal Reservasi</th>
                                        <th scope="col">Jumlah Peserta</th>
                                        <th scope="col">Total Bayar</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($reservasis as $index => $reservasi)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        
                                        <td>
                                            {{ $reservasi->pelanggan->nama_lengkap ?? 'N/A' }}
                                            <br>
                                            <small class="text-muted">{{ $reservasi->pelanggan->no_hp ?? '' }}</small>
                                        </td>
                                        
                                        <td>{{ $reservasi->paketWisata->nama_paket ?? 'N/A' }}</td>
                                        
                                        <td>{{ $reservasi->reservasi_wisata->format('d M Y') }}</td>
                                        
                                        <td>{{ $reservasi->jumlah_peserta }} orang</td>
                                        
                                        <td>Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
                                        
                                        <td>
                                            @php
                                                $badgeClass = [
                                                    'pesan' => 'warning',
                                                    'dibayar' => 'success',
                                                    'selesai' => 'primary'
                                                ][$reservasi->status_reservasi_wisata];
                                            @endphp
                                            <span class="badge badge-{{ $badgeClass }}">
                                                {{ ucfirst($reservasi->status_reservasi_wisata) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('reservasi_edit', $reservasi->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('reservasi_show', $reservasi->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <form action="{{ route('reservasi_destroy', $reservasi->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>

                        @if($reservasis->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                Tidak ada data reservasi tersedia
                            </div>
                        @endif

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- content-wrapper -->
</div> <!-- main-panel -->
@endsection