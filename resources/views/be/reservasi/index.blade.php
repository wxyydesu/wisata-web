@extends('be.master')
@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="row mb-3">
            <div class="col-md-6">
                <h4 class="card-title">Manajemen Reservasi</h4>
                <p class="card-description">Daftar seluruh reservasi wisata</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('reservasi.create') }}" class="btn btn-primary btn-icon-text">
                    <i class="mdi mdi-plus-circle me-2"></i>Tambah Reservasi
                </a>
                <div class="btn-group ms-2">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-filter-variant me-1"></i> Filter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Status Reservasi</h6></li>
                        <li>
                            <a class="dropdown-item @if(request('status')=='pesan') active @endif" href="{{ route('reservasi.index', ['status'=>'pesan']) }}">
                                <span class="badge bg-warning me-2">●</span>Pesan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if(request('status')=='dibayar') active @endif" href="{{ route('reservasi.index', ['status'=>'dibayar']) }}">
                                <span class="badge bg-success me-2">●</span>Dibayar
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if(request('status')=='selesai') active @endif" href="{{ route('reservasi.index', ['status'=>'selesai']) }}">
                                <span class="badge bg-primary me-2">●</span>Selesai
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if(request('status')=='ditolak') active @endif" href="{{ route('reservasi.index', ['status'=>'ditolak']) }}">
                                <span class="badge bg-danger me-2">●</span>Ditolak
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item @if(!request('status')) active @endif" href="{{ route('reservasi.index') }}">
                                <i class="mdi mdi-filter-remove me-2"></i>Reset Filter
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="mdi mdi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="mdi mdi-alert-circle me-2"></i>
                Terjadi kesalahan:
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Reservasi Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No.</th>
                                <th width="12%">Kode</th>
                                <th width="15%">Pelanggan</th>
                                <th width="15%">Paket Wisata</th>
                                <th width="12%">Tanggal</th>
                                <th width="8%">Peserta</th>
                                <th width="12%">Total Bayar</th>
                                <th width="10%">Status</th>
                                <th width="11%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservasi as $item)
                            <tr>
                                <td>{{ ($reservasi->currentPage()-1) * $reservasi->perPage() + $loop->iteration }}</td>
                                <td>
                                    <span class="fw-bold">{{ $item->kode_reservasi ?? 'RSV-'.str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->pelanggan->foto)
                                        <img src="{{ asset('storage/' . $item->pelanggan->foto) }}" class="rounded-circle me-2" width="30" height="30" alt="Foto Profil">
                                        @else
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-light text-dark">
                                                {{ substr($item->pelanggan->nama_lengkap, 0, 1) }}
                                            </span>
                                        </div>
                                        @endif
                                        <div>
                                            <strong>{{ $item->pelanggan->nama_lengkap }}</strong>
                                            <div class="text-muted small">{{ $item->pelanggan->no_hp }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block">{{ $item->paketWisata->nama_paket ?? 'Paket tidak tersedia' }}</span>
                                    <small class="text-muted">{{ $item->paketWisata->kategori->nama_kategori ?? 'Kategori tidak tersedia' }}</small>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($item->tgl_mulai)->translatedFormat('d M Y') }}</div>
                                    <small class="text-muted">s/d {{ \Carbon\Carbon::parse($item->tgl_akhir)->translatedFormat('d M Y') }}</small>
                                </td>
                                <td class="text-center">{{ $item->jumlah_peserta }} <small class="text-muted">org</small></td>
                                <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'pesan' => ['icon' => 'badge badge-warning'],
                                            'dibayar' => ['icon' => 'badge badge-success'],
                                            'selesai' => ['icon' => '"badge badge-primary'],
                                            'ditolak' => ['icon' => 'badge badge-danger']
                                        ];
                                        $status = $item->status_reservasi;
                                        $statusConfig = $statusClasses[$status] ?? ['icon' => 'badge badge-black'];
                                    @endphp
                                    <span class="badge {{ $statusConfig['icon'] }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if(Auth::user()->level == 'admin' || Auth::user()->level == 'owner')
                                        <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('reservasi.edit', $item->id) }}'">
                                            <i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                        @endif
                                        @if(Auth::user()->level == 'admin')
                                        <form action="{{ route('reservasi.destroy', $item->id) }}" method="POST" id="deleteForm{{ $item->id }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-fw" onclick="deleteConfirm({{ $item->id }})">
                                                <i class="fas fa-trash-alt me-1"></i>Delete
                                            </button>
                                        </form>
                                        @endif
                                        <button type="button" class="btn btn-success btn-sm" onClick="window.location.href='{{ route('reservasi.show', $item->id) }}'">
                                            <i class="fa fa-eye"></i> Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="mdi mdi-calendar-remove-outline display-1 text-muted"></i>
                                        <h4 class="mt-3">Tidak ada data reservasi</h4>
                                        <p class="text-muted">Silahkan tambah reservasi baru</p>
                                        <a href="{{ route('reservasi.create') }}" class="btn btn-primary mt-2">
                                            <i class="mdi mdi-plus-circle me-2"></i>Tambah Reservasi
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reservasi->hasPages())
                <div class="row mt-3">
                    <div class="col-md-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $reservasi->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if SweetAlert is loaded
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded!');
        return;
    }

    // Debug: Check session data
    console.log('Session swal data:', @json(session('swal')));
    console.log('Validation errors:', @json($errors->all()));

    // Show SweetAlert notification if exists
    @if(session('swal'))
        Swal.fire({
            position: 'top-end',
            icon: '{{ session('swal.icon') }}',
            title: {!! json_encode(session('swal.title')) !!},
            text: {!! json_encode(session('swal.text')) !!},
            showConfirmButton: false,
            timer: {{ session('swal.timer') ?? 1500 }},
            toast: true
        });
    @endif
    
    // Show validation errors if any
    @if($errors->any())
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Validasi Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            showConfirmButton: false,
            timer: 4000,
            toast: true
        });
    @endif
});
function deleteConfirm(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>
@endsection