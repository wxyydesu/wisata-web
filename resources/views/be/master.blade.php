<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if(session('loginId'))
    <?php
        $user = \App\Models\User::find(session('loginId'));
    ?>
    <title>{{$user->level . ' - Dashboard'}} </title>
    @endif
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('be/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('be/assets/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('be/assets/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('be/assets/images/favicon.png') }}" /> 
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icon-menu"></span>
            </button>
          </div>
          <div>
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
            <a class="navbar-brand brand-logo" href="index.html">
              <img src="{{asset('be/assets/images/logo.svg')}}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="index.html">
              <img src="{{ $photo }}" alt="logo" style="border-radius: 100px"/>
            </a>
          </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
          <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
              @if(session('loginId'))
                <?php
                    $user = \App\Models\User::find(session('loginId'));
                ?>
              <h1 class="welcome-text">{{ $greeting }}, <span class="text-black fw-bold">{{ $user->name }}</span></h1>
              @endif
              <h3 class="welcome-sub-text">Your performance summary this week </h3>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item d-none d-lg-block">
              <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                <span class="input-group-addon input-group-prepend border-right">
                  <span class="icon-calendar input-group-text calendar-icon"></span>
                </span>
                <input type="text" class="form-control">
              </div>
            </li>
            {{-- Notifikasi --}}
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                <i class="icon-bell"></i>
                <span class="count">
                  {{ isset($notifications) ? count($notifications) : 0 }}
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                <a class="dropdown-item py-3 border-bottom">
                  <p class="mb-0 fw-medium float-start">
                    You have {{ isset($notifications) ? count($notifications) : 0 }} new notifications
                  </p>
                  {{-- <span class="badge badge-pill badge-primary float-end">View all</span> --}}
                </a>
                @if(isset($notifications) && count($notifications) > 0)
                  @foreach($notifications as $notif)
                    <a class="dropdown-item preview-item py-3">
                      <div class="preview-thumbnail">
                        @if($notif['type'] === 'reservasi')
                          <i class="mdi mdi-calendar-check m-auto text-primary"></i>
                        @elseif($notif['type'] === 'paket')
                          <i class="mdi mdi-package-variant m-auto text-success"></i>
                        @elseif($notif['type'] === 'penginapan')
                          <i class="mdi mdi-shield-home-outline m-auto text-info"></i>
                        @elseif($notif['type'] === 'berita')
                          <i class="mdi mdi-newspaper m-auto text-warning"></i>
                        @elseif($notif['type'] === 'obyek')
                          <i class="mdi mdi-map-marker-radius m-auto text-danger"></i>
                        @else
                          <i class="mdi mdi-bell m-auto"></i>
                        @endif
                      </div>
                      <div class="preview-item-content">
                        <h6 class="preview-subject fw-normal text-dark mb-1">
                          {{ $notif['title'] }}
                        </h6>
                        <p class="fw-light small-text mb-0">
                          {{ $notif['desc'] }}
                        </p>
                        <span class="fw-light small-text text-muted">{{ $notif['created_at']->diffForHumans() }}</span>
                      </div>
                    </a>
                  @endforeach
                @else
                  <a class="dropdown-item preview-item py-3">
                    <div class="preview-item-content">
                      <p class="fw-light small-text mb-0">No new notifications</p>
                    </div>
                  </a>
                @endif
              </div>
            </li>
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
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
              <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle" src="{{ $photo }}" alt="Profile image"> </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <img class="img-md rounded-circle" src="{{ $photo }}" alt="Profile image" style="width: 55px; height: 55px; margin-top: 10px;">
                  <p class="mb-1 mt-3 fw-semibold">{{ Auth::user()->name }}</p>
                  <p class="fw-light text-muted mb-0">{{ Auth::user()->level, Auth::user()->email}}</p>
                </div>
                <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
                <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity</a>
              @if(session('loginId'))
                <?php
                    $user = \App\Models\User::find(session('loginId'));
                ?>
                <form action="{{route('logout')}}" method="POST">
                  @csrf
                  <button class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</button>
                </form>
              @endif
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @yield('sidebar')
        <!-- partial -->
        <div class="main-panel">
          @yield('content')
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Wisata By <a href="" target="_blank">Bugar</a> For LSP.</span>
              <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2025. All rights reserved.</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- SweetAlert 2 -->
    <script>
        function deleteConfirm(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form after confirmation
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- plugins:js -->
    <script src="{{ asset('be/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('be/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('be/assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('be/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('be/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('be/assets/js/template.js') }}"></script>
    <script src="{{ asset('be/assets/js/settings.js') }}"></script>
    <script src="{{ asset('be/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('be/assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('be/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('be/assets/js/dashboard.js') }}"></script>
    <!-- <script src="{{ asset('be/assets/js/Chart.roundedBarCharts.js') }}"></script> -->
    <!-- End custom js for this page-->
  </body>
</html>