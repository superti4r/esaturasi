<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Register &mdash; E-Saturasi</title>
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
              <img src="{{ asset('module/Stisla/esaturasi.svg') }}" alt="logo" width="120">
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h4>Tambah User</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
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
                    <div class="alert alert-success-dismissible fade show">
                        <ul>
                            <li>{{ Session::get('success') }}</li>
                        </ul>
                    </div>
                @endif

                    <div class="form-group">
                      <label for="nik">NIK</label>
                      <input type="text" id="nik" name="nik" class="form-control" required>
                    </div>

                    <div class="form-group">
                      <label for="nama">Nama</label>
                      <input type="text" id="nama" name="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                      <label for="role">Role</label>
                      <select id="role" name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="administrator">Administrator</option>
                        <option value="guru">Guru</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="token">Token</label>
                      <input type="password" id="token" name="token" class="form-control" required>
                    </div>

                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
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
</body>
</html>
