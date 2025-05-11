@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{ $title }} - {{ $greeting }}</h1>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Reservasi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi Pelanggan</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nama Lengkap</th>
                                <td>{{ $reservasi->pelanggan->name_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td>{{ $reservasi->pelanggan->no_hp }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $reservasi->pelanggan->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informasi Reservasi</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Kode Reservasi</th>
                                <td>RES-{{ str_pad($reservasi->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Reservasi</th>
                                <td>{{ $reservasi->tgl_reservasi->format('d-m-Y H:i') }}</td>
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
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Detail Paket Wisata</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nama Paket</th>
                                <td>{{ $reservasi->paketWisata->name_paket }}</td>
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
                                <td>Rp {{ number_format($reservasi->harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Peserta</th>
                                <td>{{ $reservasi->jumlah_peserta }}</td>
                            </tr>
                            <tr>
                                <th>Diskon</th>
                                <td>{{ $reservasi->diskon ? $reservasi->diskon.'%' : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nilai Diskon</th>
                                <td>{{ $reservasi->nilai_diskon ? 'Rp '.number_format($reservasi->nilai_diskon, 0, ',', '.') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Bayar</th>
                                <td>Rp {{ number_format($reservasi->total_bayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Bukti Transfer</th>
                                <td>
                                    @if($reservasi->file_bukti_tf)
                                        <a href="{{ asset('storage/'.$reservasi->file_bukti_tf) }}" target="_blank">Lihat Bukti Transfer</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('reservasi_manage') }}" class="btn btn-secondary">Kembali</a>
                    @if(Auth::user()->level !== 'pelanggan' || $reservasi->status_reservasi == 'pesan')
                        <a href="{{ route('reservasi.edit', $reservasi->id) }}" class="btn btn-warning">Edit</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection