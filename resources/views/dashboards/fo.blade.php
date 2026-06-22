@extends('layouts.admin')

@section('title', 'Front Office Dashboard')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-person-workspace" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
      <h1 class="h3 mb-1">Receptionist Desk</h1>
      <p class="text-muted mb-0">Manage guest walk-in reservations, check-ins, payments, and checkout flows.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('fo.reservations.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-journal-plus"></i> Walk-In Booking</a>
  </div>
</div>

<!-- Metrics row -->
<section class="row g-3 mt-1">
  <div class="col-6 col-md-3">
    <div class="metric-card metric-success p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Available Rooms</span>
      <span class="h3 mb-0 text-success">{{ $roomsCount['available'] }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="metric-card metric-primary p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Occupied Rooms</span>
      <span class="h3 mb-0 text-primary">{{ $roomsCount['occupied'] }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="metric-card metric-info p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Reserved Rooms</span>
      <span class="h3 mb-0 text-info">{{ $roomsCount['reserved'] }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="metric-card metric-danger p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Dirty / Cleaning</span>
      <span class="h3 mb-0 text-danger">{{ $roomsCount['dirty'] + $roomsCount['cleaning'] }}</span>
    </div>
  </div>
</section>

<!-- Main dashboard content -->
<div class="row g-3 mt-3">
  <!-- Today's Check-ins -->
  <div class="col-12 col-xl-6">
    <div class="panel">
      <div class="panel-header border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-box-arrow-in-right text-success"></i><span>Today's Arrivals</span></h2>
        <span class="badge bg-success">{{ $arrivalsToday->count() }} pending check-in</span>
      </div>
      <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
        <table class="table align-middle table-sm">
          <thead>
            <tr>
              <th>Guest Name</th>
              <th>Room</th>
              <th>Payment</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($arrivalsToday as $arr)
              <tr>
                <td><strong>{{ $arr->guest->name }}</strong><br><small class="text-muted">{{ $arr->reservation_number }}</small></td>
                <td>Room {{ $arr->room->room_number }}</td>
                <td>
                  @php $dep = $arr->deposits()->sum('amount'); @endphp
                  @if($dep > 0)
                    <span class="badge bg-success">Dep: Rp{{ number_format($dep, 0, ',', '.') }}</span>
                  @else
                    <span class="badge bg-warning">No Deposit</span>
                  @endif
                </td>
                <td>
                  <form action="{{ route('fo.reservations.check-in', $arr->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-xs" type="submit"><i class="bi bi-check-circle"></i> Check-in</button>
                  </form>
                  <a href="{{ route('fo.reservations.show', $arr->id) }}" class="btn btn-light btn-xs"><i class="bi bi-eye"></i> Detail</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-3">No arrivals scheduled for today.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Today's Check-outs -->
  <div class="col-12 col-xl-6">
    <div class="panel">
      <div class="panel-header border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-box-arrow-out-left text-danger"></i><span>Today's Departures</span></h2>
        <span class="badge bg-danger">{{ $departuresToday->count() }} pending checkout</span>
      </div>
      <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
        <table class="table align-middle table-sm">
          <thead>
            <tr>
              <th>Guest Name</th>
              <th>Room</th>
              <th>Total Bill</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($departuresToday as $dep)
              <tr>
                <td><strong>{{ $dep->guest->name }}</strong><br><small class="text-muted">{{ $dep->reservation_number }}</small></td>
                <td>Room {{ $dep->room->room_number }}</td>
                <td>
                  Rp {{ number_format($dep->total_charge, 0, ',', '.') }}
                </td>
                <td>
                  <a href="{{ route('fo.reservations.show', $dep->id) }}#checkout-section" class="btn btn-danger btn-xs"><i class="bi bi-currency-dollar"></i> Bill & Checkout</a>
                  <a href="{{ route('fo.reservations.show', $dep->id) }}" class="btn btn-light btn-xs"><i class="bi bi-eye"></i> Detail</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-3">No departures scheduled for today.</td>
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
    <div class="panel">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-grid-3x3-gap text-primary"></i><span>Interactive Hotel Room Map</span></h2>
      </div>
      
      @php
        $floors = $rooms->groupBy('floor')->sortKeysDesc();
      @endphp
      
      @foreach($floors as $floor => $floorRooms)
        <div class="mb-4">
          <h6 class="text-uppercase fw-bold text-muted border-bottom pb-1"><i class="bi bi-layers-half text-secondary"></i> Floor {{ $floor }}</h6>
          <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 row-cols-xl-6 g-3">
            @foreach($floorRooms as $room)
              <div class="col">
                <div class="card h-100 shadow-xs border-{{ $room->status_color }}" style="border-left: 5px solid !important;">
                  <div class="card-body p-2 d-flex flex-column justify-content-between">
                    <div>
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="card-title mb-0 fw-bold">Room {{ $room->room_number }}</h6>
                        <span class="badge bg-{{ $room->status_color }} text-wrap" style="font-size: 0.65rem; padding: 2px 4px;">{{ $room->status_text }}</span>
                      </div>
                      <p class="card-text text-muted mb-0" style="font-size: 0.75rem;">{{ $room->roomType->name }}</p>
                      <p class="card-text mb-2 text-dark font-monospace" style="font-size: 0.75rem;">Rp{{ number_format($room->roomType->price_per_night, 0, ',', '.') }}/N</p>
                    </div>
                    
                    <div class="pt-2 border-top mt-1 d-flex gap-1 justify-content-end">
                      @if($room->status === 'A')
                        <a href="{{ route('fo.reservations.create', ['room_id' => $room->id]) }}" class="btn btn-outline-success btn-xs py-0 px-1 w-100" style="font-size: 0.7rem;"><i class="bi bi-bookmark-plus"></i> Book</a>
                      @elseif($room->status === 'R')
                        @php 
                          $res = $room->reservations()->where('status', 'RSV')->first();
                        @endphp
                        @if($res)
                          <a href="{{ route('fo.reservations.show', $res->id) }}" class="btn btn-outline-info btn-xs py-0 px-1 w-100" style="font-size: 0.7rem;"><i class="bi bi-eye"></i> Active Booking</a>
                        @endif
                      @elseif($room->status === 'O')
                        @php 
                          $res = $room->reservations()->where('status', 'CI')->first();
                        @endphp
                        @if($res)
                          <a href="{{ route('fo.reservations.show', $res->id) }}" class="btn btn-outline-primary btn-xs py-0 px-1 w-100" style="font-size: 0.7rem;"><i class="bi bi-receipt"></i> Folio/Bill</a>
                        @endif
                      @else
                        <span class="text-muted small py-1" style="font-size: 0.7rem;">Not Available</span>
                      @endif
                    </div>
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
