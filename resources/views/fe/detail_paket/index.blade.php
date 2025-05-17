@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="shop_top">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('paket') }}">Travel Packages</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $paket->nama_paket }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8 col-md-7 single_left">
                <!-- Image Gallery -->
                <div class="single_image mb-4">
                    <div id="packageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-3">
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $paket->foto1) }}" class="d-block w-100 package-image" style="object-fit: cover" alt="Package Image">
                            </div>
                            @if($paket->foto2)
                            <div class="carousel-item">
                                <img src="{{ asset('storage/' . $paket->foto2) }}" class="d-block w-100 package-image" style="object-fit: cover" alt="Package Image">
                            </div>
                            @endif
                            @if($paket->foto3)
                            <div class="carousel-item">
                                <img src="{{ asset('storage/' . $paket->foto3) }}" class="d-block w-100 package-image" style="object-fit: cover" alt="Package Image">
                            </div>
                            @endif
                        </div>
                        @if($paket->foto2 || $paket->foto3)
                        <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>
                    <div class="thumbnail-container mt-2">
                        <div class="row g-2">
                            <div class="col-4">
                                <img src="{{ asset('storage/' . $paket->foto1) }}" class="img-thumbnail active" style="object-fit: cover" onclick="changeSlide(0)">
                            </div>
                            @if($paket->foto2)
                            <div class="col-4">
                                <img src="{{ asset('storage/' . $paket->foto2) }}" class="img-thumbnail" style="object-fit: cover" onclick="changeSlide(1)">
                            </div>
                            @endif
                            @if($paket->foto3)
                            <div class="col-4">
                                <img src="{{ asset('storage/' . $paket->foto3) }}" class="img-thumbnail" style="object-fit: cover" onclick="changeSlide(2)">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Package Info -->
                <div class="single_right">
                    <h1 class="package-title mb-3">{{ $paket->nama_paket }}</h1>
                    <div class="package-rating mb-3">
                        <span class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </span>
                        <span class="ms-2">4.5 (24 reviews)</span>
                    </div>
                    
                    <div class="package-highlights mb-4">
                        <div class="highlight-item">
                            <i class="fas fa-clock highlight-icon"></i>
                            <span>{{ $paket->durasi }} Hari</span>
                        </div>
                    </div>
                    
                    <p class="package-short-desc mb-4">{{ $paket->deskripsi }}</p>
                    
                    <div class="package-facilities mb-4">
                        <h4 class="section-title"><i class="fas fa-umbrella-beach me-2"></i>Fasilitas Paket</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="facility-content">
                                    {!! nl2br(e($paket->fasilitas)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="col-lg-4 col-md-5">
                <div class="booking-card card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Pesan Sekarang</h4>
                        
                        @php
                            $diskon = $paket->diskonAktif ?? null;
                            $hargaNormal = $paket->harga_per_pack;
                            $hargaDiskon = $hargaNormal;
                            if ($diskon && $diskon->persen > 0) {
                                $hargaDiskon = $hargaNormal - ($hargaNormal * $diskon->persen / 100);
                            }
                        @endphp
                        
                        <div class="price-section mb-4">
                            @if($diskon && $diskon->persen > 0)
                                <div class="d-flex align-items-center">
                                    <h3 class="current-price mb-0">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</h3>
                                    <span class="discount-badge ms-3">{{ $diskon->persen }}% OFF</span>
                                </div>
                                <p class="original-price mb-0">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</p>
                            @else
                                <h3 class="current-price mb-0">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</h3>
                            @endif
                            <p class="price-note text-muted">Harga per paket</p>
                        </div>
                        
                        @auth
                        <form id="bookingForm" action="{{ route('checkout.form', $paket->id) }}" method="GET">
                            @csrf
                            <input type="hidden" name="id_paket" value="{{ $paket->id }}">
                            
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="startDate" name="tgl_mulai" min="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="endDate" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="endDate" name="tgl_akhir" disabled required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                <select class="form-select" id="jumlah_peserta" name="jumlah_peserta" required>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }} orang</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-book">
                                    <i class="fas fa-shopping-cart me-2"></i>Pesan Sekarang
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-warning">
                            <p>Anda harus <a href="{{ route('login') }}" class="alert-link">login</a> terlebih dahulu untuk melakukan pemesanan.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-warning">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary">Daftar Akun</a>
                            </div>
                        </div>
                        @endauth
                        
                        <div class="booking-features mt-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle feature-icon"></i>
                                <span>Konfirmasi instan</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-clock feature-icon"></i>
                                <span>Durasi fleksibel</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-headset feature-icon"></i>
                                <span>Dukungan 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Package Details Tabs -->
        <div class="package-details mt-5">
            <ul class="nav nav-tabs" id="packageTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Detail Paket</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Ulasan</button>
                </li>
            </ul>
            
            <div class="tab-content p-3 border border-top-0 rounded-bottom" id="packageTabsContent">
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="package-description">
                        {!! nl2br(e($paket->deskripsi)) !!}
                    </div>
                </div>
                
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="review-section">
                        <div class="review-summary text-center mb-5">
                            <div class="overall-rating">
                                <span class="rating-number">4.5</span>
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <p class="text-muted">Berdasarkan 24 ulasan</p>
                            </div>
                        </div>
                        
                        <div class="review-list">
                            <!-- Sample Review Items -->
                            <div class="review-item mb-4">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <img src="https://randomuser.me/api/portraits/women/32.jpg" class="reviewer-avatar" alt="Reviewer">
                                        <div>
                                            <h6 class="reviewer-name mb-0">Sarah Johnson</h6>
                                            <div class="review-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="review-date">2 minggu lalu</span>
                                </div>
                                <div class="review-body">
                                    <p>Perjalanan yang sangat menyenangkan! Pemandu wisata sangat informatif dan ramah. Hotelnya nyaman dan lokasinya strategis. Pasti akan merekomendasikan ke teman-teman.</p>
                                </div>
                            </div>
                            
                            <div class="review-item mb-4">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <img src="https://randomuser.me/api/portraits/men/45.jpg" class="reviewer-avatar" alt="Reviewer">
                                        <div>
                                            <h6 class="reviewer-name mb-0">David Wilson</h6>
                                            <div class="review-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="review-date">1 bulan lalu</span>
                                </div>
                                <div class="review-body">
                                    <p>Pengalaman yang bagus secara keseluruhan. Transportasi nyaman dan itinerary terorganisir dengan baik. Hanya saja waktu di beberapa destinasi terasa sedikit terburu-buru.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary">Lihat Semua Ulasan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Packages -->
        @if($related->count() > 0)
        <div class="related-packages mt-5">
            <h3 class="section-title mb-4">Paket Lainnya yang Mungkin Anda Suka</h3>
            
            <div class="row">
                @foreach($related as $r)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="package-card card h-100">
                        <div class="package-badge">
                            @if($r->diskonAktif && $r->diskonAktif->persen > 0)
                                <span class="badge bg-danger">Diskon {{ $r->diskonAktif->persen }}%</span>
                            @endif
                            <span class="badge bg-success">Populer</span>
                        </div>
                        <div class="package-image-container">
                            <img src="{{ asset('storage/' . $r->foto1) }}" class="card-img-top package-image" alt="{{ $r->nama_paket }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="package-meta mb-2">
                                <span class="package-duration"><i class="fas fa-clock me-1"></i> {{ $r->durasi }} Hari</span>
                                <span class="package-destination"><i class="fas fa-map-marker-alt me-1"></i> {{ $r->destinasi }}</span>
                            </div>
                            <h5 class="package-title"><a href="{{ route('paket.detail', $r->id) }}">{{ $r->nama_paket }}</a></h5>
                            <p class="package-excerpt">{{ \Illuminate\Support\Str::limit($r->deskripsi_singkat, 80) }}</p>
                            
                            @php
                                $diskonR = $r->diskonAktif ?? null;
                                $hargaNormalR = $r->harga_per_pack;
                                $hargaDiskonR = $hargaNormalR;
                                if ($diskonR && $diskonR->persen > 0) {
                                    $hargaDiskonR = $hargaNormalR - ($hargaNormalR * $diskonR->persen / 100);
                                }
                            @endphp
                            
                            <div class="package-price mt-auto">
                                @if($diskonR && $diskonR->persen > 0)
                                    <span class="original-price">Rp{{ number_format($hargaNormalR, 0, ',', '.') }}</span>
                                    <span class="discounted-price">Rp{{ number_format($hargaDiskonR, 0, ',', '.') }}</span>
                                @else
                                    <span class="current-price">Rp{{ number_format($hargaNormalR, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('paket.detail', $r->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                <button class="btn btn-sm btn-outline-secondary" onclick="addToWishlist({{ $r->id }})">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Floating Action Button -->
<div class="floating-buttons">
    <button class="btn-floating whatsapp">
        <i class="fab fa-whatsapp"></i>
    </button>
    <button class="btn-floating inquiry">
        <i class="fas fa-question"></i>
    </button>
</div>

<style>
    /* Main Styles */
    .package-title {
        font-weight: 700;
        color: #2c3e50;
    }
    
    .section-title {
        font-weight: 600;
        color: #2c3e50;
        position: relative;
        padding-bottom: 10px;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: #3498db;
    }
    
    /* Image Gallery */
    .carousel-inner {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        height: 170px; /* Fixed height for carousel */
    }
    
    .package-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .thumbnail-container .img-thumbnail {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
        height: 80px;
        object-fit: cover;
    }
    
    .thumbnail-container .img-thumbnail:hover,
    .thumbnail-container .img-thumbnail.active {
        border-color: #3498db;
    }
    
    /* Package Highlights */
    .package-highlights {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .highlight-item {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
    }
    
    .highlight-icon {
        color: #3498db;
        margin-right: 8px;
        font-size: 16px;
    }
    
    /* Facilities */
    .facility-content {
        line-height: 1.8;
    }
    
    /* Booking Card */
    .booking-card {
        border-radius: 10px;
        border: none;
    }
    
    .booking-card .card-title {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .price-section {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .current-price {
        font-size: 24px;
        font-weight: 700;
        color: #e74c3c;
    }
    
    .original-price {
        font-size: 16px;
        text-decoration: line-through;
        color: #95a5a6;
    }
    
    .discount-badge {
        background: #e74c3c;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .btn-book {
        background: #3498db;
        border: none;
        padding: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-book:hover {
        background: #2980b9;
    }
    
    .booking-features {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .feature-icon {
        color: #2ecc71;
        margin-right: 10px;
    }
    
    /* Package Details Tabs */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }
    
    .nav-tabs .nav-link {
        color: #7f8c8d;
        font-weight: 600;
        border: none;
        padding: 12px 20px;
    }
    
    .nav-tabs .nav-link.active {
        color: #3498db;
        background: transparent;
        border-bottom: 3px solid #3498db;
    }
    
    .package-description {
        line-height: 1.8;
    }
    
    /* Reviews */
    .review-section {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .overall-rating {
        margin-bottom: 20px;
    }
    
    .rating-number {
        font-size: 48px;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .rating-stars {
        font-size: 24px;
        color: #f39c12;
    }
    
    .review-item {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
    }
    
    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }
    
    .reviewer-name {
        font-weight: 600;
    }
    
    .review-rating {
        color: #f39c12;
        font-size: 14px;
    }
    
    .review-date {
        font-size: 12px;
        color: #95a5a6;
    }
    
    /* Related Packages */
    .package-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .package-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .package-image-container {
        height: 180px;
        overflow: hidden;
    }
    
    .package-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
    }
    
    .package-badge .badge {
        margin-right: 5px;
    }
    
    .package-meta {
        font-size: 13px;
        color: #7f8c8d;
    }
    
    .package-title {
        font-size: 18px;
        margin: 10px 0;
    }
    
    .package-title a {
        color: #2c3e50;
        text-decoration: none;
    }
    
    .package-title a:hover {
        color: #3498db;
    }
    
    .package-excerpt {
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 15px;
    }
    
    .package-price {
        font-weight: 600;
        margin-top: auto;
    }
    
    .original-price {
        font-size: 14px;
        text-decoration: line-through;
        color: #95a5a6;
        display: block;
    }
    
    .discounted-price {
        font-size: 18px;
        color: #e74c3c;
    }
    
    .current-price {
        font-size: 18px;
        color: #2c3e50;
    }
    
    /* Floating Buttons */
    .floating-buttons {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        z-index: 1000;
    }
    
    .btn-floating {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }
    
    .btn-floating:hover {
        transform: scale(1.1);
    }
    
    .whatsapp {
        background: #25D366;
    }
    
    .inquiry {
        background: #3498db;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .carousel-inner {
            height: 320px;
        }
    }
    
    @media (max-width: 767px) {
        .carousel-inner {
            height: 300px;
        }
        
        .booking-card {
            margin-top: 30px;
        }
        
        .package-image-container {
            height: 200px;
        }
    }
    
    @media (max-width: 575px) {
        .carousel-inner {
            height: 250px;
        }
    }
</style>

<script>
    function changeSlide(index) {
        const carousel = new bootstrap.Carousel(document.getElementById('packageCarousel'));
        carousel.to(index);
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-container .img-thumbnail').forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.add('active');
                thumb.style.borderColor = '#3498db';
            } else {
                thumb.classList.remove('active');
                thumb.style.borderColor = 'transparent';
            }
        });
    }
    
    function addToCart() {
        const jumlahPeserta = document.getElementById('jumlah_peserta').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            Toastify({
                text: "Harap pilih tanggal mulai dan tanggal selesai",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#e74c3c",
                stopOnFocus: true
            }).showToast();
            return;
        }
        
        Toastify({
            text: "Paket ditambahkan ke keranjang",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#2ecc71",
            stopOnFocus: true
        }).showToast();
    }
    
    function addToWishlist(packageId) {
        Toastify({
            text: "Paket ditambahkan ke wishlist",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#3498db",
            stopOnFocus: true
        }).showToast();
    }
    
    // Initialize carousel
    document.addEventListener('DOMContentLoaded', function() {
        const packageCarousel = document.getElementById('packageCarousel');
        const carousel = new bootstrap.Carousel(packageCarousel, {
            interval: 5000,
            ride: 'carousel'
        });
        
        packageCarousel.addEventListener('slid.bs.carousel', function(event) {
            changeSlide(event.to);
        });
        
        // Set minimum date for start date (today)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('startDate').min = today;
        
        // Update end date when start date changes
        document.getElementById('startDate').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDateInput = document.getElementById('endDate');
        
        if (this.value) {
            // Calculate minimum end date (start date + package duration)
            const minEndDate = new Date(startDate);
            minEndDate.setDate(minEndDate.getDate() + {{ $paket->durasi }});
            
            // Format as YYYY-MM-DD
            const minEndDateStr = minEndDate.toISOString().split('T')[0];
            
            // Set minimum end date
            endDateInput.min = minEndDateStr;
            
            // Set default end date (start date + package duration)
            endDateInput.value = minEndDateStr;
            
            // Enable end date
            endDateInput.disabled = false;
        } else {
            // Disable end date if no start date
            endDateInput.disabled = true;
            endDateInput.value = '';
        }
    });
});
</script>
@endsection