<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Reset Password &mdash; E-Saturasi</title>
  <link rel="icon" href="{{ asset('module/Stisla/favicon.ico')}}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset ('module/Bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/style.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/components.css') }}">
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
              <div class="card-header"><h4>Reset Password</h4></div>
              <div class="card-body">
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form method="POST" action="{{ url('/reset-password') }}" class="needs-validation" novalidate="">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input id="password" type="password" class="form-control" name="password" required placeholder="Masukkan password baru">
                        <div class="invalid-feedback">
                          Silahkan isi password baru anda
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">Konfirmasi Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Konfirmasi password baru">
                        <div class="invalid-feedback">
                          Silahkan konfirmasi password baru anda
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                          Reset Password
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
</body>
</html>
