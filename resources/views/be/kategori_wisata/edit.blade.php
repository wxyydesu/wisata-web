@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="font-weight-bold mb-0">Edit Tourism Category</h4>
                    </div>
                    <div>
                        <a href="{{ route('kategori-wisata.index') }}" class="btn btn-primary btn-icon-text ">
                            <i class="fa fa-arrow-left me-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('kategori-wisata.update', $category->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="kategori_wisata">Category Name*</label>
                                <input type="text" class="form-control" id="kategori_wisata" name="kategori_wisata" 
                                       value="{{ old('kategori_wisata', $category->kategori_wisata) }}" required>
                            </div>

                            {{-- <div class="form-group">
                                <label>Current Icon</label>
                                <div>
                                    @if($category->icon)
                                        <img src="{{ asset('storage/' . $category->icon) }}" 
                                             alt="Current Icon" 
                                             class="img-thumbnail mb-2"
                                             width="100">
                                    @else
                                        <p class="text-muted">No icon uploaded</p>
                                    @endif
                                </div>
                                <label for="icon">Change Icon</label>
                                <input type="file" class="form-control-file" id="icon" name="icon">
                                <small class="form-text text-muted">Upload a new icon (optional)</small>
                            </div> --}}
                            
                            {{-- <div class="form-group">
                                <label for="foto">Category Photo</label>
                                <div class="input-group">
                                    <input type="file" name="foto" id="foto" class="form-control d-none">
                                    <input type="text" class="form-control" id="file-info" placeholder="Choose file..." readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="browse-btn">
                                            <i class="fas fa-upload me-1"></i> Choose
                                        </button>
                                    </div>
                                </div>
                                @if(($category->foto) || ($category->foto))
                                <small class="text-muted">Current photo will be replaced if you upload a new one</small>
                                @endif
                            </div> --}}

                            <div class="form-group">
                                <label for="deskripsi">Description</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $category->deskripsi) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="aktif">Status</label>
                                <select class="form-control" id="aktif" name="aktif" required>
                                    <option value="1" {{ $category->aktif ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$category->aktif ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">Update</button>
                            <button type="reset" class="btn btn-light">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if elements exist on the page
    const browseBtn = document.getElementById('browse-btn');
    const fileInput = document.getElementById('foto');
    const fileInfo = document.getElementById('file-info');
    const previewDiv = document.getElementById('image-preview');
    
    if (browseBtn && fileInput) {
        browseBtn.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Update file info
                fileInfo.value = this.files[0].name;
                
                // Image preview
                if (this.files[0].type.match('image.*')) {
                    previewDiv.innerHTML = '';
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.maxHeight = '150px';
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-sm btn-danger mt-2';
                        removeBtn.innerHTML = '<i class="fas fa-trash"></i> Remove';
                        removeBtn.onclick = function() {
                            fileInput.value = '';
                            fileInfo.value = '';
                            previewDiv.innerHTML = '';
                        };
                        
                        previewDiv.appendChild(img);
                        previewDiv.appendChild(removeBtn);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });
    }
});
</script> --}}
@endsection