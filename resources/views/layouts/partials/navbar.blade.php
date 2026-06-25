<nav class="navbar admin-navbar navbar-expand bg-white">
  <div class="container-fluid px-3 px-lg-4">
    <button class="sidebar-toggle btn-tactile" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <span class="d-none d-md-inline ms-3 text-secondary" style="font-size: 0.9rem;">
      Welcome back, <strong class="text-body">{{ auth()->user()->name }}</strong>
    </span>

    <div class="navbar-actions ms-auto">
      <button class="icon-button theme-toggle btn-tactile" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
      </button>

      <div class="dropdown">
        <button class="profile-button dropdown-toggle btn-tactile" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid var(--admin-border);">
          <img class="avatar-img avatar-sm me-1" src="{{ asset('template/assets/images/avatar/avatar.jpg') }}" alt="Avatar" style="border: 2px solid var(--admin-primary); padding: 1px;">
          <span class="profile-name d-none d-sm-inline">{{ auth()->user()->name }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2 mt-2" style="border-radius: 12px; min-width: 200px; background-color: var(--admin-surface);">
          <li>
            <div class="px-3 py-2">
              <span class="d-block text-body fw-bold" style="font-size: 0.85rem;">{{ auth()->user()->name }}</span>
              <span class="d-block text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase;">{{ auth()->user()->role }} Account</span>
            </div>
          </li>
          <li><hr class="dropdown-divider" style="border-top: 1px solid var(--admin-border); margin: 6px 0;"></li>
          <li>
            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
              @csrf
              <button class="dropdown-item text-danger rounded-3 py-2 btn-tactile d-flex align-items-center gap-2" type="submit">
                <i class="bi bi-box-arrow-right"></i> Sign out
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
