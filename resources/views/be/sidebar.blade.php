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
        <a class="nav-link" href="{{ url("/dashboard/$routePrefix") }}">
          <i class="mdi mdi-grid-large menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Menu</li>
      @if (Auth::user()->level === 'admin')
      <li class="nav-item">
        <a class="nav-link" href="{{ route('user.index') }}">
          <i class="menu-icon mdi mdi-account-circle-outline"></i>
          <span class="menu-title">Users Manage</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
          <i class="menu-icon mdi mdi-card-text-outline"></i>
          <span class="menu-title">Menu Admin</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('penginapan.index') }}">Penginapan</a></li>
          </ul>
        </div>
        <div class="collapse"  id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('wisata.index') }}">Obyek Wisata</a></li>
          </ul>
        </div>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('reservasi.index') }}">Reservasi</a></li>
          </ul>
        </div>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('berita.index') }}">Berita</a></li>
          </ul>
        </div>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('kategori-wisata.index') }}">Kategori Wisata</a></li>
          </ul>
        </div>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('kategori-berita.index') }}">Kategori Berita</a></li>
          </ul>
        </div>
      </li>
      @elseif(Auth::user()->level === 'bendahara')
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
          <i class="menu-icon mdi mdi-card-text-outline"></i>
          <span class="menu-title">Menu Bendahara</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('paket.index') }}">Paket</a></li>
          </ul>
        </div>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('reservasi.index') }}">Reservasi</a></li>
          </ul>
        </div>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('diskon.index') }}">Diskon</a></li>
          </ul>
        </div>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('bank.index') }}">Bank</a></li>
          </ul>
        </div>
      </li>
      @elseif(Auth::user()->level === 'bendahara')
      
      @endif
    </ul>
  </nav>