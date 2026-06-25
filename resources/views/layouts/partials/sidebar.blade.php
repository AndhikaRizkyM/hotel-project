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
      
      <a class="nav-link {{ Request::is('hk*') ? 'active' : '' }}" href="{{ route('hk.tasks') }}">
        <span class="nav-icon"><i class="bi bi-house-gear" aria-hidden="true"></i></span>
        <span class="nav-text">Housekeeping Hub</span>
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

  <div class="sidebar-user shadow-sm">
    <img class="avatar-img avatar-md sidebar-user-avatar mb-1" src="{{ asset('template/assets/images/avatar/avatar.jpg') }}" alt="Avatar">
    <strong>{{ auth()->user()->name }}</strong>
    <small>{{ auth()->user()->role }} Account</small>
  </div>

  <div class="sidebar-footer">
    <span class="status-dot"></span>
    <span class="sidebar-footer-text">PPKD HMS System v1.0</span>
  </div>
</aside>
