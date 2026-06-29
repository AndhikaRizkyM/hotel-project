@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <div class="page-icon"
                style="border-radius: 12px; background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"><i
                    class="bi bi-speedometer2" aria-hidden="true"></i></div>
            <div>
                <p class="eyebrow mb-1">PPKD HOTEL MANAGEMENT</p>
                <h1 class="h3 mb-1 fw-bold">Superadmin Dashboard</h1>
                <p class="text-muted mb-0">Real-time status overview of bookings, housekeeping, and revenue.</p>
            </div>
        </div>
    </div>

    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
        <!-- Revenue Today -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary panel-premium border-0 shadow-sm"
                style="min-height: 150px; border-radius: 14px;">
                <div class="metric-top">
                    <span class="metric-label" style="font-weight: 700; font-size: 0.72rem; letter-spacing: 0.5px;">Revenue
                        Today</span>
                    <span class="metric-icon" style="background: rgba(37, 99, 235, 0.15); color: var(--admin-primary);"><i
                            class="bi bi-cash-stack" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.85rem; tracking: -0.5px;">Rp
                    {{ number_format($todayOps['revenue'], 0, ',', '.') }}</div>
                <div class="metric-meta text-muted">
                    <span style="font-size: 0.78rem;">Actual cash intake today</span>
                </div>
            </article>
        </div>

        <!-- Check-ins Today -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success panel-premium border-0 shadow-sm"
                style="min-height: 150px; border-radius: 14px;">
                <div class="metric-top">
                    <span class="metric-label"
                        style="font-weight: 700; font-size: 0.72rem; letter-spacing: 0.5px;">Check-Ins Today</span>
                    <span class="metric-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;"><i
                            class="bi bi-box-arrow-in-right" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.85rem; tracking: -0.5px;">
                    {{ $todayOps['checkins'] }}</div>
                <div class="metric-meta text-muted">
                    <span style="font-size: 0.78rem;">Guests arriving today</span>
                </div>
            </article>
        </div>

        <!-- Check-outs Today -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning panel-premium border-0 shadow-sm"
                style="min-height: 150px; border-radius: 14px;">
                <div class="metric-top">
                    <span class="metric-label"
                        style="font-weight: 700; font-size: 0.72rem; letter-spacing: 0.5px;">Check-Outs Today</span>
                    <span class="metric-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i
                            class="bi bi-box-arrow-left" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.85rem; tracking: -0.5px;">
                    {{ $todayOps['checkouts'] }}</div>
                <div class="metric-meta text-muted">
                    <span style="font-size: 0.78rem;">Guests departing today</span>
                </div>
            </article>
        </div>

        <!-- Pending HK Tasks -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger panel-premium border-0 shadow-sm"
                style="min-height: 150px; border-radius: 14px;">
                <div class="metric-top">
                    <span class="metric-label" style="font-weight: 700; font-size: 0.72rem; letter-spacing: 0.5px;">Pending
                        HK Tasks</span>
                    <span class="metric-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;"><i
                            class="bi bi-clock-history" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.85rem; tracking: -0.5px;">
                    {{ $pending['hk_tasks'] }}</div>
                <div class="metric-meta text-muted">
                    <span style="font-size: 0.78rem;">Active cleaning/inspections</span>
                </div>
            </article>
        </div>
    </section>

    <!-- Rooms Status Grid -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="panel border-0 shadow-sm p-4" style="border-radius: 14px; background: var(--admin-surface);">
                <h2 class="h6 mb-3 section-title fw-bold">
                    <i class="bi bi-door-open" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
                    <span>Rooms Status Summary</span>
                </h2>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-7 g-3 text-center">
                    <div class="col">
                        <div class="mini-card p-3 border-0 shadow-xs btn-tactile"
                            style="min-height: auto; border-radius: 10px; background: var(--admin-surface-soft);">
                            <strong class="d-block h4 text-success mb-0"
                                style="font-weight: 800;">{{ $roomsCount['available'] }}</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">Available</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mini-card p-3 border-0 shadow-xs btn-tactile"
                            style="min-height: auto; border-radius: 10px; background: var(--admin-surface-soft);">
                            <strong class="d-block h4 text-primary mb-0"
                                style="font-weight: 800;">{{ $roomsCount['occupied'] }}</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">Occupied</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mini-card p-3 border-0 shadow-xs btn-tactile"
                            style="min-height: auto; border-radius: 10px; background: var(--admin-surface-soft);">
                            <strong class="d-block h4 text-info mb-0"
                                style="font-weight: 800;">{{ $roomsCount['reserved'] }}</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">Reserved</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mini-card p-3 border-0 shadow-xs btn-tactile"
                            style="min-height: auto; border-radius: 10px; background: var(--admin-surface-soft);">
                            <strong class="d-block h4 text-danger mb-0"
                                style="font-weight: 800;">{{ $roomsCount['dirty'] }}</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">Dirty</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mini-card p-3 border-0 shadow-xs btn-tactile"
                            style="min-height: auto; border-radius: 10px; background: var(--admin-surface-soft);">
                            <strong class="d-block h4 text-warning mb-0"
                                style="font-weight: 800;">{{ $roomsCount['cleaning'] }}</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">Cleaning</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mini-card p-3 border-0 shadow-xs btn-tactile"
                            style="min-height: auto; border-radius: 10px; background: var(--admin-surface-soft);">
                            <strong class="d-block h4 text-secondary mb-0"
                                style="font-weight: 800;">{{ $roomsCount['maintenance'] }}</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">Maintenance</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mini-card p-3 border-0 shadow-xs btn-tactile"
                            style="min-height: auto; border-radius: 10px; background: var(--admin-surface-soft);">
                            <strong class="d-block h4 text-body mb-0"
                                style="font-weight: 800;">{{ $roomsCount['total'] }}</strong>
                            <span class="text-muted small" style="font-size: 0.75rem;">Total Rooms</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="row g-3 mt-3">
        <!-- Recent Bookings -->
        <div class="col-12 col-xl-8">
            <div class="panel border-0 shadow-sm h-100" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="panel-header mb-3">
                    <div>
                        <h2 class="h6 mb-1 section-title fw-bold">
                            <i class="bi bi-journal-check"
                                style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
                            <span>Recent Bookings</span>
                        </h2>
                        <p class="text-muted mb-0" style="font-size: 0.8rem;">Latest reservation logs processed at the
                            front office.</p>
                    </div>
                    <a class="btn btn-light btn-sm btn-tactile rounded-pill px-3"
                        href="{{ route('fo.reservations.index') }}" style="font-size: 0.8rem;">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="font-size: 0.72rem; letter-spacing: 0.5px;">RSV Number</th>
                                <th scope="col" style="font-size: 0.72rem; letter-spacing: 0.5px;">Guest</th>
                                <th scope="col" style="font-size: 0.72rem; letter-spacing: 0.5px;">Room</th>
                                <th scope="col" style="font-size: 0.72rem; letter-spacing: 0.5px;">Dates</th>
                                <th scope="col" style="font-size: 0.72rem; letter-spacing: 0.5px;">Status</th>
                                <th scope="col" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Bill</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReservations as $rsv)
                                <tr class="btn-tactile-row">
                                    <td><strong class="text-body">{{ $rsv->reservation_number }}</strong></td>
                                    <td>{{ $rsv->guest->name }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">Room
                                            {{ $rsv->room->room_number }}</span>
                                        <div class="text-muted small d-inline ms-1">{{ $rsv->room->roomType->name }}</div>
                                    </td>
                                    <td><small class="text-muted fw-semibold">{{ $rsv->check_in_date->format('d M') }} -
                                            {{ $rsv->check_out_date->format('d M Y') }}</small></td>
                                    <td>
                                        <span
                                            class="badge badge-soft-{{ $rsv->status_color }} room-status-badge">{{ $rsv->status_text }}</span>
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($rsv->total_charge, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No reservations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Room Damage reports -->
        <div class="col-12 col-xl-4">
            <div class="panel border-0 shadow-sm h-100" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="panel-header mb-3">
                    <div>
                        <h2 class="h6 mb-1 section-title fw-bold">
                            <i class="bi bi-tools" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"></i>
                            <span>Unresolved Damage</span>
                        </h2>
                        <p class="text-muted mb-0" style="font-size: 0.8rem;">Reported issues awaiting repair.</p>
                    </div>
                    <a class="btn btn-light btn-sm btn-tactile rounded-pill px-3" href="{{ route('hk.damages.index') }}"
                        style="font-size: 0.8rem;">View Details</a>
                </div>

                <div class="activity-list">
                    @forelse($recentDamages as $damage)
                        <div class="activity-item p-2 rounded-3 border-0 bg-light-subtle btn-tactile mb-2"
                            style="background: var(--admin-surface-soft) !important;">
                            <span class="activity-dot bg-danger"
                                style="margin-top: 0.55rem; width: 8px; height: 8px; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);"></span>
                            <div class="w-100 ps-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <p class="mb-0 fw-bold" style="font-size: 0.85rem;">Room
                                        {{ $damage->room->room_number }}</p>
                                    <span class="badge bg-danger-subtle text-danger px-2 py-0"
                                        style="font-size: 0.65rem;">{{ $damage->item_name }}</span>
                                </div>
                                <p class="text-muted small mb-1 mt-1">{{ $damage->description }}</p>
                                <p class="text-danger small mb-0 fw-semibold">Est: Rp
                                    {{ number_format($damage->estimated_cost, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-check2-circle text-success" style="font-size: 2.2rem;"></i>
                            <p class="text-muted small mt-2">No active damage reports. All clean!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
