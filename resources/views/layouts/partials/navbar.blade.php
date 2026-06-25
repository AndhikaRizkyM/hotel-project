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
          <img class="avatar-img avatar-sm me-1" src="{{ asset('template/assets/images/avatar/avatar.jpg') }}" alt="Avatar">
          <span class="profile-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><span class="dropdown-item-text text-muted small fw-bold text-uppercase tracking-wider px-3">{{ auth()->user()->role }} Account</span></li>
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
