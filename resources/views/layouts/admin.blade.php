<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Hotel Management System | PPKD HOTEL">
  <title>@yield('title', 'Dashboard') | PPKD HOTEL</title>

  <!-- Google Fonts Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
  
  <style>
    body {
      font-family: 'Poppins', sans-serif !important;
    }
    .brand-title {
      font-weight: 700;
      color: #0ea5e9;
    }
    .brand-subtitle {
      font-size: 0.75rem;
      letter-spacing: 1px;
    }
    .sidebar-user {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 15px;
    }
    /* Add active states for submenu / links */
    .sidebar-nav .nav-link.active {
      background-color: #1e3a8a !important;
      color: #fff !important;
    }
    @media print {
      .admin-sidebar, .admin-navbar, .admin-footer, .heading-actions, .btn {
        display: none !important;
      }
      .admin-main {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }
      .dashboard-content {
        padding: 0 !important;
      }
    }
  </style>
  @stack('styles')
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    @include('layouts.partials.sidebar')

    <div class="admin-main">
      @include('layouts.partials.navbar')

      <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
          
          <!-- Alerts / Flash Messages -->
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #22c55e !important;">
              <i class="bi bi-check-circle-fill me-2 text-success"></i>
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #ef4444 !important;">
              <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @yield('content')
        </div>
      </main>

      @include('layouts.partials.footer')
    </div>
  </div>

  <script>
    window.adminHMDUser = {
      name: @json(auth()->user()->name),
      workspace: @json(auth()->user()->role . ' Account'),
      avatar: @json(asset('template/assets/images/avatar/avatar.jpg'))
    };
  </script>
  <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/assets/js/main.js') }}"></script>
  @stack('scripts')
</body>
</html>
