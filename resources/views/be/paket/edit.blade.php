@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Paket Wisata</h4>
                        
                        <form action="{{ route('paket.update', $paket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="nama_paket">Package Name</label>
                                <input type="text" class="form-control" id="nama_paket" name="nama_paket" 
                                       value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                                @error('nama_paket')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="deskripsi">Description</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="fasilitas">Facilities</label>
                                <textarea class="form-control" id="fasilitas" name="fasilitas" rows="3" required>{{ old('fasilitas', $paket->fasilitas) }}</textarea>
                                @error('fasilitas')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="harga_per_pack">Price (Rp)</label>
                                <input type="number" class="form-control" id="harga_per_pack" name="harga_per_pack" 
                                       value="{{ old('harga_per_pack', $paket->harga_per_pack) }}" required>
                                @error('harga_per_pack')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto1">Main Image</label>
                                        <input type="file" class="form-control" id="foto1" name="foto1">
                                        @error('foto1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto1)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto1) }}" width="100" class="img-thumbnail" style="cursor:pointer" onclick="showImgPreview('{{ asset('storage/' . $paket->foto1) }}')">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto2">Additional Image 1</label>
                                        <input type="file" class="form-control" id="foto2" name="foto2">
                                        @error('foto2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto2)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto2) }}" width="100" class="img-thumbnail" style="cursor:pointer" onclick="showImgPreview('{{ asset('storage/' . $paket->foto2) }}')">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto3">Additional Image 2</label>
                                        <input type="file" class="form-control" id="foto3" name="foto3">
                                        @error('foto3')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto3)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto3) }}" width="100" class="img-thumbnail" style="cursor:pointer" onclick="showImgPreview('{{ asset('storage/' . $paket->foto3) }}')">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto4">Additional Image 3</label>
                                        <input type="file" class="form-control" id="foto4" name="foto4">
                                        @error('foto4')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto4)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto4) }}" width="100" class="img-thumbnail" style="cursor:pointer" onclick="showImgPreview('{{ asset('storage/' . $paket->foto4) }}')">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="foto5">Additional Image 4</label>
                                <input type="file" class="form-control" id="foto5" name="foto5">
                                @error('foto5')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if($paket->foto5)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $paket->foto5) }}" width="100" class="img-thumbnail" style="cursor:pointer" onclick="showImgPreview('{{ asset('storage/' . $paket->foto5) }}')">
                                        <p class="text-muted mt-1">Current image</p>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <a href="{{ route('paket.index') }}" class="btn btn-light">Cancel</a>
                        </form>

                        {{-- Select & delete image --}}
                        @if($paket->foto1 || $paket->foto2 || $paket->foto3 || $paket->foto4 || $paket->foto5)
                            <form id="deleteImageForm" action="{{ route('paket.deleteImage', [$paket->id, 'foto1']) }}" method="POST" style="margin-top:20px;">
                                @csrf
                                @method('DELETE')
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label for="deleteImageSelect" class="form-label">Delete Image</label>
                                        <select class="form-select" id="deleteImageSelect" name="field" required>
                                            <option value="" disabled selected>Select image to delete</option>
                                            @if($paket->foto1)
                                                <option value="foto1">Main Image</option>
                                            @endif
                                            @if($paket->foto2)
                                                <option value="foto2">Additional Image 1</option>
                                            @endif
                                            @if($paket->foto3)
                                                <option value="foto3">Additional Image 2</option>
                                            @endif
                                            @if($paket->foto4)
                                                <option value="foto4">Additional Image 3</option>
                                            @endif
                                            @if($paket->foto5)
                                                <option value="foto5">Additional Image 4</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger" onclick="deleteSelectedImage()">Delete Selected Image</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal for image preview --}}
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-labelledby="imgPreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imgPreviewModalLabel">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imgPreview" src="" alt="Preview" class="img-fluid">
      </div>
    </div>
  </div>
</div>

<script>
function showImgPreview(src) {
    const previewImg = document.getElementById('imgPreview');
    previewImg.src = src;
    const modal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    modal.show();
}

// SweetAlert confirm for image delete
function deleteConfirm(field) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This image will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm_' + field).submit();
        }
    });
}

function deleteSelectedImage() {
    const select = document.getElementById('deleteImageSelect');
    const field = select.value;
    if (!field) {
        Swal.fire('Please select an image to delete.');
        return;
    }
    Swal.fire({
        title: 'Are you sure?',
        text: "This image will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Change form action to include selected field
            const form = document.getElementById('deleteImageForm');
            form.action = "{{ route('paket.deleteImage', [$paket->id, '']) }}/" + field;
            form.submit();
        }
    });
}
</script>
@endsection