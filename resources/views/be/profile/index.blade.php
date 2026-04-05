<!-- resources/views/profile/index.blade.php -->
@extends('be.master')
@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="page-header-wrapper" style="animation: slideInDown 0.6s ease-out;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="page-title mb-1">
                                <i class="mdi mdi-account-circle me-2"></i>Kelola Profil Anda
                            </h2>
                            <p class="text-muted">Update dan kelola informasi profil pribadi Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile_update') }}" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Kolom Kiri: Profile Card -->
                <div class="col-lg-4 mb-4" style="animation: fadeInLeft 0.8s ease-out;">
                    <!-- Profile Photo Card -->
                    <div class="card profile-card border-0 shadow-lg h-100" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-body text-center p-4" style="background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%);">
                            <div class="position-relative d-inline-block mb-3">
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
                                <div class="profile-photo-wrapper" style="position: relative;">
                                    <img src="{{ $photo }}" 
                                         class="rounded-circle profile-image shadow-lg"
                                         width="180"
                                         height="180"
                                         alt="Foto Profil"
                                         style="object-fit: cover; border: 6px solid white; transition: transform 0.3s ease;">
                                    
                                    <label for="foto" class="btn btn-primary rounded-circle camera-btn shadow-sm"
                                           style="width: 50px; height: 50px; padding: 0; position: absolute; bottom: 10px; right: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;"
                                           onmouseover="this.style.transform='scale(1.1)'"
                                           onmouseout="this.style.transform='scale(1)'">
                                        <i class="fas fa-camera" style="font-size: 1.2rem;"></i>
                                    </label>
                                    <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- User Info Card Body -->
                        <div class="card-body p-4 text-center">
                            <h4 class="fw-bold mb-1" style="color: #1F1F1F;">{{ $user->name }}</h4>
                            <p class="text-muted small mb-3">
                                <i class="mdi mdi-email me-1"></i>{{ $user->email }}
                            </p>
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge bg-info px-3 py-2">
                                    <i class="mdi mdi-shield me-1"></i>{{ ucfirst($user->level) }}
                                </span>
                                <span class="badge bg-success px-3 py-2">
                                    <i class="mdi mdi-check-circle me-1"></i>Active
                                </span>
                            </div>

                            <!-- Profile Stats -->
                            <div class="border-top pt-3">
                                @if($user->level === 'pelanggan' && isset($profileData))
                                    <small class="text-muted d-block mb-2">Anggota sejak</small>
                                    <strong style="color: #05C3FB;">{{ $profileData->created_at->format('d M Y') }}</strong>
                                @elseif(in_array($user->level, ['admin', 'bendahara', 'owner']) && isset($profileData))
                                    <small class="text-muted d-block mb-2">Jabatan</small>
                                    <strong style="color: #05C3FB;">{{ ucfirst($profileData->jabatan) }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Fields -->
                <div class="col-lg-8" style="animation: fadeInRight 0.8s ease-out;">
                    <!-- Informasi Dasar -->
                    <div class="card border-0 shadow-lg mb-4" style="border-radius: 15px; transition: all 0.3s ease;"
                         onmouseover="this.style.boxShadow='0 10px 40px rgba(5, 195, 251, 0.2)'"
                         onmouseout="this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                        <div class="card-header border-0 bg-transparent py-4 px-4">
                            <h5 class="mb-0" style="color: #1F1F1F;">
                                <i class="mdi mdi-information-outline me-2" style="color: #05C3FB; font-size: 1.3rem;"></i>Informasi Dasar
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label fw-600 mb-2" style="color: #1F1F1F;">
                                            <i class="mdi mdi-account me-2"></i>Nama Lengkap
                                        </label>
                                        <div class="input-group-wrapper">
                                            <input type="text" class="form-control input-modern" id="name" name="name" 
                                                   value="{{ old('name', $user->name) }}" required
                                                   style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem 1rem;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label fw-600 mb-2" style="color: #1F1F1F;">
                                            <i class="mdi mdi-email me-2"></i>Email
                                        </label>
                                        <input type="email" class="form-control input-modern" id="email" name="email" 
                                               value="{{ old('email', $user->email) }}" required
                                               style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem 1rem;">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="no_hp" class="form-label fw-600 mb-2" style="color: #1F1F1F;">
                                            <i class="mdi mdi-phone me-2"></i>Nomor HP
                                        </label>
                                        <input type="text" class="form-control input-modern" id="no_hp" name="no_hp" 
                                               value="{{ old('no_hp', $user->no_hp) }}" required
                                               style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem 1rem;">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-600 mb-2" style="color: #1F1F1F;">
                                            <i class="mdi mdi-shield-account me-2"></i>Level Akun
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-2" style="border-color: #e9ecef; border-radius: 10px 0 0 10px;">
                                                <i class="mdi mdi-shield" style="color: #000000;"></i>
                                            </span>
                                            <input type="text" class="form-control bg-light border-2" 
                                                   value="{{ ucfirst($user->level) }}" readonly 
                                                   style="border-color: #e9ecef; border-radius: 0 10px 10px 0; cursor: not-allowed;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alamat" class="form-label fw-600 mb-2" style="color: #1F1F1F;">
                                            <i class="mdi mdi-map-marker me-2"></i>Alamat
                                        </label>
                                        <textarea class="form-control input-modern" id="alamat" name="alamat" rows="4" required
                                                  placeholder="Masukkan alamat lengkap Anda"
                                                  style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem 1rem; resize: vertical;">{{ old('alamat', $user->alamat) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    @if($user->level === 'pelanggan' && isset($profileData))
                        <div class="card border-0 shadow-lg mb-4" style="border-radius: 15px; border-left: 5px solid #05C3FB; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 40px rgba(5, 195, 251, 0.15)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                            <div class="card-header border-0 bg-transparent py-4 px-4">
                                <h5 class="mb-0" style="color: #1F1F1F;">
                                    <i class="mdi mdi-information-outline me-2" style="color: #05C3FB; font-size: 1.3rem;"></i>Informasi Pelanggan
                                </h5>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, rgba(5, 195, 251, 0.1) 0%, rgba(5, 195, 251, 0.05) 100%);">
                                            <small class="text-muted d-block mb-1">
                                                <i class="mdi mdi-identifier me-1"></i>ID Pelanggan
                                            </small>
                                            <strong style="color: #05C3FB; font-size: 1.1rem;">{{ $profileData->id }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, rgba(52, 177, 170, 0.1) 0%, rgba(52, 177, 170, 0.05) 100%);">
                                            <small class="text-muted d-block mb-1">
                                                <i class="mdi mdi-calendar me-1"></i>Tanggal Bergabung
                                            </small>
                                            <strong style="color: #34B1AA; font-size: 1.1rem;">{{ $profileData->created_at->format('d M Y') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(in_array($user->level, ['admin', 'bendahara', 'owner']) && isset($profileData))
                        <div class="card border-0 shadow-lg mb-4" style="border-radius: 15px; border-left: 5px solid #05C3FB; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 40px rgba(5, 195, 251, 0.15)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                            <div class="card-header border-0 bg-transparent py-4 px-4">
                                <h5 class="mb-0" style="color: #1F1F1F;">
                                    <i class="mdi mdi-information-outline me-2" style="color: #05C3FB; font-size: 1.3rem;"></i>Informasi Karyawan
                                </h5>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, rgba(5, 195, 251, 0.1) 0%, rgba(5, 195, 251, 0.05) 100%);">
                                            <small class="text-muted d-block mb-1">
                                                <i class="mdi mdi-briefcase me-1"></i>Jabatan
                                            </small>
                                            <strong style="color: #05C3FB; font-size: 1.1rem;">{{ ucfirst($profileData->jabatan) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, rgba(52, 177, 170, 0.1) 0%, rgba(52, 177, 170, 0.05) 100%);">
                                            <small class="text-muted d-block mb-1">
                                                <i class="mdi mdi-identifier me-1"></i>ID Karyawan
                                            </small>
                                            <strong style="color: #34B1AA; font-size: 1.1rem;">{{ $profileData->id }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-end" style="animation: fadeInUp 0.8s ease-out;">
                        <a href="{{ route(Auth::user()->level) }}" class="btn btn-outline-primary px-5 py-2" 
                           style="border-radius: 10px; border-width: 2px; font-weight: 600; transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='white'; this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                            <i class="mdi mdi-close me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2"
                                style="border-radius: 10px; background: linear-gradient(135deg, #1F3BB3 0%, #0a9fd4 100%); border: none; font-weight: 600; transition: all 0.3s ease;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 30px rgba(31, 59, 179, 0.3)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="mdi mdi-content-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Preview image sebelum upload
document.getElementById('foto').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.querySelector('.profile-image');
            preview.style.opacity = '0.5';
            setTimeout(() => {
                preview.src = event.target.result;
                preview.style.opacity = '1';
            }, 150);
        };
        reader.readAsDataURL(file);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal !== 'undefined') {
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
    }
});

// Add smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Form submission animation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const buttons = this.querySelectorAll('button[type="submit"]');
    buttons.forEach(btn => {
        btn.innerHTML = '<i class="mdi mdi-loading spin me-2"></i>Menyimpan...';
        btn.disabled = true;
    });
});
</script>

<style>
/* Modern Input Styling */
.input-modern {
    transition: all 0.3s ease;
}

.input-modern:focus {
    border-color: #05C3FB !important;
    box-shadow: 0 0 0 0.2rem rgba(5, 195, 251, 0.15);
    background-color: #fff;
}

.input-modern:hover {
    border-color: #05C3FB;
}

/* Animations */
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.spin {
    animation: spin 1s linear infinite;
}

/* Profile Card Styling */
.profile-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(5, 195, 251, 0.2) !important;
}

/* Form Label Styling */
.form-label {
    font-weight: 600;
    color: #1F1F1F;
    margin-bottom: 0.5rem;
}

/* Info Box Styling */
.info-box {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.info-box:hover {
    border-color: #05C3FB;
    transform: scale(1.02);
}

/* Button Animations */
.btn {
    transition: all 0.3s ease;
}

.btn-primary {
    box-shadow: 0 4px 15px rgba(31, 59, 179, 0.25);
}

/* Page Header */
.page-header-wrapper {
    padding: 1.5rem 0;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1F1F1F;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }

    .profile-image {
        width: 150px !important;
        height: 150px !important;
    }
}

/* Input Group Styling */
.input-group-text {
    border-radius: 10px 0 0 10px;
    cursor: pointer;
}
</style>
@endsection