<!DOCTYPE HTML>
<html>
<head>
<title>WisataLokal.com</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- <link href="{{ asset('fe/css/bootstrap.css') }}" rel='stylesheet' type='text/css' /> --}}
<link href="{{ asset('fe/css/style.css') }}" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('fe/css/fwslider.css') }}" media="all">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<script src="{{ asset('fe/js/jquery.min.js') }}"></script>
<script src="{{ asset('fe/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('fe/js/fwslider.js') }}"></script>
<!-- Toastify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<style>/* Modern Animations */
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

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
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

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Global Smooth Transitions */
* {
    transition-property: background-color, border-color, color, box-shadow;
    transition-duration: 0.3s;
    transition-timing-function: ease;
}

button, a, input, textarea, select {
    transition-duration: 0.2s !important;
}

<style>    .discount-badge {
        background: #ff5722;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        display: inline-block;
        margin-left: 10px;
    }
    
    .itinerary {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        margin-top: 15px;
    }
    
    .itinerary h5 {
        margin-top: 15px;
        color: #333;
    }
    
    .card {
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-radius: 16px !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(5, 195, 251, 0.15);
    }
    
    .form-control {
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid rgba(5, 195, 251, 0.2);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #05C3FB;
        box-shadow: 0 0 0 0.2rem rgba(5, 195, 251, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1F3BB3 0%, #05C3FB 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 700;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(31, 59, 179, 0.35);
    }
    
    .alert-info {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
    
    .success-icon {
        font-size: 80px;
        color: #28a745;
        margin-bottom: 20px;
    }
    
    .booking-code {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
    }
    .dropdown-menu {
        background-color: #fff !important;
    }
    
    .dropdown-item {
        color: #000 !important;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    
    /* Style untuk gambar profil */
    .profile-img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }
    .profile-img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }
    .package-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    }
    .package-card {
        width: calc(33.33% - 20px);
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .package-card:hover {
        transform: translateY(-5px);
    }
    .package-image {
        height: 200px;
        overflow: hidden;
    }
    .package-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .package-details {
        padding: 15px;
    }
    .package-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .package-location {
        color: #666;
        margin-bottom: 10px;
        display: flex;
        align-items: center.
    }
    .package-location i {
        margin-right: 5px;
    }
    .package-price {
        font-size: 20px;
        font-weight: 700;
        color: #007bff;
        margin: 10px 0;
    }
    .package-meta {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-size: 14px;
    }
    .package-rating {
        color: #ffc107;
    }
    .book-now {
        display: block;
        width: 100%;
        padding: 10px;
        background: #28a745;
        color: white;
        text-align: center;
        border: none;
        border-radius: 4px;
        margin-top: 15px;
        font-weight: 600;
        cursor: pointer.
    }
    .section-title {
        text-align: center;
        margin: 40px 0 20px;
        font-size: 28px;
        font-weight: 700.
    }
    .search-filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px.
    }
    .filter-group {
        margin-bottom: 15px.
    }
    .filter-title {
        font-weight: 600;
        margin-bottom: 8px.
    }
        /* Tambahkan di file CSS Anda */
    .dropdown-reservasi {
        width: 350px;
        max-height: 400px;
        overflow-y: auto;
        padding: 0.
    }

    .dropdown-header {
        padding: 0.75rem 1rem;
        background-color: #f8f9fa.
    }

    .reservasi-item {
        padding: 0.75rem 1rem;
        transition: background-color 0.2s.
    }

    .reservasi-item:hover {
        background-color: #f8f9fa.
    }

    .dropdown-footer {
        padding: 0.75rem 1rem;
        background-color: #f8f9fa.
    }

    @media (max-width: 767.98px) {
        .dropdown-reservasi {
            width: 300px.
        }
    }

    /* Fix SweetAlert2 z-index */
    .swal-top-container {
        z-index: 999999 !important.
    }

    .swal-top-popup {
        margin-top: 70px !important; /* Adjust based on your navbar height */
        z-index: 999999 !important.
    }

    /* Ensure navbar stays below */
    .navbar {
        z-index: 1030 !important; /* Bootstrap default is 1030 */
    }

    /* Fix dropdown menus */
    .dropdown-menu {
        z-index: 1040 !important;
    }
    
    .reducedfrom {
        text-decoration: line-through;
        color: #999;
        font-size: 0.95rem;
    }
    
    .actual {
        color: #F95F53;
        font-weight: bold;
    }
    #flexiselDemo3 {
    padding: 0;
    list-style: none;
    }
    #flexiselDemo3 li {
        padding: 0 10px;
        text-align: center;
    }
</style>
</head>
<body>
    @yield('navbar')
	@yield('slider')
    
    <!-- Stats Section -->
    {{-- <div style="padding: 60px 0; background: linear-gradient(135deg, #1F3BB3 0%, #0a5c9f 100%); animation: slideInDown 0.8s ease-out;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6 text-center">
                    <div style="animation: scaleIn 0.6s ease-out;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: #05C3FB; margin-bottom: 10px;">500+</div>
                        <div style="font-size: 1.1rem; color: white; font-weight: 700;">Destinasi Wisata</div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-top: 5px;">Di seluruh Indonesia</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <div style="animation: scaleIn 0.6s ease-out; animation-delay: 0.1s;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: #05C3FB; margin-bottom: 10px;">50K+</div>
                        <div style="font-size: 1.1rem; color: white; font-weight: 700;">Pelanggan Puas</div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-top: 5px;">Rating 4.9/5 ⭐</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <div style="animation: scaleIn 0.6s ease-out; animation-delay: 0.2s;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: #05C3FB; margin-bottom: 10px;">1000+</div>
                        <div style="font-size: 1.1rem; color: white; font-weight: 700;">Paket Tour</div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-top: 5px;">Terlengkap se-Asia</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <div style="animation: scaleIn 0.6s ease-out; animation-delay: 0.3s;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: #05C3FB; margin-bottom: 10px;">24/7</div>
                        <div style="font-size: 1.1rem; color: white; font-weight: 700;">Support Team</div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-top: 5px;">Siap membantu Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Browse Categories -->
    <div style="padding: 60px 0; background: linear-gradient(180deg, #f0f7ff 0%, rgba(5, 195, 251, 0.05) 100%); animation: slideInUp 0.8s ease-out;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 50px; animation: slideInDown 0.6s ease-out;">
                <h2 style="font-size: 2rem; font-weight: 800; color: #1F3BB3; margin-bottom: 10px;">Kategori Wisata Populer</h2>
                <p style="color: #666; font-size: 1rem;">Jelajahi berbagai pilihan destinasi wisata sesuai minat Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div style="padding: 30px 20px; background: white; border-radius: 16px; text-align: center; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease; cursor: pointer; animation: slideInDown 0.6s ease-out;" onmouseover="this.style.transform='translateY(-8px) scale(1.05)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.2)'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'; this.style.boxShadow=''">
                        <i class="fas fa-mountain" style="font-size: 2.5rem; color: #05C3FB; margin-bottom: 12px; display: block;"></i>
                        <p style="font-weight: 700; color: #1F3BB3;">Pendakian Gunung</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div style="padding: 30px 20px; background: white; border-radius: 16px; text-align: center; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease; cursor: pointer; animation: slideInDown 0.6s ease-out; animation-delay: 0.1s;" onmouseover="this.style.transform='translateY(-8px) scale(1.05)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.2)'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'; this.style.boxShadow=''">
                        <i class="fas fa-water" style="font-size: 2.5rem; color: #34B1AA; margin-bottom: 12px; display: block;"></i>
                        <p style="font-weight: 700; color: #1F3BB3;">Pantai & Laut</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div style="padding: 30px 20px; background: white; border-radius: 16px; text-align: center; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease; cursor: pointer; animation: slideInDown 0.6s ease-out; animation-delay: 0.2s;" onmouseover="this.style.transform='translateY(-8px) scale(1.05)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.2)'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'; this.style.boxShadow=''">
                        <i class="fas fa-tree" style="font-size: 2.5rem; color: #2ecc71; margin-bottom: 12px; display: block;"></i>
                        <p style="font-weight: 700; color: #1F3BB3;">Hutan & Alam</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div style="padding: 30px 20px; background: white; border-radius: 16px; text-align: center; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease; cursor: pointer; animation: slideInDown 0.6s ease-out; animation-delay: 0.3s;" onmouseover="this.style.transform='translateY(-8px) scale(1.05)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.2)'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'; this.style.boxShadow=''">
                        <i class="fas fa-gopuram" style="font-size: 2.5rem; color: #f39c12; margin-bottom: 12px; display: block;"></i>
                        <p style="font-weight: 700; color: #1F3BB3;">Budaya & Sejarah</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div style="padding: 30px 20px; background: white; border-radius: 16px; text-align: center; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease; cursor: pointer; animation: slideInDown 0.6s ease-out; animation-delay: 0.4s;" onmouseover="this.style.transform='translateY(-8px) scale(1.05)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.2)'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'; this.style.boxShadow=''">
                        <i class="fas fa-city" style="font-size: 2.5rem; color: #9b59b6; margin-bottom: 12px; display: block;"></i>
                        <p style="font-weight: 700; color: #1F3BB3;">Kota Modern</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div style="padding: 30px 20px; background: white; border-radius: 16px; text-align: center; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease; cursor: pointer; animation: slideInDown 0.6s ease-out; animation-delay: 0.5s;" onmouseover="this.style.transform='translateY(-8px) scale(1.05)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.2)'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'; this.style.boxShadow=''">
                        <i class="fas fa-utensils" style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 12px; display: block;"></i>
                        <p style="font-weight: 700; color: #1F3BB3;">Kuliner Lokal</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Section Divider -->
    <div style="margin: 60px 0; animation: slideInDown 0.8s ease-out;">
        <div style="height: 2px; background: linear-gradient(90deg, transparent 0%, #05C3FB 25%, #05C3FB 75%, transparent 100%); border-radius: 1px;"></div>
    </div>
    
    @yield('content')
    
    <!-- Modern Section Divider -->
    <div style="margin: 60px 0; animation: slideInDown 0.8s ease-out;">
        <div style="height: 2px; background: linear-gradient(90deg, transparent 0%, #05C3FB 25%, #05C3FB 75%, transparent 100%); border-radius: 1px;"></div>
    </div>
    
    @yield('product')
    
    <!-- Why Choose Us Section -->
    <div style="padding: 80px 0; background: white; animation: slideInUp 0.8s ease-out;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 60px; animation: slideInDown 0.6s ease-out;">
                <h2 style="font-size: 2rem; font-weight: 800; color: #1F3BB3; margin-bottom: 10px;">Mengapa Memilih WisataLokal?</h2>
                <p style="color: #666; font-size: 1rem;">Kami memberikan pengalaman terbaik untuk liburan impian Anda</p>
                <div style="width: 60px; height: 4px; background: linear-gradient(90deg, transparent 0%, #05C3FB 50%, transparent 100%); margin: 20px auto; border-radius: 2px;"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div style="padding: 40px; background: linear-gradient(135deg, rgba(5, 195, 251, 0.1) 0%, rgba(52, 177, 170, 0.05) 100%); border-radius: 16px; border-left: 4px solid #05C3FB; transition: all 0.3s ease; animation: slideInLeft 0.6s ease-out;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                        <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #05C3FB; margin-bottom: 20px; display: block;"></i>
                        <h4 style="color: #1F3BB3; font-weight: 800; margin-bottom: 12px; font-size: 1.1rem;">Destinasi Terpilih</h4>
                        <p style="color: #666; line-height: 1.6; margin: 0;">Setiap destinasi dipilih dengan cermat oleh tim ahli wisata berpengalaman kami.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="padding: 40px; background: linear-gradient(135deg, rgba(5, 195, 251, 0.1) 0%, rgba(52, 177, 170, 0.05) 100%); border-radius: 16px; border-left: 4px solid #34B1AA; transition: all 0.3s ease; animation: slideInDown 0.6s ease-out;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 12px 40px rgba(52, 177, 170, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                        <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: #34B1AA; margin-bottom: 20px; display: block;"></i>
                        <h4 style="color: #1F3BB3; font-weight: 800; margin-bottom: 12px; font-size: 1.1rem;">Aman & Terpercaya</h4>
                        <p style="color: #666; line-height: 1.6; margin: 0;">Semua partner kami telah tersertifikasi dan memiliki asuransi perjalanan lengkap.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="padding: 40px; background: linear-gradient(135deg, rgba(5, 195, 251, 0.1) 0%, rgba(52, 177, 170, 0.05) 100%); border-radius: 16px; border-left: 4px solid #F95F53; transition: all 0.3s ease; animation: slideInRight 0.6s ease-out;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 12px 40px rgba(249, 95, 83, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                        <i class="fas fa-tag" style="font-size: 2.5rem; color: #F95F53; margin-bottom: 20px; display: block;"></i>
                        <h4 style="color: #1F3BB3; font-weight: 800; margin-bottom: 12px; font-size: 1.1rem;">Harga Terjangkau</h4>
                        <p style="color: #666; line-height: 1.6; margin: 0;">Nikmati paket wisata berkualitas dengan harga yang kompetitif dan banyak diskon menarik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @yield('wisata')
    
    <!-- Special Offers Banner -->
    <div style="padding: 60px 0; background: linear-gradient(135deg, #1F3BB3 0%, #0a5c9f 100%); animation: slideInUp 0.8s ease-out;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" style="animation: slideInLeft 0.6s ease-out;">
                    <h3 style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 15px;">🎉 Penawaran Spesial Terbatas!</h3>
                    <p style="color: rgba(255,255,255,0.9); font-size: 1.05rem; margin-bottom: 20px; line-height: 1.8;">Dapatkan diskon hingga 40% untuk paket wisata pilihan Anda. Promosi terbatas hanya untuk 100 pemesanan pertama di bulan ini!</p>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="background: rgba(255,255,255,0.2); border-radius: 12px; padding: 15px 25px; border: 2px solid rgba(255,255,255,0.5);">
                            <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin: 0;">Waktu Tersisa:</p>
                            <p style="font-size: 1.3rem; font-weight: 800; color: #05C3FB; margin: 5px 0 0 0;" id="countdown">02:15:30</p>
                        </div>
                        <a href="#" style="padding: 15px 40px; background: linear-gradient(135deg, #05C3FB 0%, #34B1AA 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(5, 195, 251, 0.4)'" onmouseout="this.style.transform='translateY(0)'">Pesan Sekarang ✨</a>
                    </div>
                </div>
                <div class="col-lg-6" style="text-align: center; animation: slideInRight 0.6s ease-out;">
                    <div style="font-size: 4rem; margin-bottom: 20px; animation: pulse 2s infinite;">🚀</div>
                    <p style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">Jangan lewatkan kesempatan emas ini!</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials Section -->
    <div style="padding: 80px 0; background: linear-gradient(180deg, #f0f7ff 0%, rgba(5, 195, 251, 0.05) 100%); animation: slideInUp 0.8s ease-out;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 60px; animation: slideInDown 0.6s ease-out;">
                <h2 style="font-size: 2rem; font-weight: 800; color: #1F3BB3; margin-bottom: 10px;">Testimoni Pelanggan</h2>
                <p style="color: #666; font-size: 1rem;">Ribuan pelanggan puas telah merasakan pengalaman wisata bersama kami</p>
                <div style="width: 60px; height: 4px; background: linear-gradient(90deg, transparent 0%, #05C3FB 50%, transparent 100%); margin: 20px auto; border-radius: 2px;"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-4" style="animation: slideInLeft 0.6s ease-out;">
                    <div style="background: white; padding: 30px; border-radius: 16px; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'">
                        <div style="display: flex; gap: 8px; margin-bottom: 15px;">
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                        </div>
                        <p style="color: #1F1F1F; font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;">"Pengalaman terbaik! Semua paket wisata sudah terencana dengan dengan sempurna. Pelayanannya sangat memuaskan dan tim yang ramah."</p>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #05C3FB, #34B1AA); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem;">AM</div>
                            <div>
                                <p style="margin: 0; font-weight: 700; color: #1F3BB3;">Amin Maulana</p>
                                <p style="margin: 0; font-size: 0.85rem; color: #666;">Jakarta, Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="animation: slideInDown 0.6s ease-out;">
                    <div style="background: white; padding: 30px; border-radius: 16px; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'">
                        <div style="display: flex; gap: 8px; margin-bottom: 15px;">
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                        </div>
                        <p style="color: #1F1F1F; font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;">"Harga sangat terjangkau dibanding travel lain. Destinasinya indah-indah dan pemandu turnya sangat profesional dan berpengetahuan luas."</p>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #34B1AA, #1F3BB3); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem;">SD</div>
                            <div>
                                <p style="margin: 0; font-weight: 700; color: #1F3BB3;">Siti Dewi</p>
                                <p style="margin: 0; font-size: 0.85rem; color: #666;">Bandung, Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="animation: slideInRight 0.6s ease-out;">
                    <div style="background: white; padding: 30px; border-radius: 16px; border: 2px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.borderColor='#05C3FB'; this.style.boxShadow='0 12px 40px rgba(5, 195, 251, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(5, 195, 251, 0.1)'">
                        <div style="display: flex; gap: 8px; margin-bottom: 15px;">
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                        </div>
                        <p style="color: #1F1F1F; font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;">"Keluarga saya sangat senang dengan paket wisata yang ditawarkan. Fasilitas hotel bagus, makanan lezat, dan pemandu wisata yang asik."</p>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #F95F53, #05C3FB); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem;">RP</div>
                            <div>
                                <p style="margin: 0; font-weight: 700; color: #1F3BB3;">Reza Pratama</p>
                                <p style="margin: 0; font-size: 0.85rem; color: #666;">Surabaya, Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @yield('related-berita')
    
    <!-- CTA Newsletter Section -->
    <div style="padding: 60px 0; background: linear-gradient(135deg, #1F3BB3 0%, #0a5c9f 100%); animation: slideInUp 0.8s ease-out;">
        <div class="container">
            <div style="max-width: 600px; margin: 0 auto; text-align: center;">
                <h2 style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 15px; animation: slideInDown 0.6s ease-out;">Dapatkan Penawaran Eksklusif 🎁</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.05rem; margin-bottom: 30px; animation: slideInUp 0.6s ease-out;">Bergabunglah dengan 50,000+ pelanggan kami dan dapatkan diskon khusus, tips wisata, dan promo eksklusif langsung ke email Anda.</p>
                <form style="display: flex; gap: 10px; animation: slideInUp 0.8s ease-out;">
                    <input type="email" placeholder="Masukkan email Anda..." style="flex: 1; padding: 15px 20px; border: none; border-radius: 10px; font-size: 0.95rem; outline: none; transition: all 0.3s ease;" onfocus="this.style.boxShadow='0 0 0 3px rgba(5, 195, 251, 0.3)'" onblur="this.style.boxShadow=''">
                    <button type="submit" style="padding: 15px 40px; background: linear-gradient(135deg, #05C3FB 0%, #34B1AA 100%); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; white-space: nowrap;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(5, 195, 251, 0.4)'" onmouseout="this.style.transform='translateY(0)'">Subscribe</button>
                </form>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; margin-top: 15px;">✓ Tanpa spam • ✓ Dapat dibatalkan kapan saja • ✓ Dapatkan bonus promo langsung!</p>
            </div>
        </div>
    </div>
    
    <div class="footer" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #0f1419 100%); border-top: 3px solid #05C3FB; animation: slideInUp 0.8s ease-out;">
        <div class="container py-5">
            <div class="row g-5 mb-5">
                <!-- Brand Section -->
                <div class="col-lg-3 col-md-6">
                    <div style="animation: slideInLeft 0.8s ease-out;">
                        <h5 style="color: #05C3FB; font-weight: 800; margin-bottom: 1.5rem; font-size: 1.2rem; letter-spacing: 1px;">🌍 WISATALOKAL</h5>
                        <p style="color: #b0b8c0; font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem;">Platform terpercaya untuk menemukan dan memesan pengalaman wisata terbaik di Indonesia.</p>
                        <div style="display: flex; gap: 12px;">
                            <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%); display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease; transform: scale(1);" onmouseover="this.style.transform='scale(1.15)'; this.style.boxShadow='0 8px 20px rgba(5, 195, 251, 0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow=''">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%); display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease; transform: scale(1);" onmouseover="this.style.transform='scale(1.15)'; this.style.boxShadow='0 8px 20px rgba(5, 195, 251, 0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow=''">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%); display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease; transform: scale(1);" onmouseover="this.style.transform='scale(1.15)'; this.style.boxShadow='0 8px 20px rgba(5, 195, 251, 0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow=''">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%); display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease; transform: scale(1);" onmouseover="this.style.transform='scale(1.15)'; this.style.boxShadow='0 8px 20px rgba(5, 195, 251, 0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow=''">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6" style="animation: slideInDown 0.8s ease-out;">
                    <h6 style="color: #fff; font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.95rem;">Produk</h6>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Paket Wisata</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Penginapan</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Objek Wisata</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Berita & Blog</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div class="col-lg-2 col-md-6" style="animation: slideInUp 0.8s ease-out;">
                    <h6 style="color: #fff; font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.95rem;">Perusahaan</h6>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Tentang Kami</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Karir</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Press</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Kebijakan</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="col-lg-2 col-md-6" style="animation: slideInRight 0.8s ease-out;">
                    <h6 style="color: #fff; font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.95rem;">Dukungan</h6>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Hubungi Kami</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">FAQ</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Syarat & Ketentuan</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#" style="color: #b0b8c0; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Privasi</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-lg-3 col-md-6" style="animation: slideInUp 0.8s ease-out;">
                    <h6 style="color: #fff; font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.95rem;">📬 Newsletter</h6>
                    <p style="color: #b0b8c0; font-size: 0.9rem; margin-bottom: 1rem;">Dapatkan penawaran eksklusif langsung ke inbox Anda.</p>
                    <form style="display: flex; flex-direction: column; gap: 10px;">
                        <input type="email" placeholder="Email Anda" style="padding: 12px 16px; border: 2px solid rgba(5, 195, 251, 0.2); background: rgba(255,255,255,0.05); border-radius: 10px; color: white; font-size: 0.95rem; transition: all 0.3s ease;" onfocus="this.style.borderColor='#05C3FB'; this.style.backgroundColor='rgba(5,195,251,0.1)'" onblur="this.style.borderColor='rgba(5, 195, 251, 0.2)'; this.style.backgroundColor='rgba(255,255,255,0.05)'">
                        <button type="submit" style="padding: 12px 20px; background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%); border: none; border-radius: 10px; color: white; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(5, 195, 251, 0.25);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(5, 195, 251, 0.35)'" onmouseout="this.style.transform='translateY(0)'">Subscribe</button>
                    </form>
                </div>
            </div>

            <!-- Divider -->
            <div style="height: 1px; background: linear-gradient(90deg, transparent 0%, rgba(5, 195, 251, 0.3) 50%, transparent 100%); margin: 2rem 0;"></div>

            <!-- Footer Bottom -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; padding-top: 2rem;">
                <div style="color: #b0b8c0; font-size: 0.95rem;">
                    <p style="margin: 0;">© 2026 <strong style="color: #05C3FB;">WisataLokal</strong>. Semua hak dilindungi. Dengan ❤️ dari Indonesia.</p>
                </div>
                <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: flex-end;">
                    <a href="#" style="color: #b0b8c0; text-decoration: none; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Bahasa: 🇮🇩 Indonesia</a>
                    <a href="#" style="color: #b0b8c0; text-decoration: none; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#b0b8c0'">Mata Uang: IDR</a>
                </div>
            </div>
        </div>
</body>
</html>