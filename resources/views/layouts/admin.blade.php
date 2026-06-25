<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Hotel Management System | PPKD HOTEL">
  <title>@yield('title', 'Dashboard') | PPKD HOTEL</title>

  <!-- Google Fonts Geist -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
  
  <style>
    body {
      font-family: 'Geist', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
      background-color: #f8fafc;
    }
    .brand-title {
      font-weight: 700;
      background: linear-gradient(135deg, #38bdf8, #818cf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .brand-subtitle {
      font-size: 0.72rem;
      letter-spacing: 1.5px;
      color: #94a3b8;
    }
    
    /* Modernized Sidebar Section Headers */
    .sidebar-section-header {
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 1.2px;
      color: var(--admin-sidebar-muted) !important;
      padding: 1.25rem 1.15rem 0.4rem;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .sidebar-section-header::after {
      content: "";
      flex: 1;
      height: 1px;
      background: rgba(255, 255, 255, 0.06);
    }

    /* Sleek Sidebar Navigation links */
    .sidebar-nav .nav-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      min-height: 44px;
      padding: 0.65rem 0.9rem;
      border-radius: 8px;
      color: var(--admin-sidebar-text) !important;
      font-weight: 500;
      font-size: 0.85rem;
      transition: all 0.2s ease;
      white-space: nowrap;
      border-left: 3px solid transparent;
    }
    .sidebar-nav .nav-link:hover {
      background: rgba(255, 255, 255, 0.03) !important;
      color: var(--admin-sidebar-text-strong) !important;
      transform: translateX(4px);
    }
    .sidebar-nav .nav-link.active {
      background: linear-gradient(135deg, rgba(14, 165, 233, 0.12), rgba(99, 102, 241, 0.12)) !important;
      color: #38bdf8 !important;
      font-weight: 600;
      border-left: 3px solid #0ea5e9;
      border-radius: 0 8px 8px 0;
      box-shadow: inset 5px 0 15px rgba(14, 165, 233, 0.05);
    }
    .sidebar-nav .nav-link.active .nav-icon {
      background: rgba(14, 165, 233, 0.2) !important;
      color: #38bdf8 !important;
    }
    .nav-icon {
      width: 28px;
      height: 28px;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.04);
      color: #64748b;
      font-size: 0.85rem;
      transition: all 0.2s ease;
    }

    /* Redesigned Profile Capsule */
    .sidebar-user {
      margin: auto 1rem 1rem;
      padding: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.06) !important;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.02) !important;
      backdrop-filter: blur(10px);
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }
    .sidebar-user-avatar {
      border: 2px solid #0ea5e9;
      box-shadow: 0 0 12px rgba(14, 165, 233, 0.25);
      padding: 2px;
      background: #0f172a;
    }
    .sidebar-user strong {
      color: #f1f5f9;
      font-size: 0.9rem;
      font-weight: 600;
    }
    .sidebar-user small {
      color: #64748b;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .sidebar-footer {
      border-top: 1px solid rgba(255, 255, 255, 0.06);
      margin-inline: 1rem;
      padding: 0.85rem 0;
      color: #475569;
      font-size: 0.75rem;
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
    <div class="sidebar-backdrop" data-sidebar-close onclick="document.body.classList.remove('sidebar-open')"></div>

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
