@php
    $user = Auth::user();
@endphp
<div class="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('fe/images/logo.png') }}" alt="WisataLokal" height="40" class="d-inline-block align-top">
                WisataLokal.com
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Left Side Menu -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paket') }}">Paket Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('objekwisata.front') }}">Objek Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penginapan') }}">Penginapan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('berita') }}">Berita</a>
                    </li>
                </ul>

                <!-- Right Side Menu -->
                <ul class="navbar-nav ms-auto">
                    <!-- Reservasi Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative dropdown-toggle" href="#" id="reservasiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-calendar-check"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ Auth::check() && Auth::user()->pelanggan ? Auth::user()->pelanggan->reservasi()->where('status_reservasi', 'pesan')->count() : 0 }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-reservasi" aria-labelledby="reservasiDropdown">
                            @auth
                                <li class="dropdown-header">
                                    <h6>Riwayat Pesanan</h6>
                                    <small class="text-muted">
                                        {{ Auth::user()->pelanggan ? Auth::user()->pelanggan->reservasi()->count() : 0 }} reservasi
                                    </small>
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                @if(Auth::user()->pelanggan)
                                    @forelse(Auth::user()->pelanggan->reservasi()->orderBy('created_at', 'desc')->take(5)->get() as $reservasi)
                                        <li class="reservasi-item">
                                            <a href="{{ route('pesanan.index', $reservasi->id) }}" class="dropdown-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">{{ $reservasi->paketWisata->nama_paket ?? 'Paket Tidak Tersedia' }}</h6>
                                                        <small>Kode: {{ $reservasi->id_paket }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <small>
                                                            @php
                                                                $tgl = $reservasi->tgl_reservasi;
                                                                if (!($tgl instanceof \Carbon\Carbon)) {
                                                                    $tgl = \Carbon\Carbon::parse($tgl);
                                                                }
                                                            @endphp
                                                            {{ $tgl->format('d M Y') }}
                                                        </small>
                                                        <div>
                                                            <span class="badge bg-{{ $reservasi->status_reservasi === 'selesai' ? 'success' : ($reservasi->status_reservasi === 'batal' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($reservasi->status_reservasi) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-center text-muted py-2">
                                            Tidak ada reservasi tersedia
                                        </li>
                                    @endforelse
                                @else
                                    <li class="text-center text-muted py-2">
                                        Tidak ada reservasi tersedia
                                    </li>
                                @endif

                                <li><hr class="dropdown-divider"></li>
                                <li><a href="{{ route('pesanan.index') }}" class="dropdown-item text-center">Lihat Semua</a></li>
                            @else
                                <li class="text-center text-muted py-2">
                                    Silakan login untuk melihat reservasi
                                </li>
                            @endauth
                        </ul>
                    </li>

                    <!-- Authentication Links -->
                    @auth
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                                <img src="{{ $photo }}" alt="Profile" class="profile-img">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item text-dark" href="{{ route('profile.index') }}"><i class="fas fa-user me-2"></i> {{ Auth::user()->name }} - Profile</a></li>
                                <li><a class="dropdown-item text-dark" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="guestDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2">Guest</span>
                                <img src="{{ asset('images/default-user.jpg') }}" alt="Profile" class="profile-img">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light dropdown-menu-end" aria-labelledby="guestDropdown">
                                <li><a class="dropdown-item text-dark" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i> Login</a></li>
                                <li><a class="dropdown-item text-dark" href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i> Register</a></li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</div>
