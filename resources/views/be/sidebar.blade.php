<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        @php
          if (Auth::check()) {
              $routePrefix = Auth::user()->level;
              $url = url("/dashboard/$routePrefix");
          } else {
              $url = url('/login');
          }
        @endphp
        <a class="nav-link" href="{{ route($routePrefix) }}">
          <i class="mdi mdi-grid-large menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      @if (Auth::check() && (Auth::user()->level === 'admin' || Auth::user()->level === 'bendahara' || Auth::user()->level === 'owner'))
      <li class="nav-item nav-category">Menu</li>
      @endif
      
      <!-- Profile Link (Common for all) -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('profile_index') }}">
          <i class="menu-icon mdi mdi-account"></i>
          <span class="menu-title">My Profile</span>
        </a>
      </li>
      
      @if (Auth::check() && Auth::user()->level === 'admin')
      <li class="nav-item">
        <a class="nav-link" href="{{ route('user.index') }}">
          <i class="menu-icon mdi mdi-account-circle-outline"></i>
          <span class="menu-title">Users Manage</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#admin-menu" aria-expanded="false" aria-controls="admin-menu">
          <i class="menu-icon mdi mdi-card-text-outline"></i>
          <span class="menu-title">Menu Admin</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="admin-menu">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('penginapan.index') }}">Penginapan</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('wisata.index') }}">Objek Wisata</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('reservasi.index') }}">Reservasi</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('berita.index') }}">Berita</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('kategori-wisata.index') }}">Kategori Wisata</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('kategori-berita.index') }}">Kategori Berita</a></li>
          </ul>
        </div>
      </li>
      @elseif(Auth::check() && Auth::user()->level === 'bendahara')
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#bendahara-menu" aria-expanded="false" aria-controls="bendahara-menu">
          <i class="menu-icon mdi mdi-card-text-outline"></i>
          <span class="menu-title">Menu Bendahara</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="bendahara-menu">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('paket.index') }}">Paket</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('reservasi.index') }}">Reservasi</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('diskon.index') }}">Diskon</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('exportPdf') }}">Export PDF</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('exportExcel') }}">Export Excel</a></li>
          </ul>
        </div>
      </li>
      @elseif(Auth::check() && Auth::user()->level === 'owner')
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#owner-menu" aria-expanded="false" aria-controls="owner-menu">
          <i class="menu-icon mdi mdi-card-text-outline"></i>
          <span class="menu-title">Menu Owner</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="owner-menu">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('paket.index') }}">Paket</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('reservasi.index') }}">Reservasi</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('diskon.index') }}">Diskon</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('berita.index') }}">Berita</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('wisata.index') }}">Objek Wisata</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('bank.index') }}">Bank</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('exportPdf') }}">Export PDF</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('exportExcel') }}">Export Excel</a></li>
          </ul>
        </div>
      </li>
      @endif
    </ul>
  </nav>