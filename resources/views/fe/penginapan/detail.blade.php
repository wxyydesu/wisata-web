{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\penginapan\detail.blade.php --}}
@extends('fe.master')
{{-- @section('navbar')
    @include('fe.navbar')
@endsection --}}

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
             <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('penginapan') }}">Penginapan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($penginapan->nama_penginapan, 30) }}</li>
                </ol>
            </nav>
            <div class="detail-image">
                <img src="{{ asset('storage/' . $penginapan->foto1) }}" alt="{{ $penginapan->nama_penginapan }}" class="img-fluid rounded">
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="detail-title">{{ $penginapan->nama_penginapan }}</h2>
            <p class="detail-location"><i class="bi bi-geo-alt"></i> {{ $penginapan->lokasi ?? 'Lokasi tidak tersedia' }}</p>
            <p class="detail-description">
                {{ $penginapan->deskripsi }}
            </p>
            <p class="detail-price text-primary">Rp {{ number_format($penginapan->harga_per_malam, 0, ',', '.') }} / malam</p>
            @if($penginapan->status === 'tersedia')
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#bookingModal">Pesan Sekarang</button>
            @else
                <button class="btn btn-danger btn-lg" disabled>Tidak Tersedia</button>
            @endif
        </div>
    </div>
    <div class="row mt-5">
        <h3 class="section-title">Fasilitas</h3>
        <ul class="list-unstyled">
            @foreach(explode(',', $penginapan->fasilitas) as $fasilitas)
                <li><i class="bi bi-check-circle"></i> {{ trim($fasilitas) }}</li>
            @endforeach
        </ul>
    </div>

    {{-- Ulasan Section --}}
    <div class="row mt-5">
        <div class="col-md-12">
            <h3 class="section-title mb-4">Ulasan & Rating</h3>
            
            @if(Auth::check())
                {{-- Form Tambah Ulasan --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Berikan Ulasan Anda</h5>
                    </div>
                    <div class="card-body">
                        <form id="formUlasan" method="POST" action="{{ route('ulasan.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="penginapan_id" value="{{ $penginapan->id }}">
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            
                            <div class="form-group mb-3">
                                <label for="rating">Rating</label>
                                <div id="ratingStars" class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill" data-rating="{{ $i }}" style="cursor: pointer; font-size: 2rem; color: #ddd;"></i>
                                    @endfor
                                </div>
                                <input type="hidden" id="rating" name="rating" value="0" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="komentar">Komentar</label>
                                <textarea class="form-control" id="komentar" name="komentar" rows="4" placeholder="Tulis ulasan Anda di sini..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Daftar Ulasan --}}
            <div class="ulasan-list">
                @forelse($penginapan->ulasan()->latest()->get() as $ulasan)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title">{{ $ulasan->user->name }}</h5>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $ulasan->rating ? 'bi-star-fill' : 'bi-star' }}" style="color: #ffc107;"></i>
                                        @endfor
                                        <span class="text-muted">({{ $ulasan->rating }}/5)</span>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $ulasan->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="card-text">{{ $ulasan->komentar }}</p>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        Belum ada ulasan. Jadilah yang pertama memberikan ulasan!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Booking Modal --}}
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pesan Penginapan: {{ $penginapan->nama_penginapan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(Auth::check())
                    <form id="formPesan" method="POST" action="{{ route('penginapan.customer.store') }}">
                        @csrf
                        <input type="hidden" name="penginapan_id" value="{{ $penginapan->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tgl_check_in">Tanggal Check In</label>
                                    <input type="date" class="form-control" id="tgl_check_in" name="tgl_check_in" 
                                        min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tgl_check_out">Tanggal Check Out</label>
                                    <input type="date" class="form-control" id="tgl_check_out" name="tgl_check_out" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jumlah_kamar">Jumlah Kamar</label>
                            <input type="number" class="form-control" id="jumlah_kamar" name="jumlah_kamar" 
                                min="1" value="1" required>
                        </div>

                        <div class="form-group mb-3">
                            <p><strong>Harga:</strong> Rp {{ number_format($penginapan->harga_per_malam, 0, ',', '.') }}/malam</p>
                            <p id="totalDisplay">Total akan dihitung setelah Anda memilih tanggal</p>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Lanjutkan Pemesanan</button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk melakukan pemesanan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Rating Stars
    const stars = document.querySelectorAll('#ratingStars i');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;
            
            stars.forEach(s => {
                if (s.dataset.rating <= rating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });

        star.addEventListener('mouseover', function() {
            const hoverRating = this.dataset.rating;
            stars.forEach(s => {
                if (s.dataset.rating <= hoverRating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });

    document.getElementById('ratingStars').addEventListener('mouseleave', function() {
        const currentRating = ratingInput.value;
        stars.forEach(s => {
            if (s.dataset.rating <= currentRating) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#ddd';
            }
        });
    });

    // Calculate total price
    const checkInInput = document.getElementById('tgl_check_in');
    const checkOutInput = document.getElementById('tgl_check_out');
    const jumlahKamarInput = document.getElementById('jumlah_kamar');

    function updateCheckOutMinDate() {
        if (checkInInput.value) {
            const checkInDate = new Date(checkInInput.value);
            const minCheckOutDate = new Date(checkInDate);
            minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);
            
            const minDateString = minCheckOutDate.toISOString().split('T')[0];
            checkOutInput.min = minDateString;
            
            // If current checkout is before new minimum, reset it
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = minDateString;
            }
            
            calculateTotal();
        }
    }

    // Set initial checkout minimum when page loads
    if (checkOutInput) {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        checkOutInput.min = tomorrow.toISOString().split('T')[0];
    }

    function calculateTotal() {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        
        if (checkIn && checkOut && checkOut > checkIn) {
            const lama = Math.floor((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const jumlahKamar = parseInt(jumlahKamarInput.value) || 1;
            const total = {{ $penginapan->harga_per_malam }} * lama * jumlahKamar;
            document.getElementById('totalDisplay').textContent = 
                'Total: Rp ' + new Intl.NumberFormat('id-ID').format(total) + ' (' + lama + ' malam × ' + jumlahKamar + ' kamar)';
        }
    }

    checkInInput.addEventListener('change', updateCheckOutMinDate);
    checkOutInput.addEventListener('change', calculateTotal);
    jumlahKamarInput.addEventListener('change', calculateTotal);

    // Form Ulasan submission
    document.getElementById('formUlasan')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("ulasan.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Ulasan Anda telah disimpan!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Gagal menyimpan ulasan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan', 'error');
        });
    });

    // Form Pesan (Booking) submission with AJAX
    document.getElementById('formPesan')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkIn = document.getElementById('tgl_check_in').value;
        const checkOut = document.getElementById('tgl_check_out').value;
        const jumlahKamar = document.getElementById('jumlah_kamar').value;
        
        // Validate dates
        if (!checkIn || !checkOut) {
            Swal.fire('Error', 'Silakan isi tanggal check-in dan check-out', 'error');
            return;
        }

        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);

        if (checkOutDate <= checkInDate) {
            Swal.fire('Error', 'Tanggal check-out harus setelah tanggal check-in', 'error');
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu, sedang memproses pemesanan Anda',
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(this);
        
        fetch('{{ route("penginapan.customer.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pemesanan Berhasil!',
                    text: 'Sedang membawa Anda ke halaman pembayaran...',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = data.redirect_url;
                });
            } else {
                Swal.fire('Error', data.message || 'Gagal membuat pemesanan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Gagal membuat pemesanan. Silakan coba lagi.', 'error');
        });
    });
});
</script>
@endsection