@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title">Paket Wisata Management</h4>
        <a href="{{ route('paket.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle me-2"></i>Add Paket Wisata
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Paket Wisata Management</h4>
                    <p class="card-description">
                        Paket Wisata Table <code>Add | Edit | Remove</code>
                    </p>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Package Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pakets as $index => $paket)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($paket->foto1)
                                        <img src="{{ asset('storage/' . $paket->foto1) }}" 
                                                alt="Paket Image" 
                                                class="rounded"
                                                width="60" 
                                                height="40"
                                                style="object-fit: cover; cursor: pointer;"
                                                onclick="showImgPreview('{{ asset('storage/' . $paket->foto1) }}')">
                                        @else
                                        <span class="text-muted">No Image</span>
                                        @endif
                                        @if($paket->foto2)
                                        <img src="{{ asset('storage/' . $paket->foto2) }}" 
                                                alt="Paket Image" 
                                                class="rounded"
                                                width="60" 
                                                height="40"
                                                style="object-fit: cover; cursor: pointer;"
                                                onclick="showImgPreview('{{ asset('storage/' . $paket->foto2) }}')">
                                        @else
                                        
                                        @endif
                                        @if($paket->foto3)
                                        <img src="{{ asset('storage/' . $paket->foto3) }}" 
                                                alt="Paket Image" 
                                                class="rounded"
                                                width="60" 
                                                height="40"
                                                style="object-fit: cover; cursor: pointer;"
                                                onclick="showImgPreview('{{ asset('storage/' . $paket->foto3) }}')">
                                        @else

                                        @endif
                                        @if($paket->foto4)
                                        <img src="{{ asset('storage/' . $paket->foto4) }}" 
                                                alt="Paket Image" 
                                                class="rounded"
                                                width="60" 
                                                height="40"
                                                style="object-fit: cover; cursor: pointer;"
                                                onclick="showImgPreview('{{ asset('storage/' . $paket->foto4) }}')">
                                        @else
                                        
                                        @endif
                                        @if($paket->foto5)
                                        <img src="{{ asset('storage/' . $paket->foto5) }}" 
                                                alt="Paket Image" 
                                                class="rounded"
                                                width="60" 
                                                height="40"
                                                style="object-fit: cover; cursor: pointer;"
                                                onclick="showImgPreview('{{ asset('storage/' . $paket->foto5) }}')">
                                        @else
                                        
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($paket->nama_paket, 30) }}</td>
                                    <td>{{ Str::limit($paket->deskripsi, 50) }}</td>
                                    <td>Rp {{ number_format($paket->harga_per_pack, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('paket.edit', $paket->id) }}'">
                                                <i class="fa fa-pencil-square-o"></i> Edit
                                            </button>
                                            <form action="{{ route('paket.destroy', $paket->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-fw" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($pakets->isEmpty())
                        <div class="alert alert-info text-center mt-3">
                            No paket wisata available
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imgPreview" src="" alt="Preview" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showImgPreview(src) {
    const previewImg = document.getElementById('imgPreview');
    previewImg.src = src;
    
    // Initialize and show modal
    const modal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    modal.show();
}
</script>
@endsection