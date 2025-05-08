<!-- resources/views/profile/index.blade.php -->
@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-3 py-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Profil Saya</h4>
                </div>
                
                <div class="card-body p-3 p-md-4">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Foto Profil -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @php
                                    $photo = null;
                                    if ($user->level == 'pelanggan' && $user->pelanggan && $user->pelanggan->foto) {
                                        $photo = asset('storage/' . $user->pelanggan->foto);
                                    } elseif ($user->karyawan && $user->karyawan->foto) {
                                        $photo = asset('storage/' . $user->karyawan->foto);
                                    } else {
                                        $photo = asset('images/default-user.jpg');
                                    }
                                @endphp
                                <img src="{{ $photo}}" 
                                     class="rounded-circle border profile-image" 
                                     width="120" 
                                     height="120"
                                     alt="Foto Profil">
                                <label for="foto" class="btn btn-sm btn-secondary position-absolute bottom-0 end-0 rounded-circle">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                            </div>
                        </div>
                        
                        <!-- Informasi Dasar -->
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="no_hp" class="form-label">Nomor HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                       value="{{ old('no_hp', $user->no_hp) }}" required>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label">Level Akun</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->level) }}" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                        
                        <!-- Informasi Tambahan -->
                        @if($user->level === 'pelanggan' && isset($profileData))
                            <div class="mb-3 bg-light p-3 rounded">
                                <h5 class="text-primary mb-3">Informasi Tambahan Pelanggan</h5>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">ID Pelanggan</label>
                                        <input type="text" class="form-control" value="{{ $profileData->id }}" readonly>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Tanggal Daftar</label>
                                        <input type="text" class="form-control" value="{{ $profileData->created_at->format('d F Y') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @elseif(in_array($user->level, ['admin', 'bendahara', 'owner']) && isset($profileData))
                            <div class="mb-3 bg-light p-3 rounded">
                                <h5 class="text-primary mb-3">Informasi Tambahan Karyawan</h5>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Jabatan</label>
                                        <input type="text" class="form-control" value="{{ ucfirst($profileData->jabatan) }}" readonly>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">ID Karyawan</label>
                                        <input type="text" class="form-control" value="{{ $profileData->id }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Tombol Simpan -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            {{-- Session swal: {{ json_encode(session('swal')) }}
                            Errors: {{ json_encode($errors->all()) }} --}}
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
function showImgPreview(src) {
    const previewImg = document.getElementById('imgPreview');
    previewImg.src = src;
    
    // Initialize and show modal
    const modal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    modal.show();
}
// Preview image sebelum upload
document.getElementById('foto').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        const preview = document.querySelector('.profile-image');
        preview.src = URL.createObjectURL(file);
    }
});


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
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}',
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
            html: `
                <ul class="text-left">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            showConfirmButton: false,
            timer: 4000,
            toast: true
        });
    @endif
});
</script>
@endsection