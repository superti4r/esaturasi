<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Lupa Password &mdash; E-Saturasi</title>
  <link rel="icon" href="{{ asset('_root/img/favicon.ico')}}" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset ('_sys/css/module/style.css') }}">
  <link rel="stylesheet" href="{{ asset ('_sys/css/module/components.css') }}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="{{ asset ('_sys/img/esaturasi.svg') }}" alt="logo" width="120">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Lupa Password</h4></div>
              <div class="card-body">
                @if (session('success'))
                  <div class="alert alert-success">
                    {{ session('success') }}
                  </div>
                @endif

                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form method="POST" action="{{ url('/lupa-password') }}" class="needs-validation" novalidate="">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" value="{{ old('email') }}" class="form-control" name="email" tabindex="1" required autofocus>
                        <div class="invalid-feedback">
                          Silahkan isi email anda
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="2">
                          Kirim Link Reset Password
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
