@php
    $user = Auth::user();
@endphp
<div class="header" style="box-shadow: 0 4px 30px rgba(0,0,0,0.1); animation: slideInDown 0.6s ease-out; position: sticky; top: 0; z-index: 999;">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1F1F1F 0%, #0a0a0a 100%); padding: 1rem 0; backdrop-filter: blur(10px);">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}" style="font-weight: 700; font-size: 1.35rem; letter-spacing: -0.8px; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('fe/images/logo.png') }}" alt="WisataLokal" height="45" class="d-inline-block align-top me-3" style="transition: transform 0.3s ease; filter: brightness(1.1);" onmouseover="this.style.transform='scale(1.08) rotate(2deg)'" onmouseout="this.style.transform='scale(1) rotate(0)'" />
                <span style="background: linear-gradient(135deg, #05C3FB 0%, #34B1AA 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 1.15rem; font-weight: 800; letter-spacing: -1px;">Wisata</span><span style="color: #fff; font-size: 1.15rem; font-weight: 800; letter-spacing: -1px;">Lokal</span>
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation" style="border: 2px solid #05C3FB; border-radius: 8px;">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Left Side Menu -->
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" style="transition: all 0.3s ease; font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 8px; color: #fff; position: relative;" onmouseover="this.style.color='#05C3FB'; this.style.backgroundColor='rgba(5, 195, 251, 0.1)'" onmouseout="this.style.color='#fff'; this.style.backgroundColor='transparent'">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paket') }}" style="transition: all 0.3s ease; font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 8px; color: #fff;" onmouseover="this.style.color='#05C3FB'; this.style.backgroundColor='rgba(5, 195, 251, 0.1)'" onmouseout="this.style.color='#fff'; this.style.backgroundColor='transparent'">
                            <i class="fas fa-suitcase me-1"></i>Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('objekwisata.front') }}" style="transition: all 0.3s ease; font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 8px; color: #fff;" onmouseover="this.style.color='#05C3FB'; this.style.backgroundColor='rgba(5, 195, 251, 0.1)'" onmouseout="this.style.color='#fff'; this.style.backgroundColor='transparent'">
                            <i class="fas fa-compass me-1"></i>Destinasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penginapan') }}" style="transition: all 0.3s ease; font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 8px; color: #fff;" onmouseover="this.style.color='#05C3FB'; this.style.backgroundColor='rgba(5, 195, 251, 0.1)'" onmouseout="this.style.color='#fff'; this.style.backgroundColor='transparent'">
                            <i class="fas fa-bed me-1"></i>Penginapan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('berita') }}" style="transition: all 0.3s ease; font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 8px; color: #fff;" onmouseover="this.style.color='#05C3FB'; this.style.backgroundColor='rgba(5, 195, 251, 0.1)'" onmouseout="this.style.color='#fff'; this.style.backgroundColor='transparent'">
                            <i class="fas fa-newspaper me-1"></i>Berita
                        </a>
                    </li>
                </ul>

                <!-- Right Side Menu -->
                <ul class="navbar-nav ms-auto" style="align-items: center; gap: 12px;">
                    <!-- Reservasi Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative dropdown-toggle" href="#" id="reservasiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.3s ease; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; font-size: 1rem;" onmouseover="this.style.color='#05C3FB'; this.style.backgroundColor='rgba(5, 195, 251, 0.1)'" onmouseout="this.style.color='#fff'; this.style.backgroundColor='transparent'">
                            <i class="fas fa-suitcase me-2"></i> Pesanan
                            <span class="position-absolute translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #F95F53 0%, #d63c2d 100%); animation: pulse 2s infinite; top: -5px; right: -8px;">
                                {{ Auth::check() && Auth::user()->pelanggan ? Auth::user()->pelanggan->reservasi()->where('status_reservasi', 'menunggu konfirmasi')->count() : 0 }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-reservasi" aria-labelledby="reservasiDropdown" style="border: 1px solid rgba(5, 195, 251, 0.2); border-radius: 12px; overflow: hidden; box-shadow: 0 12px 40px rgba(5, 195, 251, 0.15); background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(240, 247, 255, 0.95) 100%); margin-top: 12px;">
                            @auth
                                <li class="dropdown-header" style="background: linear-gradient(135deg, #1F3BB3 0%, #0a5c9f 100%); color: white; padding: 1rem; border-bottom: 2px solid rgba(5, 195, 251, 0.2);">
                                    <h6 style="margin: 0; font-weight: 800; font-size: 0.95rem;"><i class="fas fa-history me-2"></i>Riwayat Pesanan Paket</h6>
                                    <small style="opacity: 0.9;">
                                        {{ Auth::user()->pelanggan ? Auth::user()->pelanggan->reservasi()->count() : 0 }} pesanan
                                    </small>
                                </li>

                                @if(Auth::user()->pelanggan)
                                    @forelse(Auth::user()->pelanggan->reservasi()->orderBy('created_at', 'desc')->take(3)->get() as $reservasi)
                                        <li class="reservasi-item" style="padding: 0.75rem 1rem; border-bottom: 1px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(5, 195, 251, 0.08)'; this.style.paddingLeft='1.25rem'" onmouseout="this.style.backgroundColor='transparent'; this.style.paddingLeft='1rem'">
                                            <a href="{{ route('pesanan.detail', $reservasi->id) }}" class="dropdown-item" style="padding: 0; border: none; background: transparent;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 style="margin: 0; font-weight: 700; color: #1F3BB3; font-size: 0.9rem;">{{ $reservasi->paketWisata->nama_paket ?? 'Paket Tidak Tersedia' }}</h6>
                                                        <small style="color: #666;">Kode: {{ $reservasi->id }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <small style="color: #999; display: block; font-size: 0.8rem;">
                                                            @php
                                                                $tgl = $reservasi->tgl_reservasi;
                                                                if (!($tgl instanceof \Carbon\Carbon)) {
                                                                    $tgl = \Carbon\Carbon::parse($tgl);
                                                                }
                                                            @endphp
                                                            {{ $tgl->format('d M Y') }}
                                                        </small>
                                                        <div>
                                                            <span class="badge" style="background: {{ $reservasi->status_reservasi === 'selesai' ? 'linear-gradient(135deg, #34B1AA 0%, #2a9a93 100%)' : ($reservasi->status_reservasi === 'batal' ? 'linear-gradient(135deg, #F95F53 0%, #d63c2d 100%)' : 'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)') }}; color: white; font-size: 0.75rem; padding: 4px 8px; border-radius: 4px;">
                                                                {{ ucfirst($reservasi->status_reservasi) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-center text-muted py-3">
                                            <i class="fas fa-inbox" style="font-size: 1.5rem; color: #ccc;"></i>
                                            <p style="margin-top: 8px; font-size: 0.9rem; color: #999;">Tidak ada pesanan</p>
                                        </li>
                                    @endforelse
                                @else
                                    <li class="text-center text-muted py-3">
                                        <i class="fas fa-inbox" style="font-size: 1.5rem; color: #ccc;"></i>
                                        <p style="margin-top: 8px; font-size: 0.9rem; color: #999;">Tidak ada pesanan</p>
                                    </li>
                                @endif

                                <li style="background: linear-gradient(135deg, #f0f7ff 0%, rgba(5, 195, 251, 0.05) 100%); padding: 1rem; text-align: center; border-bottom: 1px solid rgba(5, 195, 251, 0.1);">
                                    <a href="{{ route('pesanan.index') }}" style="color: #1F3BB3; text-decoration: none; font-weight: 700; transition: all 0.3s ease; display: inline-block; font-size: 0.9rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#1F3BB3'">Lihat Semua Paket →</a>
                                </li>

                                <li class="dropdown-divider m-1"></li>

                                <li class="dropdown-header" style="background: linear-gradient(135deg, #34B1AA 0%, #2a9a93 100%); color: white; padding: 1rem; border-bottom: 2px solid rgba(5, 195, 251, 0.2);">
                                    <h6 style="margin: 0; font-weight: 800; font-size: 0.95rem;"><i class="fas fa-bed me-2"></i>Riwayat Pemesanan Penginapan</h6>
                                    <small style="opacity: 0.9;">
                                        {{ Auth::user()->pelanggan ? Auth::user()->pelanggan->penginapanReservasis()->count() : 0 }} penginapan
                                    </small>
                                </li>

                                @if(Auth::user()->pelanggan)
                                    @forelse(Auth::user()->pelanggan->penginapanReservasis()->orderBy('created_at', 'desc')->take(3)->get() as $penginapan)
                                        <li class="reservasi-item" style="padding: 0.75rem 1rem; border-bottom: 1px solid rgba(5, 195, 251, 0.1); transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(52, 177, 170, 0.08)'; this.style.paddingLeft='1.25rem'" onmouseout="this.style.backgroundColor='transparent'; this.style.paddingLeft='1rem'">
                                            <a href="{{ route('penginapan.riwayat') }}" class="dropdown-item" style="padding: 0; border: none; background: transparent;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 style="margin: 0; font-weight: 700; color: #34B1AA; font-size: 0.9rem;">{{ $penginapan->penginapan->nama_penginapan ?? 'Penginapan Tidak Tersedia' }}</h6>
                                                        <small style="color: #666;">ID: {{ $penginapan->id }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <small style="color: #999; display: block; font-size: 0.8rem;">
                                                            @php
                                                                $tglCheck = $penginapan->tgl_check_in;
                                                                if (!($tglCheck instanceof \Carbon\Carbon)) {
                                                                    $tglCheck = \Carbon\Carbon::parse($tglCheck);
                                                                }
                                                            @endphp
                                                            {{ $tglCheck->format('d M Y') }}
                                                        </small>
                                                        <div>
                                                            <span class="badge" style="background: {{ $penginapan->status_reservasi === 'booking' ? 'linear-gradient(135deg, #34B1AA 0%, #2a9a93 100%)' : ($penginapan->status_reservasi === 'batal' ? 'linear-gradient(135deg, #F95F53 0%, #d63c2d 100%)' : 'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)') }}; color: white; font-size: 0.75rem; padding: 4px 8px; border-radius: 4px;">
                                                                {{ ucfirst($penginapan->status_reservasi) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-center text-muted py-3">
                                            <i class="fas fa-bed" style="font-size: 1.5rem; color: #ccc;"></i>
                                            <p style="margin-top: 8px; font-size: 0.9rem; color: #999;">Belum ada pemesanan penginapan</p>
                                        </li>
                                    @endforelse
                                @else
                                    <li class="text-center text-muted py-3">
                                        <i class="fas fa-bed" style="font-size: 1.5rem; color: #ccc;"></i>
                                        <p style="margin-top: 8px; font-size: 0.9rem; color: #999;">Belum ada pemesanan penginapan</p>
                                    </li>
                                @endif

                                <li style="background: linear-gradient(135deg, #f0f7ff 0%, rgba(52, 177, 170, 0.05) 100%); padding: 1rem; text-align: center;">
                                    <a href="{{ route('penginapan.riwayat') }}" style="color: #34B1AA; text-decoration: none; font-weight: 700; transition: all 0.3s ease; display: inline-block; font-size: 0.9rem;" onmouseover="this.style.color='#05C3FB'" onmouseout="this.style.color='#34B1AA'">Lihat Semua Penginapan →</a>
                                </li>
                            @else
                                <li class="text-center text-muted py-3">
                                    <i class="fas fa-lock" style="font-size: 1.5rem; color: #ccc;"></i>
                                    <p style="margin-top: 8px; font-size: 0.9rem; color: #999;">Silakan login untuk melihat pesanan</p>
                                </li>
                            @endauth
                        </ul>
                    </li>

                    <!-- Authentication Links -->
                    @auth
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.3s ease; gap: 8px; padding: 0.5rem 1rem; border-radius: 8px;">
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
                                <span style="color: #fff; font-weight: 600; font-size: 0.9rem; display: none;" id="userName">{{ Auth::user()->name }}</span>
                                <img src="{{ $photo }}" alt="Profile" class="profile-img" style="border: 2px solid #05C3FB; transition: all 0.3s ease; width: 38px; height: 38px;" onmouseover="this.style.transform='scale(1.12)'; this.style.borderColor='#0a9fd4'; this.style.boxShadow='0 0 12px rgba(5, 195, 251, 0.4)'" onmouseout="this.style.transform='scale(1)'; this.style.borderColor='#05C3FB'; this.style.boxShadow=''">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light dropdown-menu-end" aria-labelledby="userDropdown" style="border: 1px solid rgba(5, 195, 251, 0.2); border-radius: 12px; overflow: hidden; box-shadow: 0 12px 40px rgba(5, 195, 251, 0.15); margin-top: 12px;">
                                <li style="background: linear-gradient(135deg, #1F3BB3 0%, #0a5c9f 100%); padding: 1rem; border-bottom: 2px solid rgba(5, 195, 251, 0.1);">
                                    <p style="margin: 0; color: white; font-weight: 700; font-size: 0.95rem;">{{ Auth::user()->name }}</p>
                                    <small style="color: rgba(255,255,255,0.7);">{{ Auth::user()->email }}</small>
                                </li>
                                <li><a class="dropdown-item text-dark" href="{{ route('profile.index') }}" style="transition: all 0.3s ease; font-weight: 600;" onmouseover="this.style.backgroundColor='rgba(5, 195, 251, 0.1)'; this.style.color='#05C3FB'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000'"><i class="fas fa-user me-2" style="color: #05C3FB;"></i> Profile</a></li>
                                <li><a class="dropdown-item text-dark" href="#" style="transition: all 0.3s ease; font-weight: 600;" onmouseover="this.style.backgroundColor='rgba(5, 195, 251, 0.1)'; this.style.color='#05C3FB'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000'"><i class="fas fa-cog me-2" style="color: #05C3FB;"></i> Settings</a></li>
                                <li><hr class="dropdown-divider m-0"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" style="transition: all 0.3s ease; font-weight: 600;" onmouseover="this.style.backgroundColor='rgba(249, 95, 83, 0.1)'; this.style.color='#F95F53'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000'">
                                            <i class="fas fa-sign-out-alt me-2" style="color: #F95F53;"></i> Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest Actions -->
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link" style="transition: all 0.3s ease; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; color: #fff;" onmouseover="this.style.color='#05C3FB'; this.style.backgroundColor='rgba(5, 195, 251, 0.1)'" onmouseout="this.style.color='#fff'; this.style.backgroundColor='transparent'">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link" style="transition: all 0.3s ease; font-weight: 700; padding: 0.5rem 1rem; border-radius: 10px; background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%); color: white; box-shadow: 0 4px 12px rgba(5, 195, 251, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(5, 195, 251, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(5, 195, 251, 0.3)'">
                                <i class="fas fa-user-plus me-1"></i>Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</div>