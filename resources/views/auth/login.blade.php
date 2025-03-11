<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; E-Saturasi</title>
  <link rel="icon" href="{{ asset('module/Stisla/favicon.ico')}}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset ('module/Bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/style.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/components.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/FontAwesome/css/all.css') }}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="{{ asset ('module/Stisla/esaturasi.svg') }}" alt="logo" width="120">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Login</h4></div>
              <div class="card-body">
                <form method="POST" action="{{ route ('login') }}" class="needs-validation" novalidate="">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::get('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{{ Session::get('success') }}</li>
                        </ul>
                    </div>
                @endif

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" value= "{{ old ('email') }}" class="form-control" name="email" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Silahkan isi email anda
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                      <div class="float-right">
                        <a href="/lupa-password" class="text-small">Lupa Password?</a>
                      </div>
                    </div>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                        <div class="input-group-append">
                          <button class="btn btn-light text-dark border-0" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                    <div class="invalid-feedback">Silahkan isi password anda</div>
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me">Ingat saya</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block d-flex align-items-center justify-content-center" tabindex="4">
                      <i class="fas fa-fingerprint mr-2"></i> Login
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="simple-footer">
              Build & Remaked by &copy; @superti4r
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script src="{{ asset ('module/jQuery/jquery.min.js') }}"></script>
  <script src="{{ asset ('module/Bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset ('module/jQueryNiceScroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset ('module/Bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset ('module/Moment/moment.min.js') }}"></script>
  <script src="{{ asset ('module/SweetAlert/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset ('module/Cropper/cropper.min.js') }}"></script>
  <script src="{{ asset ('module/Summernote/summernote-bs4.min.js') }}"></script>
  <script src="{{ asset ('module/Stisla/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset ('module/Stisla/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset ('module/Stisla/select.bootstrap4.min.js') }}"></script>
  <script src="{{ asset ('module/Stisla/stisla.js') }}"></script>
  <script src="{{ asset ('module/Stisla/scripts.js') }}"></script>
  <script src="{{ asset ('module/Stisla/custom.js') }}"></script>
  <script src="{{ asset ('module/Stisla/modules-datatables.js') }}"></script>
  <script type="module" src="{{ asset ('module/Popper/popper.min.js') }}"></script>

  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      var passwordField = document.getElementById('password');
      var icon = this.querySelector('i');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  </script>

</body>
</html>
