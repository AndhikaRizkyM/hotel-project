@extends('layouts.admin')

@section('title', 'Front Office Dashboard')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <div class="page-icon" style="border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981;"><i
                    class="bi bi-person-workspace" aria-hidden="true"></i></div>
            <div>
                <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
                <h1 class="h3 mb-1 fw-bold">Receptionist Desk</h1>
                <p class="text-muted mb-0">Manage guest walk-in reservations, check-ins, payments, and checkout flows.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('fo.reservations.create') }}"
                class="btn btn-primary btn-sm btn-tactile rounded-pill px-3 py-2"><i class="bi bi-journal-plus"></i> Walk-In
                Booking</a>
        </div>
    </div>

    <!-- Metrics row -->
    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success panel-premium border-0 shadow-sm"
                style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
                <div class="metric-top">
                    <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Available Rooms</span>
                    <span class="metric-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;"><i
                            class="bi bi-door-open" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">
                    {{ $roomsCount['available'] }}</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary panel-premium border-0 shadow-sm"
                style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
                <div class="metric-top">
                    <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Occupied Rooms</span>
                    <span class="metric-icon" style="background: rgba(37, 99, 235, 0.15); color: var(--admin-primary);"><i
                            class="bi bi-person-fill-check" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">
                    {{ $roomsCount['occupied'] }}</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning panel-premium border-0 shadow-sm"
                style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
                <div class="metric-top">
                    <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Reserved Rooms</span>
                    <span class="metric-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i
                            class="bi bi-journal-bookmark" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">
                    {{ $roomsCount['reserved'] }}</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger panel-premium border-0 shadow-sm"
                style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
                <div class="metric-top">
                    <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Dirty & Cleaning</span>
                    <span class="metric-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;"><i
                            class="bi bi-trash3" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">
                    {{ $roomsCount['dirty'] + $roomsCount['cleaning'] }}</div>
            </article>
        </div>
    </section>

    <!-- Main dashboard content -->
    <div class="row g-3 mt-3">
        <!-- Today's Check-ins -->
        <div class="col-12 col-xl-6">
            <div class="panel border-0 shadow-sm" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="panel-header border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0 section-title fw-bold">
                        <i class="bi bi-box-arrow-in-right"
                            style="background: rgba(16, 185, 129, 0.1); color: #10b981;"></i>
                        <span>Today's Arrivals</span>
                    </h2>
                    <span class="badge badge-soft-success" style="font-size: 0.72rem;">{{ $arrivalsToday->count() }} pending
                        check-in</span>
                </div>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table align-middle table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="font-size: 0.72rem;">Guest Name</th>
                                <th style="font-size: 0.72rem;">Room</th>
                                <th style="font-size: 0.72rem;">Payment</th>
                                <th style="font-size: 0.72rem;" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arrivalsToday as $arr)
                                <tr>
                                    <td><strong>{{ $arr->guest->name }}</strong><br><small class="text-muted font-monospace"
                                            style="font-size: 0.7rem;">{{ $arr->reservation_number }}</small></td>
                                    <td><span class="badge bg-light text-dark border">Room
                                            {{ $arr->room->room_number }}</span></td>
                                    <td>
                                        @php $dep = $arr->deposits()->sum('amount'); @endphp
                                        @if ($dep > 0)
                                            <span class="badge badge-soft-success">Dep:
                                                Rp{{ number_format($dep, 0, ',', '.') }}</span>
                                        @else
                                            <span class="badge badge-soft-warning">No Deposit</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <form action="{{ route('fo.reservations.check-in', $arr->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button class="btn btn-success btn-tactile btn-xs" type="submit"><i
                                                        class="bi bi-check-circle"></i> Check-in</button>
                                            </form>
                                            <a href="{{ route('fo.reservations.show', $arr->id) }}"
                                                class="btn btn-light btn-tactile btn-xs"><i class="bi bi-eye"></i>
                                                Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4" style="font-size: 0.85rem;">No
                                        arrivals scheduled for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Today's Check-outs -->
        <div class="col-12 col-xl-6">
            <div class="panel border-0 shadow-sm" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="panel-header border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0 section-title fw-bold">
                        <i class="bi bi-box-arrow-out-left"
                            style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"></i>
                        <span>Today's Departures</span>
                    </h2>
                    <span class="badge badge-soft-danger" style="font-size: 0.72rem;">{{ $departuresToday->count() }}
                        pending checkout</span>
                </div>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table align-middle table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="font-size: 0.72rem;">Guest Name</th>
                                <th style="font-size: 0.72rem;">Room</th>
                                <th style="font-size: 0.72rem;">Total Bill</th>
                                <th style="font-size: 0.72rem;" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departuresToday as $dep)
                                <tr>
                                    <td><strong>{{ $dep->guest->name }}</strong><br><small
                                            class="text-muted font-monospace"
                                            style="font-size: 0.7rem;">{{ $dep->reservation_number }}</small></td>
                                    <td><span class="badge bg-light text-dark border">Room
                                            {{ $dep->room->room_number }}</span></td>
                                    <td class="fw-bold">
                                        Rp {{ number_format($dep->total_charge, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('fo.reservations.show', $dep->id) }}#checkout-section"
                                                class="btn btn-danger btn-tactile btn-xs text-white"><i
                                                    class="bi bi-currency-dollar"></i> Bill & Checkout</a>
                                            <a href="{{ route('fo.reservations.show', $dep->id) }}"
                                                class="btn btn-light btn-tactile btn-xs"><i class="bi bi-eye"></i>
                                                Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4" style="font-size: 0.85rem;">No
                                        departures scheduled for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Room Map Grid -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="panel border-0 shadow-sm" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="panel-header border-bottom pb-2 mb-3">
                    <h2 class="h6 mb-0 section-title fw-bold">
                        <i class="bi bi-grid-3x3-gap"
                            style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
                        <span>Interactive Hotel Room Map</span>
                    </h2>
                </div>

                @php
                    $floors = $rooms->groupBy('floor')->sortKeysDesc();
                @endphp

                @foreach ($floors as $floor => $floorRooms)
                    <div class="mb-4">
                        <h6 class="text-uppercase fw-bold text-muted border-bottom pb-2"
                            style="font-size: 0.75rem; letter-spacing: 1px;"><i class="bi bi-layers-half me-1"></i> Floor
                            {{ $floor }}</h6>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-6 g-3">
                            @foreach ($floorRooms as $room)
                                <div class="col">
                                    <div class="room-bento-card room-{{ $room->status }} btn-tactile">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">Room
                                                    {{ $room->room_number }}</h6>
                                                <span
                                                    class="badge badge-soft-{{ $room->status_color }} room-status-badge">{{ $room->status_text }}</span>
                                            </div>
                                            <p class="text-muted mb-0" style="font-size: 0.72rem;">
                                                {{ $room->roomType->name }}</p>
                                            <p class="mb-2 text-body font-monospace fw-semibold"
                                                style="font-size: 0.75rem; opacity: 0.9;">
                                                Rp{{ number_format($room->roomType->price_per_night, 0, ',', '.') }}/Night
                                            </p>
                                        </div>

                                        <div
                                            class="room-bento-actions pt-2 border-top mt-1 d-flex gap-1 justify-content-end">
                                            @if ($room->status === 'A')
                                                <a href="{{ route('fo.reservations.create', ['room_id' => $room->id]) }}"
                                                    class="btn btn-success btn-tactile btn-xs w-100 py-1"
                                                    style="font-size: 0.7rem;"><i class="bi bi-bookmark-plus"></i>
                                                    Book</a>
                                            @elseif($room->status === 'R')
                                                @php
                                                    $res = $room->reservations()->where('status', 'RSV')->first();
                                                @endphp
                                                @if ($res)
                                                    <a href="{{ route('fo.reservations.show', $res->id) }}"
                                                        class="btn btn-info text-white btn-tactile btn-xs w-100 py-1"
                                                        style="font-size: 0.7rem;"><i class="bi bi-eye"></i> View</a>
                                                @endif
                                            @elseif($room->status === 'O')
                                                @php
                                                    $res = $room->reservations()->where('status', 'CI')->first();
                                                @endphp
                                                @if ($res)
                                                    <a href="{{ route('fo.reservations.show', $res->id) }}"
                                                        class="btn btn-primary btn-tactile btn-xs w-100 py-1"
                                                        style="font-size: 0.7rem;"><i class="bi bi-receipt"></i> Folio</a>
                                                @endif
                                            @else
                                                <span class="text-muted small py-1 w-100 text-center"
                                                    style="font-size: 0.7rem;"><i class="bi bi-slash-circle"></i> Service
                                                    Mode</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
