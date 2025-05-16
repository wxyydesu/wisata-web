@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('penginapan.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Tambah Penginapan
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manajemen Penginapan</h4>
                        <p class="card-description">
                            Data Penginapan <code>Tambah | Edit | Hapus</code>
                        </p>

                        {{-- @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif --}}

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Penginapan</th>
                                        <th scope="col">Fasilitas</th>
                                        <th scope="col">Foto</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penginapans as $i => $p)
                                    <tr>
                                        <th scope="row">{{ $i+1 }}</th>
                                        <td>
                                            @if (strlen($p->nama_penginapan) > 20)
                                                {{ substr($p->nama_penginapan, 0, 20) . '...' }}
                                            @else
                                                {{ $p->nama_penginapan }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (strlen($p->fasilitas) > 30)
                                                {{ substr($p->fasilitas, 0, 30) . '...' }}
                                            @else
                                                {{ $p->fasilitas }}
                                            @endif
                                        </td>
                                        <td>
                                            @for($f=1;$f<=5;$f++)
                                                @php $foto = 'foto'.$f; @endphp
                                                @if($p->$foto)
                                                    <img src="{{ asset('storage/'.$p->$foto) }}" 
                                                         alt="Foto Penginapan" 
                                                         width="40" 
                                                         style="cursor:pointer"
                                                         onclick="showImgPreview('{{ asset('storage/'.$p->$foto) }}')">
                                                @endif
                                            @endfor
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('penginapan.edit', $p->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                
                                                <form action="{{ route('penginapan.destroy', $p->id) }}" method="POST" id="deleteForm{{ $p->id }}" style="display: inline;">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-fw" onclick="deleteConfirm({{ $p->id }})">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn btn-success btn-sm" onClick="window.location.href='{{ route('penginapan.show', $p->id) }}'">
                                                    <i class="fa fa-eye"></i> Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($penginapans->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                Tidak ada data penginapan tersedia
                            </div>
                        @endif
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- content-wrapper -->

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-labelledby="imgPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="imgPreview" src="" alt="Preview" style="max-width:100%;max-height:70vh;">
            </div>
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
function showImgPreview(src) {
    document.getElementById('imgPreview').src = src;
    var myModal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    myModal.show();
}

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