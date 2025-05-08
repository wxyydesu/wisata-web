@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('penginapan_create') }}" class="btn btn-primary">
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

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

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
                                                <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('penginapan_edit', $p->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                
                                                <form action="{{ route('penginapan_destroy', $p->id) }}" method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-fw">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn btn-success btn-sm" onClick="window.location.href='{{ route('penginapan_show', $p->id) }}'">
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
</div> <!-- main-panel -->

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

<script>
function showImgPreview(src) {
    document.getElementById('imgPreview').src = src;
    var myModal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    myModal.show();
}

function deleteConfirm(id) {
    if (confirm('Yakin ingin menghapus penginapan ini?')) {
        document.getElementById('deleteForm' + id).submit();
    }
}
</script>
@endsection