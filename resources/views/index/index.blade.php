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
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center header-transparent">
    <div class="container d-flex justify-content-between align-items-center">

      <div id="logo">
        <a href="#hero"><img src="{{ asset('assets/images/logo/lnu_logo_header_blue.png') }}" alt=""></a>
        <!-- Uncomment below if you prefer to use a text logo -->
        <!--<h1><a href="index.html"></a></h1>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#about">About</a></li>
          <li><a class="nav-link scrollto" href="#services">Systems</a></li>
          <li><a class="nav-link scrollto" href="#team">Team</a></li>
          <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
          <li><a class="nav-link scrollto" href="#login" data-toggle="modal" data-target="#modal-default">Login</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header><!-- End Header -->

  <section id="hero">
    <div class="hero-container" data-aos="zoom-in" data-aos-delay="100">
      <h1>INTEGRATED DATA SYSTEM</h1>
      <h2>LNU - IDS</h2>
      <!-- <a href="#about" class="btn-get-started">Get Started</a> -->
    </div>
  </section>

  <main id="main">

    <section id="about">
      <div class="container" data-aos="fade-up">
        <div class="row about-container">

          <div class="col-lg-6 content order-lg-1 order-2">
            <h2 class="title">Few Words About Us</h2>
            <p>
            abc
            </p>

            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-briefcase"></i></div>
              <h4 class="title"><a href="">Eiusmod Tempor</a></h4>
              <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>
            </div>

            <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
              <div class="icon"><i class="bi bi-card-checklist"></i></div>
              <h4 class="title"><a href="">Magni Dolores</a></h4>
              <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
            </div>

            <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
              <div class="icon"><i class="bi bi-binoculars"></i></div>
              <h4 class="title"><a href="">Dolor Sitema</a></h4>
              <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tarad limino ata</p>
            </div>

          </div>

          <div class="col-lg-6 background order-lg-2 order-1" data-aos="fade-left" data-aos-delay="100"></div>
        </div>

      </div>
    </section><!-- End About Section -->


    <!-- ======= Services Section ======= -->
    <section id="services">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h3 class="section-title">Systems</h3>
          <p class="section-description">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="bi bi-briefcase"></i></a></div>
              <h4 class="title"><a href="">Lorem Ipsum</a></h4>
              <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="bi bi-card-checklist"></i></a></div>
              <h4 class="title"><a href="">Dolor Sitema</a></h4>
              <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tarad limino ata</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="bi bi-bar-chart"></i></a></div>
              <h4 class="title"><a href="">Sed ut perspiciatis</a></h4>
              <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="bi bi-binoculars"></i></a></div>
              <h4 class="title"><a href="">Magni Dolores</a></h4>
              <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="bi bi-brightness-high"></i></a></div>
              <h4 class="title"><a href="">Nemo Enim</a></h4>
              <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="bi bi-calendar4-week"></i></a></div>
              <h4 class="title"><a href="">Eiusmod Tempor</a></h4>
              <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= Team Section ======= -->
    <section id="team">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h3 class="section-title">Team</h3>
          <p class="section-description">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
        </div>
        <div class="row">
          <!-- <div class="col-lg-3 col-md-6">
            <div class="member" data-aos="fade-up" data-aos-delay="100">
              <div class="pic"><img src="assets/img/team-1.jpg" alt=""></div>
              <h4>Walter White</h4>
              <span>Chief Executive Officer</span>
              <div class="social">
                <a href=""><i class="bi bi-twitter"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="member" data-aos="fade-up" data-aos-delay="200">
              <div class="pic"><img src="assets/img/team-2.jpg" alt=""></div>
              <h4>Sarah Jhinson</h4>
              <span>Product Manager</span>
              <div class="social">
                <a href=""><i class="bi bi-twitter"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="member" data-aos="fade-up" data-aos-delay="300">
              <div class="pic"><img src="assets/img/team-3.jpg" alt=""></div>
              <h4>William Anderson</h4>
              <span>CTO</span>
              <div class="social">
                <a href=""><i class="bi bi-twitter"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="member" data-aos="fade-up" data-aos-delay="400">
              <div class="pic"><img src="assets/img/team-4.jpg" alt=""></div>
              <h4>Amanda Jepson</h4>
              <span>Accountant</span>
              <div class="social">
                <a href=""><i class="bi bi-twitter"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>
        </div> -->

      </div>
    </section><!-- End Team Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact">
      <div class="container">
        <div class="section-header">
          <h3 class="section-title">Contact</h3>
          <p class="section-description">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
        </div>
      </div>

      <!-- Uncomment below if you wan to use dynamic maps -->
      <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22864.11283411948!2d-73.96468908098944!3d40.630720240038435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew+York%2C+NY%2C+USA!5e0!3m2!1sen!2sbg!4v1540447494452" width="100%" height="380" frameborder="0" style="border:0" allowfullscreen></iframe> -->

      <div class="container mt-5">
        <div class="row justify-content-center">

          <div class="col-lg-3 col-md-4">

            <div class="info">
              <div>
                <i class="bi bi-geo-alt"></i>
                <p>A108 Adam Street<br>New York, NY 535022</p>
              </div>

              <div>
                <i class="bi bi-envelope"></i>
                <p>info@example.com</p>
              </div>

              <div>
                <i class="bi bi-phone"></i>
                <p>+1 5589 55488 55s</p>
              </div>
            </div>

            <div class="social-links">
              <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
              <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
              <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
              <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
              <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>

          </div>

          <div class="col-lg-5 col-md-8">
            <div class="form">
              <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                <div class="form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="form-group mt-3">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
                <div class="form-group mt-3">
                  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
                </div>
                <div class="form-group mt-3">
                  <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                </div>
                <div class="my-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>
                </div>
                <div class="text-center"><button type="submit">Send Message</button></div>
              </form>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">

      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>MIS</strong>. All Rights Reserved
      </div>
      <div class="credits">
        Managed by <a href="#">MIS</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-md" id="login-form">
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
