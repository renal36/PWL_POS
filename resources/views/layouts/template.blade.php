<!DOCTYPE html>
@php
  $activeMenu = $activeMenu ?? '';
  $breadcrumb = $breadcrumb ?? (object)['title' => '', 'list' => []];
@endphp
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>{{ config('app.name', 'PWL Laravel Starter Code') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <!-- Google Font: Source Sans Pro -->
  <link
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    rel="stylesheet"
  />

  <!-- Font Awesome  -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- DataTables CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css"
  />

  <!-- SweetAlert2 Bootstrap4 theme -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css"
  />

  <!-- AdminLTE Theme style -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"
  />

  @stack('css')
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    @include('layouts.header')

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="{{ url('/') }}" class="brand-link">
        <img
          src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
          alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3"
          style="opacity: .8"
        />
        <span class="brand-text font-weight-light">PWL - Starter Code</span>
      </a>

      @include('layouts.sidebar')
    </aside>

    <div class="content-wrapper">
      @include('layouts.breadcrumb')

      <section class="content pt-3">
        @yield('content')
      </section>
    </div>

    @include('layouts.footer')
  </div>

  <!-- jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script> {{-- Ini opsional, hanya jika Anda butuh aturan validasi tambahan --}}

@stack('js')

</body>
</html>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables & Plugins -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

  <!-- jquery-validation -->
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/additional-methods.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- AdminLTE App -->
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

  <script>
    // Setup AJAX dengan CSRF token untuk keamanan Laravel
    $.ajaxSetup({
  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});
  </script>

  @stack('js')
</body>
</html>
