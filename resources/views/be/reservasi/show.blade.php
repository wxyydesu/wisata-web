@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="mdi mdi-account-circle-outline"></i> Informasi Pelanggan
                                    </h5>
                                </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th width="30%">Nama Lengkap</th>
                                                    <td>{{ $reservasi->pelanggan->nama_lengkap }}</td>
                                                </tr>
                                                <tr>
                                                    <th>No HP</th>
                                                    <td>{{ $reservasi->pelanggan->no_hp }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Alamat</th>
                                                    <td>{{ $reservasi->pelanggan->alamat }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $reservasi->pelanggan->user->email ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-info card-outline">
                                    <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="mdi mdi-calendar-check"></i> Informasi Reservasi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th width="30%">Kode Reservasi</th>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        RES-{{ str_pad($reservasi->id, 6, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Mulai</th>
                                                <td>{{ \Carbon\Carbon::parse($reservasi->tgl_mulai)->translatedFormat('d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Akhir</th>
                                                <td>{{ \Carbon\Carbon::parse($reservasi->tgl_akhir)->translatedFormat('d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Lama Reservasi</th>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ max(1, \Carbon\Carbon::parse($reservasi->tgl_mulai)->diffInDays(\Carbon\Carbon::parse($reservasi->tgl_akhir))) + 1 }} hari
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge badge-{{ 
                                                        $reservasi->status_reservasi == 'pesan' ? 'warning' : 
                                                        ($reservasi->status_reservasi == 'dibayar' ? 'primary' : 'success') 
                                                    }}">
                                                        {{ ucfirst($reservasi->status_reservasi) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card card-success card-outline">
                                    <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="mdi mdi-bag-personal"></i> Detail Paket Wisata
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th width="30%">Nama Paket</th>
                                                <td>{{ $reservasi->paketWisata->nama_paket }}</td>
                                            </tr>
                                            <tr>
                                                <th>Deskripsi</th>
                                                <td>{{ $reservasi->paketWisata->deskripsi }}</td>
                                            </tr>
                                            <tr>
                                                <th>Fasilitas</th>
                                                <td>{{ $reservasi->paketWisata->fasilitas }}</td>
                                            </tr>
                                            <tr>
                                                <th>Harga per Pack</th>
                                                <td class="font-weight-bold">Rp {{ number_format($reservasi->harga, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Peserta</th>
                                                <td>{{ $reservasi->jumlah_peserta }} orang</td>
                                            </tr>
                                            <tr>
                                                <th>Diskon</th>
                                                <td>{{ $reservasi->diskon ? $reservasi->diskon.'%' : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nilai Diskon</th>
                                                <td>{{ $reservasi->nilai_diskon ? 'Rp '.number_format($reservasi->nilai_diskon, 0, ',', '.') : '-' }}</td>
                                            </tr>
                                            <tr class="table-primary">
                                                <th>Total Bayar</th>
                                                <td class="font-weight-bold">Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Bukti Transfer</th>
                                                <td>
                                                    {{-- @if($reservasi->file_bukti_tf)
                                                        <div class="mt-2">
                                                            <p>File saat ini: 
                                                                <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#buktiTFModal">
                                                                    Lihat Bukti Transfer
                                                                </button>
                                                            </p>
                                                        </div>
                                                    @endif --}}
                                                    @if($reservasi->file_bukti_tf)
                                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#buktiTFModal">
                                                            <i class="mdi mdi-file-image"></i> Lihat Bukti Transfer
                                                        </button>
                                                    @else
                                                        <span class="text-muted">Belum ada bukti transfer</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('reservasi.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-arrow-left"></i> Kembali ke Daftar
                            </a>
                            <div>
                                @if(Auth::user()->level !== 'pelanggan' || $reservasi->status_reservasi == 'pesan')
                                    <a href="{{ route('reservasi.edit', $reservasi->id) }}" class="btn btn-warning">
                                        <i class="mdi mdi-pencil"></i> Edit Reservasi
                                    </a>
                                @endif
                                
                                @if(Auth::user()->level == 'admin')
                                    <button class="btn btn-danger" onclick="deleteConfirm({{ $reservasi->id }})">
                                        <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                    <form id="deleteForm{{ $reservasi->id }}" action="{{ route('reservasi.destroy', $reservasi->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($reservasi->file_bukti_tf)
<div class="modal fade" id="buktiTFModal" tabindex="-1" aria-labelledby="buktiTFModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buktiTFModalLabel">Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @php
                    $fileExtension = pathinfo($reservasi->file_bukti_tf, PATHINFO_EXTENSION);
                    $fileUrl = asset('storage/' . $reservasi->file_bukti_tf);
                @endphp
                
                @if(strtolower($fileExtension) === 'pdf')
                    <embed src="{{ $fileUrl }}" type="application/pdf" width="100%" height="600px">
                @else
                    <img src="{{ $fileUrl }}" alt="Bukti Transfer" class="img-fluid">
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> Tutup
                </button>
                <a href="{{ $fileUrl }}" download class="btn btn-primary">
                    <i class="mdi mdi-download"></i> Unduh
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    // Fungsi konfirmasi hapus
    function deleteConfirm(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>
@endsection