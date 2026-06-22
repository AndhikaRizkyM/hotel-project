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

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="{{ route('dashboard') }}" aria-label="PPKD Hotel Dashboard">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
            <span class="brand-title">PPKD Hotel</span>
            <span class="brand-subtitle">HMS Management</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav" style="overflow-y: auto; max-height: calc(100vh - 180px);">
        <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
          <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>

        @if(auth()->user()->isAdmin())
          <div class="text-uppercase text-muted px-3 pt-3 pb-1" style="font-size: 0.7rem; font-weight: 600;">Master Data</div>
          
          <a class="nav-link {{ Request::is('master/users*') ? 'active' : '' }}" href="{{ route('master.users.index') }}">
            <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
            <span class="nav-text">Users</span>
          </a>
          <a class="nav-link {{ Request::is('master/room-types*') ? 'active' : '' }}" href="{{ route('master.room-types.index') }}">
            <span class="nav-icon"><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i></span>
            <span class="nav-text">Room Types</span>
          </a>
          <a class="nav-link {{ Request::is('master/rooms*') ? 'active' : '' }}" href="{{ route('master.rooms.index') }}">
            <span class="nav-icon"><i class="bi bi-door-closed" aria-hidden="true"></i></span>
            <span class="nav-text">Rooms</span>
          </a>
          <a class="nav-link {{ Request::is('master/fb-menus*') ? 'active' : '' }}" href="{{ route('master.fb-menus.index') }}">
            <span class="nav-icon"><i class="bi bi-egg-fried" aria-hidden="true"></i></span>
            <span class="nav-text">F&B Menu</span>
          </a>
          <a class="nav-link {{ Request::is('master/laundry-services*') ? 'active' : '' }}" href="{{ route('master.laundry-services.index') }}">
            <span class="nav-icon"><i class="bi bi-water" aria-hidden="true"></i></span>
            <span class="nav-text">Laundry Services</span>
          </a>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isFO())
          <div class="text-uppercase text-muted px-3 pt-3 pb-1" style="font-size: 0.7rem; font-weight: 600;">Front Office</div>
          
          <a class="nav-link {{ Request::is('fo/availability*') ? 'active' : '' }}" href="{{ route('fo.availability') }}">
            <span class="nav-icon"><i class="bi bi-calendar-range" aria-hidden="true"></i></span>
            <span class="nav-text">Room Availability</span>
          </a>
          <a class="nav-link {{ Request::is('fo/reservations*') ? 'active' : '' }}" href="{{ route('fo.reservations.index') }}">
            <span class="nav-icon"><i class="bi bi-journal-bookmark" aria-hidden="true"></i></span>
            <span class="nav-text">Bookings</span>
          </a>
          <a class="nav-link {{ Request::is('fo/guests*') ? 'active' : '' }}" href="{{ route('fo.guests.index') }}">
            <span class="nav-icon"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span>
            <span class="nav-text">Guest Profiles</span>
          </a>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isHK())
          <div class="text-uppercase text-muted px-3 pt-3 pb-1" style="font-size: 0.7rem; font-weight: 600;">Housekeeping</div>
          
          <a class="nav-link {{ Request::is('hk/tasks*') ? 'active' : '' }}" href="{{ route('hk.tasks') }}">
            <span class="nav-icon"><i class="bi bi-card-checklist" aria-hidden="true"></i></span>
            <span class="nav-text">Cleaning Tasks</span>
          </a>
          <a class="nav-link {{ Request::is('hk/inspections*') ? 'active' : '' }}" href="{{ route('hk.inspections.index') }}">
            <span class="nav-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
            <span class="nav-text">Inspections</span>
          </a>
          <a class="nav-link {{ Request::is('hk/damages*') ? 'active' : '' }}" href="{{ route('hk.damages.index') }}">
            <span class="nav-icon"><i class="bi bi-tools" aria-hidden="true"></i></span>
            <span class="nav-text">Damage Reports</span>
          </a>
          <a class="nav-link {{ Request::is('hk/lost-found*') ? 'active' : '' }}" href="{{ route('hk.lost-found.index') }}">
            <span class="nav-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
            <span class="nav-text">Lost & Found</span>
          </a>
          <a class="nav-link {{ Request::is('hk/maintenance*') ? 'active' : '' }}" href="{{ route('hk.maintenance.index') }}">
            <span class="nav-icon"><i class="bi bi-wrench" aria-hidden="true"></i></span>
            <span class="nav-text">Maintenance</span>
          </a>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isFB())
          <div class="text-uppercase text-muted px-3 pt-3 pb-1" style="font-size: 0.7rem; font-weight: 600;">Food & Beverage</div>
          
          <a class="nav-link {{ Request::is('fb/breakfast*') ? 'active' : '' }}" href="{{ route('fb.breakfast') }}">
            <span class="nav-icon"><i class="bi bi-egg-fried" aria-hidden="true"></i></span>
            <span class="nav-text">Breakfast List</span>
          </a>
          <a class="nav-link {{ Request::is('fb/orders*') ? 'active' : '' }}" href="{{ route('fb.orders.index') }}">
            <span class="nav-icon"><i class="bi bi-cart-check" aria-hidden="true"></i></span>
            <span class="nav-text">F&B Orders</span>
          </a>
        @endif

        @if(auth()->user()->isAdmin())
          <div class="text-uppercase text-muted px-3 pt-3 pb-1" style="font-size: 0.7rem; font-weight: 600;">Reports</div>
          <a class="nav-link {{ Request::is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
            <span class="nav-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
            <span class="nav-text">Analytics Reports</span>
          </a>
        @endif
      </nav>

      <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ asset('template/assets/images/avatar/avatar.jpg') }}" alt="Avatar">
        <strong>{{ auth()->user()->name }}</strong>
        <small class="text-white-50">{{ auth()->user()->role }} Account</small>
      </div>

      <div class="sidebar-footer">
        <span class="status-dot"></span>
        <span class="sidebar-footer-text">PPKD HMS System v1.0</span>
      </div>
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>

          <span class="d-none d-md-inline ms-3 text-secondary">
            Welcome back, <strong>{{ auth()->user()->name }}</strong>
          </span>

          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>

            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="avatar-img avatar-sm" src="{{ asset('template/assets/images/avatar/avatar.jpg') }}" alt="Avatar">
                <span class="profile-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text text-muted">Role: {{ auth()->user()->role }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Sign out</button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

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

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright 2026 PPKD Hotel Management System.</span>
          <span>Developed with Laravel 12 & AdminHMD</span>
        </div>
      </footer>
    </div>
  </div>

  <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/assets/js/main.js') }}"></script>
  @stack('scripts')
</body>
</html>
