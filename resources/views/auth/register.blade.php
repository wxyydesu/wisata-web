<!-- filepath: c:\xampp\htdocs\LSP\wisata-web-main\resources\views\auth\registration.blade.php -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Star Admin2 </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('be/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('be/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('be/images/favicon.png') }}" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <img src="{{ asset('be/images/logo.svg') }}" alt="logo">
                </div>
                <h4>New here?</h4>
                <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
                <form class="pt-3" method="POST" action="{{ route('register-user') }}">
                  @csrf
                  <!-- Ensure this directive is present -->
                  @if(Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                  @endif
                  @if(Session::has('fail'))
                    <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                  @endif
                  <div class="form-group">
                    <input name="name" type="text" class="form-control form-control-lg" id="name" placeholder="Username" required value="{{ old('name') }}">
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group">
                    <input name="email" type="email" class="form-control form-control-lg" id="email" placeholder="Email" required value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group">
                    <input name="no_hp" type="no_hp" class="form-control form-control-lg" id="no_hp" placeholder="Phone Number" required value="{{ old('no_hp') }}">
                    @if ($errors->has('no_hp'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('no_hp') }}</strong>
                        </span>
                    @endif
                  </div>
                  {{-- <div class="form-group">
                    <select class="form-select form-select-lg" id="exampleFormControlSelect2">
                      <option>Country</option>
                      <option>United States of America</option>
                      <option>United Kingdom</option>
                      <option>India</option>
                      <option>Germany</option>
                      <option>Argentina</option>
                    </select>
                  </div> --}}
                  <div class="form-group">
                    <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> I agree to all Terms & Conditions </label>
                    </div>
                  </div>
                   <!-- Display Validation Errors -->
                    {{-- @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul class="mb-0">
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                    @endif --}}
                    <!-- Form End -->
                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">SIGN UP</button>
                    {{-- <a class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn" href="{{ route('login') }}">SIGN UP</a> --}}
                  </div>
                  <div class="text-center mt-4 fw-light"> Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
                  </div>
                </form>
                
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('be/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('be/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('be/js/off-canvas.js') }}"></script>
    <script src="{{ asset('be/js/template.js') }}"></script>
    <script src="{{ asset('be/js/settings.js') }}"></script>
    <script src="{{ asset('be/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('be/js/todolist.js') }}"></script>
    <!-- endinject -->
  </body>
</html>