<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>LNU - Integrated Data System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="icon" href="{{ asset('assets/images/logo/lnu_logo.png') }}" type="image/gif" nonce="{{ csp_nonce() }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/fontawesome-free/css/all.min.css') }}" nonce="{{ csp_nonce() }}">
  <!-- Vendor CSS Files -->
  <link href="{{ asset('_regna/vendor/aos/aos.css') }}" rel="stylesheet" nonce="{{ csp_nonce() }}">
  <link href="{{ asset('_regna/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" nonce="{{ csp_nonce() }}">
  <link href="{{ asset('_regna/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" nonce="{{ csp_nonce() }}">
  <link href="{{ asset('_regna/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet" nonce="{{ csp_nonce() }}">
  <link href="{{ asset('_regna/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet" nonce="{{ csp_nonce() }}">
  <link href="{{ asset('_regna/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet" nonce="{{ csp_nonce() }}">
  <!--  CSS File -->
  <link href="{{ asset('_regna/css/style.css') }}" rel="stylesheet" nonce="{{ csp_nonce() }}">
  <!-- login style -->
  <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" nonce="{{ csp_nonce() }}">
  <!-- master style -->
  <link rel="stylesheet" href="{{ asset('assets/master/master.css') }}" nonce="{{ csp_nonce() }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/select2/css/select2.min.css') }}" nonce="{{ csp_nonce() }}">
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" nonce="{{ csp_nonce() }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}" nonce="{{ csp_nonce() }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/toastr/toastr.min.css') }}" nonce="{{ csp_nonce() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/login-1.css') }}" nonce="{{ csp_nonce() }}">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center header-transparent">
    <div class="container d-flex justify-content-between align-items-center">

      <div id="logo">
        <a href="#"><img src="{{ asset('assets/images/logo/lnu_logo_header_blue.png') }}" alt=""></a>
      </div>
    </div>
  </header><!-- End Header -->

  <section id="hero">
    <div class="hero-container" data-aos="zoom-in" data-aos-delay="100">
        <h1>INTEGRATED DATA SYSTEM (IDS)</h1>
        <h2><div class="row">
            <div class="col-12">
                <div class="wrapper-1">
                    <form action="#" id="login-form">
                        <h2><img src="{{ asset('assets/images/logo/lnu_logo.png') }}" alt="" id="lnu-logo"><br><br>
                        Login</h2>
                        <div class="input-field" id="username-field">
                            <input type="text" name="username" required>
                            <label>Username</label>
                        </div>
                        <div class="input-field" id="password-field">
                            <input type="password" name="password" required>
                            <label>Password</label>
                        </div>
                        <div class="forget">
                            <a href="#" id="forgot-password">Forgot password?</a>
                        </div>
                        <button type="button" name="login">Log In</button>
                    </form>
                    <form action="#" class="hide" id="forgot-password-form">
                        <h2><img src="{{ asset('assets/images/logo/lnu_logo.png') }}" alt="" id="lnu-logo"><br><br>
                        Forgot Password</h2>
                        <div class="input-field" id="forgot-id_no-field">
                            <input type="text" name="id_no" id="forgot-id_no" required>
                            <label>Input ID No.</label>
                        </div>
                        <div class="forgot-message">
                            <label id="forgot-message"></label>
                            Your temporary password will be sent to the email address associated with your account.
                                If you are unsure of the email address registered in our system,
                                please visit the IT Support Office for assistance.
                        </div><br>
                        <button type="button" id="forgot-password-submit">Submit</button><br>
                        <button type="button" class="btn btn-primary btn-primary-scan" id="back-login"><span class="fa fa-reply"></span> Back</button>
                    </form>
                </div>
            </div>
        </div></h2>
        </div>

    </section>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">

      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>ITSO</strong>. All Rights Reserved
      </div>
      <div class="credits">
        Managed by <a href="#">ITSO</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">User Login</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <select class="hide" name="role">
                @foreach($role as $row)
                  <option value="{{$row->id}}">{{$row->name}}</option>
                @endforeach
              </select>
              <b class="input-header">Username</b>
              <div class="input input-group mb-4">
                <input type="text" class="form-control" name="username" placeholder="Enter your username" value="">
              </div>
              <b class="input-header">Password</b>
              <div class="input input-group mb-4">
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" value="">
                <span class="input-group-text cursor-pointer" tooltip="Show Password">
                  <i class="fa fa-eye" id="togglePassword"></i>
                </span>
              </div>
              <button type="button" class="btn btn-default" name="forgot" id="forgot" data-toggle="modal" data-target="#modal-forgot">Forgot password?</button>
              <br>
              <br>
              <button type="button" class="btn btn-primary btn-primary-scan" name="login">Login</button>
              <hr class="mb-0">
              <button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">

        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <div class="modal fade" id="modal-forgot">
    <div class="modal-dialog modal-md" id="forgot-form">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reset Account Password</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <br>
            <label class="input-header">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Enter your username">
            <button class="btn btn-primary">Reset Password</button>
            <br>
            <p class="mt-4 reset-account-message">A password reset link will be sent to your email address.</p>
            <br>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <!-- jQuery -->
  <script src="{{ asset('_adminLTE/plugins/jquery/jquery.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}" nonce="{{ csp_nonce() }}"></script>

  <!-- Vendor -->
  <script src="{{ asset('_regna/vendor/aos/aos.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_regna/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_regna/vendor/glightbox/js/glightbox.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_regna/vendor/isotope-layout/isotope.pkgd.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_regna/vendor/swiper/swiper-bundle.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_regna/vendor/php-email-form/validate.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_regna/js/main.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- select2 -->
  <script src="{{ asset('_adminLTE/plugins/select2/js/select2.full.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('_adminLTE/plugins/sweetalert2/sweetalert2.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- InputMask -->
  <script src="{{ asset('_adminLTE/plugins/moment/moment.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <script src="{{ asset('_adminLTE/plugins/inputmask/jquery.inputmask.bundle.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- date-range-picker -->
  <script src="{{ asset('_adminLTE/plugins/daterangepicker/daterangepicker.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- date-picker -->
  <script src="{{ asset('_adminLTE/plugins/datepicker/bootstrap-datepicker.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- timepicker -->
  <script src="{{ asset('_adminLTE/plugins/timepicker/jquery.timepicker.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- Toastr -->
  <script src="{{ asset('_adminLTE/plugins/toastr/toastr.min.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- Main -->
  <script src="{{ asset('assets/master/master.js') }}" nonce="{{ csp_nonce() }}"></script>
  <!-- Login -->
  <script src="{{ asset('assets/js/login.js') }}" nonce="{{ csp_nonce() }}"></script>
</body>

</html>
