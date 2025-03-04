<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Panel &mdash; E-Saturasi</title>
  <link rel="icon" href="{{ asset('module/Stisla/favicon.ico')}}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset ('module/Bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/FontAwesome/css/all.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Cropper/cropper.min.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Summernote/summernote-bs4.min.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/select.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/style.css') }}">
  <link rel="stylesheet" href="{{ asset ('module/Stisla/components.css') }}">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      @include('partials.navbar')
      @include('partials.sidebar')
      <div class="main-content">
        @yield('content')
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2025 <div class="bullet"></div> Rebuild by <a href="https://github.com/superti4r">Super</a>
        </div>
        <div class="footer-right">
          2.3.0
        </div>
      </footer>
    </div>
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

  @yield('scripts')

  <script src="{{ asset ('module/SweetAlert/sweetalert2.all.min.js') }}"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Oke'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Oke'
        });
    @endif
</script>

</body>
</html>
