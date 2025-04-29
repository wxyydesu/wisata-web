<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        @php
          if (Auth::check()) {
              $routePrefix = Auth::user()->level;
              $url = url("/$routePrefix");
          } else {
              $url = url('/login');
          }
        @endphp
        <a class="nav-link" href="{{ url("/$routePrefix") }}">
          <i class="mdi mdi-grid-large menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Menu</li>
      @if (str_contains(Auth::user()->level, 'admin'))
      <li class="nav-item">
        <a class="nav-link" href="{{ route('user.manage') }}">
          <i class="menu-icon mdi mdi-account-circle-outline"></i>
          <span class="menu-title">Users Manage</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
          <i class="menu-icon mdi mdi-card-text-outline"></i>
          <span class="menu-title">Form elements</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
          </ul>
        </div>
      </li>
      @else
      
      @endif
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
          <i class="menu-icon mdi mdi-chart-line"></i>
          <span class="menu-title">Charts</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="charts">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
          </ul>
        </div>
      </li>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="docs/documentation.html">
          <i class="menu-icon mdi mdi-file-document"></i>
          <span class="menu-title">Documentation</span>
        </a>
      </li>
    </ul>
  </nav>