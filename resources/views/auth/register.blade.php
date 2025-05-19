<!-- filepath: c:\xampp\htdocs\LSP\wisata-web-main\resources\views\auth\registration.blade.php -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Star Admin2 </title>
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
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('be/assets/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('be/assets/images/favicon.png') }}" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <img src="{{ asset('be/assets/images/logo.svg') }}" alt="logo">
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
                    <input name="no_hp" type="text" maxlength="15" class="form-control form-control-lg" id="no_hp" placeholder="Phone Number" required value="{{ old('no_hp') }}">
                    @if ($errors->has('no_hp'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('no_hp') }}</strong>
                        </span>
                    @endif
                  </div>
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
                        <input type="checkbox" class="form-check-input" name="terms" id="terms-checkbox" {{ old('terms') ? 'checked' : '' }} required> I agree to all Terms & Conditions
                      </label>
                      @if ($errors->has('terms'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('terms') }}</strong>
                        </span>
                      @endif
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
                  <div class="text-center mt-4 fw-light"> Already have an account? <a href="{{ url('/login') }}" class="text-primary">Login</a>
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



    <!-- Modal Persetujuan -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border:none;background:transparent;font-size:1.5rem;">&times;</button>
      </div>
      <div class="modal-body" style="max-height:300px;overflow:auto;">
        {{-- Isi persetujuan di sini --}}
        <p>
          Dengan mendaftar, Anda menyetujui semua syarat dan ketentuan yang berlaku di website ini. Silakan baca dengan seksama sebelum melanjutkan.
        </p>
        <ul>
          <li>Data yang Anda masukkan harus benar dan dapat dipertanggungjawabkan.</li>
          <li>Penggunaan layanan tunduk pada kebijakan privasi dan aturan yang berlaku.</li>
          <li>Setiap pelanggaran dapat mengakibatkan penonaktifan akun.</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="decline-terms" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="accept-terms">Saya Setuju</button>
      </div>
    </div>
  </div>
</div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('be/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('be/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('be/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('be/assets/js/template.js') }}"></script>
    <script src="{{ asset('be/assets/js/settings.js') }}"></script>
    <script src="{{ asset('be/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('be/assets/js/todolist.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var termsCheckbox = document.getElementById('terms-checkbox');
  var termsModal = new bootstrap.Modal(document.getElementById('termsModal'));
  var acceptBtn = document.getElementById('accept-terms');
  var declineBtn = document.getElementById('decline-terms');
  var isModalAccepted = false;

  // Only show modal when user tries to check (not uncheck) the checkbox
  termsCheckbox.addEventListener('change', function(e) {
    if (!termsCheckbox.checked && !isModalAccepted) {
      // User is unchecking, allow
      return;
    }
    if (termsCheckbox.checked && !isModalAccepted) {
      // User is checking, show modal and revert checkbox
      e.preventDefault();
      termsCheckbox.checked = false;
      termsModal.show();
    }
  });

  acceptBtn.addEventListener('click', function() {
    isModalAccepted = true;
    termsCheckbox.checked = true;
    termsModal.hide();
  });

  declineBtn.addEventListener('click', function() {
    isModalAccepted = false;
    termsCheckbox.checked = false;
    termsModal.hide();
  });

  // Reset modal state on form reset
  document.querySelector('form').addEventListener('reset', function() {
    isModalAccepted = false;
    termsCheckbox.checked = false;
  });
});
</script>
    <!-- endinject -->
  </body>
</html>