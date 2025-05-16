@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">{{ $greeting }}, here's your Tourism Category Management</h4>
            <a href="{{ route('kategori-wisata.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Add Category
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tourism Categories</h4>
                        <p class="card-description">
                            Category Table <code>Add | Edit | Remove</code>
                        </p>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Category Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($categories as $index => $category)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}.</th>
                                
                                        {{-- Nama Kategori --}}
                                        <td class="py-1">
                                            {{ $category->kategori_wisata }}
                                        </td>

                                        {{-- foto --}}
                                        {{-- <td>
                                            @if($category->foto)
                                                <img src="{{ asset('storage/' . $category->foto) }}" 
                                                     alt="Category foto" 
                                                     class="rounded-circle border"
                                                     width="40" 
                                                     height="40"
                                                     style="cursor:pointer; object-fit: cover;"
                                                     onclick="showImgPreview('{{ asset('storage/' . $category->foto) }}')">
                                            @else
                                                <span class="text-muted">No foto</span>
                                            @endif
                                        </td> --}}
                                
                                        {{-- Deskripsi --}}
                                        <td>
                                            {{ Str::limit($category->deskripsi ?? '-', 30) }}
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            @if($category->aktif)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                
                                        {{-- Tanggal Dibuat --}}
                                        <td>
                                            {{ $category->created_at->format('d M Y') }}
                                        </td>
                                
                                        {{-- Aksi --}}
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('kategori-wisata.edit', $category->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                <form action="{{ route('kategori-wisata.destroy', $category->id) }}" method="POST" id="deleteForm{{ $category->id }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-fw" onclick="deleteConfirm({{ $category->id }})">
                                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($categories->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                No categories available
                            </div>
                        @endif

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- content-wrapper -->

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