@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">{{ $greeting }}, Create New User</h4>
            <a href="{{ route('user_manage') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('user_store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_hp">Phone Number</label>
                                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Address</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="level">Role</label>
                                        <select class="form-control" id="level" name="level" required>
                                            @foreach($levels as $level)
                                                <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="aktif">Status</label>
                                        <select class="form-control" id="aktif" name="aktif" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="karyawan-fields">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jabatan">Position</label>
                                        <select class="form-control" id="jabatan" name="jabatan">
                                            @foreach($jabatans as $jabatan)
                                                <option value="{{ $jabatan }}">{{ ucfirst($jabatan) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="pelanggan-fields" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_lengkap">Complete Name</label>
                                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="foto">Profile Photo</label>
                                <div class="input-group">
                                    <input type="file" name="foto" id="foto" class="form-control d-none">
                                    <input type="text" class="form-control" id="file-info" placeholder="Choose file..." readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="browse-btn">
                                            <i class="fas fa-upload me-1"></i> Choose
                                        </button>
                                    </div>
                                </div>
                                <div id="image-preview" class="mt-2"></div>
                            </div>

                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="reset" class="btn btn-light">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('level').addEventListener('change', function() {
    const level = this.value;
    const karyawanFields = document.getElementById('karyawan-fields');
    const pelangganFields = document.getElementById('pelanggan-fields');
    
    if (level === 'pelanggan') {
        karyawanFields.style.display = 'none';
        pelangganFields.style.display = 'flex';
        document.getElementById('nama_lengkap').required = true;
        document.getElementById('jabatan').required = false;
    } else {
        karyawanFields.style.display = 'flex';
        pelangganFields.style.display = 'none';
        document.getElementById('nama_lengkap').required = false;
        document.getElementById('jabatan').required = true;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.file-upload-browse').forEach(button => {
        button.addEventListener('click', function() {
            const fileInput = this.closest('.input-group').querySelector('.file-upload-default');
            fileInput.click();
        });
    });

    document.querySelectorAll('.file-upload-default').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.value.split('\\').pop();
            this.closest('.input-group').querySelector('.file-upload-info').value = fileName;
        });
    });
});

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
</script>
@endsection