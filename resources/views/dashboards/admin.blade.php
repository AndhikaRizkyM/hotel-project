@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">PPKD HOTEL MANAGEMENT</p>
      <h1 class="h3 mb-1">Superadmin Dashboard</h1>
      <p class="text-muted mb-0">Real-time status overview of bookings, housekeeping, and revenue.</p>
    </div>
  </div>
</div>

<section class="row g-3 mt-1" aria-label="Dashboard metrics">
  <!-- Revenue Today -->
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-primary">
      <div class="metric-top">
        <span class="metric-label">Revenue Today</span>
        <span class="metric-icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">Rp {{ number_format($todayOps['revenue'], 0, ',', '.') }}</div>
      <div class="metric-meta">
        <span>Actual cash intake today</span>
      </div>
    </article>
  </div>

  <!-- Check-ins Today -->
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-success">
      <div class="metric-top">
        <span class="metric-label">Check-Ins Today</span>
        <span class="metric-icon"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">{{ $todayOps['checkins'] }}</div>
      <div class="metric-meta">
        <span>Guests arriving today</span>
      </div>
    </article>
  </div>

  <!-- Check-outs Today -->
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-warning">
      <div class="metric-top">
        <span class="metric-label">Check-Outs Today</span>
        <span class="metric-icon"><i class="bi bi-box-arrow-out-left" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">{{ $todayOps['checkouts'] }}</div>
      <div class="metric-meta">
        <span>Guests departing today</span>
      </div>
    </article>
  </div>

  <!-- Pending HK Tasks -->
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-danger">
      <div class="metric-top">
        <span class="metric-label">Pending HK Tasks</span>
        <span class="metric-icon"><i class="bi bi-clock-history" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">{{ $pending['hk_tasks'] }}</div>
      <div class="metric-meta">
        <span>Active cleaning/inspections</span>
      </div>
    </article>
  </div>
</section>

<!-- Rooms Status Grid -->
<div class="row g-3 mt-3">
  <div class="col-12">
    <div class="panel">
      <h2 class="h5 mb-3 section-title"><i class="bi bi-door-open" aria-hidden="true"></i><span>Rooms Status Summary</span></h2>
      <div class="row row-cols-2 row-cols-sm-3 row-cols-md-7 g-2 text-center">
        <div class="col">
          <div class="mini-card p-2 text-center" style="min-height: auto;">
            <strong class="d-block h4 text-success mb-0">{{ $roomsCount['available'] }}</strong>
            <span class="text-muted small">Available</span>
          </div>
        </div>
        <div class="col">
          <div class="mini-card p-2 text-center" style="min-height: auto;">
            <strong class="d-block h4 text-primary mb-0">{{ $roomsCount['occupied'] }}</strong>
            <span class="text-muted small">Occupied</span>
          </div>
        </div>
        <div class="col">
          <div class="mini-card p-2 text-center" style="min-height: auto;">
            <strong class="d-block h4 text-info mb-0">{{ $roomsCount['reserved'] }}</strong>
            <span class="text-muted small">Reserved</span>
          </div>
        </div>
        <div class="col">
          <div class="mini-card p-2 text-center" style="min-height: auto;">
            <strong class="d-block h4 text-danger mb-0">{{ $roomsCount['dirty'] }}</strong>
            <span class="text-muted small">Dirty</span>
          </div>
        </div>
        <div class="col">
          <div class="mini-card p-2 text-center" style="min-height: auto;">
            <strong class="d-block h4 text-warning mb-0">{{ $roomsCount['cleaning'] }}</strong>
            <span class="text-muted small">Cleaning</span>
          </div>
        </div>
        <div class="col">
          <div class="mini-card p-2 text-center" style="min-height: auto;">
            <strong class="d-block h4 text-secondary mb-0">{{ $roomsCount['maintenance'] }}</strong>
            <span class="text-muted small">Maintenance</span>
          </div>
        </div>
        <div class="col">
          <div class="mini-card p-2 text-center" style="min-height: auto;">
            <strong class="d-block h4 text-body mb-0">{{ $roomsCount['total'] }}</strong>
            <span class="text-muted small">Total Rooms</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<section class="row g-3 mt-3">
  <!-- Recent Bookings -->
  <div class="col-12 col-xl-8">
    <div class="panel h-100">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-journal-check" aria-hidden="true"></i><span>Recent Bookings</span></h2>
          <p class="text-muted mb-0">Latest reservation logs processed at the front office.</p>
        </div>
        <a class="btn btn-light btn-sm" href="{{ route('fo.reservations.index') }}">View All</a>
      </div>
      
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">RSV Number</th>
              <th scope="col">Guest</th>
              <th scope="col">Room</th>
              <th scope="col">Dates</th>
              <th scope="col">Status</th>
              <th scope="col">Total Bill</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentReservations as $rsv)
              <tr>
                <td><strong>{{ $rsv->reservation_number }}</strong></td>
                <td>{{ $rsv->guest->name }}</td>
                <td>Room {{ $rsv->room->room_number }} ({{ $rsv->room->roomType->name }})</td>
                <td><small>{{ $rsv->check_in_date->format('d M') }} - {{ $rsv->check_out_date->format('d M Y') }}</small></td>
                <td>
                  <span class="badge badge-soft-{{ $rsv->status_color }}">{{ $rsv->status_text }}</span>
                </td>
                <td>Rp {{ number_format($rsv->total_charge, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">No reservations found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Room Damage reports -->
  <div class="col-12 col-xl-4">
    <div class="panel h-100">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-tools" aria-hidden="true"></i><span>Unresolved Damage</span></h2>
          <p class="text-muted mb-0">Reported issues awaiting repair.</p>
        </div>
        <a class="btn btn-light btn-sm" href="{{ route('hk.damages.index') }}">View Details</a>
      </div>

      <div class="activity-list">
        @forelse($recentDamages as $damage)
          <div class="activity-item">
            <span class="activity-dot bg-danger"></span>
            <div>
              <p class="mb-1 fw-semibold">Room {{ $damage->room->room_number }} - {{ $damage->item_name }}</p>
              <p class="text-muted small mb-1">{{ $damage->description }}</p>
              <p class="text-danger small mb-0">Est: Rp {{ number_format($damage->estimated_cost, 0, ',', '.') }}</p>
            </div>
          </div>
        @empty
          <p class="text-muted text-center py-4">No active damage reports. All clean!</p>
        @endforelse
      </div>
    </div>
  </div>
</section>
@endsection
