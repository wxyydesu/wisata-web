@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('penginapan_reservasi.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Tambah Reservasi Penginapan
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manajemen Reservasi Penginapan</h4>
                        <p class="card-description">
                            Data Reservasi Penginapan <code>Tambah | Edit | Hapus</code>
                        </p>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Pelanggan</th>
                                        <th scope="col">Penginapan</th>
                                        <th scope="col">Check In</th>
                                        <th scope="col">Check Out</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasi as $i => $r)
                                    <tr>
                                        <th scope="row">{{ $reservasi->firstItem() + $i }}</th>
                                        <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                                        <td>
                                            @if (strlen($r->penginapan->nama_penginapan) > 20)
                                                {{ substr($r->penginapan->nama_penginapan, 0, 20) . '...' }}
                                            @else
                                                {{ $r->penginapan->nama_penginapan }}
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($r->tgl_check_in)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($r->tgl_check_out)) }}</td>
                                        <td>Rp {{ number_format($r->total_bayar, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $r->status_reservasi == 'booking' ? 'success' : ($r->status_reservasi == 'menunggu konfirmasi' ? 'warning' : 'danger') }}">
                                                {{ $r->status_reservasi }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-dark btn-sm" onclick="window.location.href='{{ route('penginapan_reservasi.edit', $r->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                
                                                <form action="{{ route('penginapan_reservasi.destroy', $r->id) }}" method="POST" id="deleteForm{{ $r->id }}" style="display: inline;">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteConfirm({{ $r->id }})">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn btn-success btn-sm" onclick="window.location.href='{{ route('penginapan_reservasi.show', $r->id) }}'">
                                                    <i class="fa fa-eye"></i> Detail
                                                </button>
                                                
                                                @if(in_array($r->status_reservasi, ['menunggu konfirmasi', 'booking']))
                                                    <button type="button" class="btn btn-info btn-sm" onclick="window.location.href='{{ route('penginapan_reservasi.payment', $r->id) }}'">
                                                        <i class="fa fa-credit-card"></i> Bayar
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($reservasi->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                Tidak ada data reservasi penginapan tersedia
                            </div>
                        @endif

                        <div class="d-flex justify-content-center mt-3">
                            {{ $reservasi->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded!');
        return;
    }

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
        title: 'Apakah anda yakin?',
        text: "Data ini tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>
@endsection
