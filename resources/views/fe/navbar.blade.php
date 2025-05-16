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
                        <a class="nav-link" href="">Penginapan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Berita</a>
                    </li>
                </ul>
                
                <!-- Right Side Menu -->
                <ul class="navbar-nav ms-auto">
                    <!-- Cart -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" id="reservasiDropdown" role="button" 
                        @auth data-bs-toggle="dropdown" aria-expanded="false" @endauth
                        @guest data-bs-toggle="tooltip" data-bs-placement="bottom" title="Login terlebih dahulu untuk melihat reservasi" @endguest>
                            <i class="fas fa-calendar-check"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                @auth
                                    @if(auth()->user()->pelanggan && method_exists(auth()->user()->pelanggan, 'reservasis'))
                                        {{ auth()->user()->pelanggan->reservasis()->where('status_reservasi', 'pesan')->count() }}
                                    @else
                                        0
                                    @endif
                                @else
                                    0
                                @endauth
                            </span>
                        </a>
                        
                        @auth
                        <ul class="dropdown-menu dropdown-menu-end dropdown-reservasi" aria-labelledby="reservasiDropdown">
                            <li class="dropdown-header">
                                <h6>Reservasi Pending</h6>
                                <small class="text-muted">
                                    @if(auth()->user()->pelanggan && method_exists(auth()->user()->pelanggan, 'reservasis'))
                                        {{ auth()->user()->pelanggan->reservasis()->where('status_reservasi', 'pesan')->count() }}
                                    @else
                                        0
                                    @endif
                                    reservasi
                                </small>
                            </li>
                            <li class="dropdown-divider"></li>
                            
                            @if(auth()->user()->pelanggan && method_exists(auth()->user()->pelanggan, 'reservasis'))
                                @forelse(auth()->user()->pelanggan->reservasis()->where('status_reservasi', 'pesan')->with('paketWisata')->get() as $reservasi)
                                <!-- ... reservasi item display ... -->
                                @empty
                                <!-- ... empty state ... -->
                                @endforelse
                            @else
                                <!-- ... not a pelanggan state ... -->
                            @endif
                            
                            <!-- ... footer ... -->
                        </ul>
                        @endauth
                    </li>
                    
                    <!-- Authentication Links -->
                    @auth
                        <!-- Tampilan ketika sudah login -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2"></span>
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
                                <li><a class="dropdown-item text-dark" href="{{route('profile.index')}}"><i class="fas fa-user me-2"></i> {{ Auth::user()->name }} - Profile</a></li>
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
                        <!-- Tampilan ketika belum login -->
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