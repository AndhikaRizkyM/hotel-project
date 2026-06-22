@extends('layouts.admin')

@section('title', 'Room Availability')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-calendar-range" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
      <h1 class="h3 mb-1">Room Availability Grid</h1>
      <p class="text-muted mb-0">Check live room status, filter by floor/type, and easily make walk-in bookings.</p>
    </div>
  </div>
</div>

<!-- Filters Panel -->
<div class="panel mb-4 shadow-sm">
  <form method="GET" action="{{ route('fo.availability') }}" class="row g-3">
    <div class="col-12 col-md-3">
      <label for="room_type_id" class="form-label small fw-bold">Room Type</label>
      <select name="room_type_id" id="room_type_id" class="form-select form-select-sm">
        <option value="">All Types</option>
        @foreach($roomTypes as $type)
          <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-12 col-md-3">
      <label for="floor" class="form-label small fw-bold">Floor</label>
      <select name="floor" id="floor" class="form-select form-select-sm">
        <option value="">All Floors</option>
        <option value="1" {{ request('floor') == '1' ? 'selected' : '' }}>Floor 1</option>
        <option value="2" {{ request('floor') == '2' ? 'selected' : '' }}>Floor 2</option>
        <option value="3" {{ request('floor') == '3' ? 'selected' : '' }}>Floor 3</option>
      </select>
    </div>
    <div class="col-12 col-md-3">
      <label for="status" class="form-label small fw-bold">Status</label>
      <select name="status" id="status" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        <option value="A" {{ request('status') == 'A' ? 'selected' : '' }}>Available</option>
        <option value="O" {{ request('status') == 'O' ? 'selected' : '' }}>Occupied</option>
        <option value="D" {{ request('status') == 'D' ? 'selected' : '' }}>Dirty</option>
        <option value="C" {{ request('status') == 'C' ? 'selected' : '' }}>Cleaning</option>
        <option value="M" {{ request('status') == 'M' ? 'selected' : '' }}>Maintenance</option>
        <option value="R" {{ request('status') == 'R' ? 'selected' : '' }}>Reserved</option>
      </select>
    </div>
    <div class="col-12 col-md-3 d-flex align-items-end">
      <button type="submit" class="btn btn-primary btn-sm me-2 w-100"><i class="bi bi-funnel"></i> Filter</button>
      <a href="{{ route('fo.availability') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
    </div>
  </form>
</div>

<!-- Grid Map -->
<div class="panel">
  @php
    $floors = $rooms->groupBy('floor')->sortKeysDesc();
  @endphp
  
  @forelse($floors as $floor => $floorRooms)
    <div class="mb-4">
      <h6 class="text-uppercase fw-bold text-muted border-bottom pb-1"><i class="bi bi-layers-half text-secondary"></i> Floor {{ $floor }}</h6>
      <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-6 g-3">
        @foreach($floorRooms as $room)
          <div class="col">
            <div class="card h-100 shadow-xs border-{{ $room->status_color }}" style="border-left: 5px solid !important;">
              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="card-title mb-0 fw-bold">Room {{ $room->room_number }}</h6>
                    <span class="badge bg-{{ $room->status_color }} text-wrap" style="font-size: 0.65rem; padding: 2px 4px;">{{ $room->status_text }}</span>
                  </div>
                  <p class="card-text text-muted mb-1" style="font-size: 0.75rem;">{{ $room->roomType->name }}</p>
                  <p class="card-text mb-2 text-dark font-monospace fw-bold" style="font-size: 0.8rem;">Rp{{ number_format($room->roomType->price_per_night, 0, ',', '.') }}</p>
                </div>
                
                <div class="pt-2 border-top mt-2 d-flex gap-1 justify-content-end">
                  @if($room->status === 'A')
                    <a href="{{ route('fo.reservations.create', ['room_id' => $room->id]) }}" class="btn btn-outline-success btn-xs py-1 px-1 w-100" style="font-size: 0.7rem;"><i class="bi bi-bookmark-plus"></i> Book Room</a>
                  @elseif($room->status === 'R')
                    @php 
                      $res = $room->reservations()->where('status', 'RSV')->first();
                    @endphp
                    @if($res)
                      <a href="{{ route('fo.reservations.show', $res->id) }}" class="btn btn-outline-info btn-xs py-1 px-1 w-100" style="font-size: 0.7rem;"><i class="bi bi-eye"></i> View Booking</a>
                    @endif
                  @elseif($room->status === 'O')
                    @php 
                      $res = $room->reservations()->where('status', 'CI')->first();
                    @endphp
                    @if($res)
                      <a href="{{ route('fo.reservations.show', $res->id) }}" class="btn btn-outline-primary btn-xs py-1 px-1 w-100" style="font-size: 0.7rem;"><i class="bi bi-receipt"></i> Folio Details</a>
                    @endif
                  @else
                    <span class="text-muted small py-1 text-center w-100" style="font-size: 0.7rem;">Not Bookable</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @empty
    <div class="text-center text-muted py-5">
      <i class="bi bi-door-closed h1 d-block mb-3"></i>
      <p>No rooms match the selected filters.</p>
    </div>
  @endforelse
</div>
@endsection
