@extends('layouts.admin')

@section('title', 'Breakfast Entitlements')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-egg-fried" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FOOD & BEVERAGE DEPT</p>
      <h1 class="h3 mb-1">Daily Breakfast Entitlements</h1>
      <p class="text-muted mb-0">Review rooms and active guests entitled to breakfast service for the day.</p>
    </div>
  </div>
</div>

<!-- Filters Panel -->
<div class="panel mb-4 shadow-sm">
  <form method="GET" action="{{ route('fb.breakfast') }}" class="row g-2">
    <div class="col-12 col-sm-6 col-md-4">
      <label for="date" class="form-label small fw-bold">Select Date</label>
      <div class="input-group input-group-sm">
        <input type="date" name="date" id="date" value="{{ $date }}" class="form-control">
        <button type="submit" class="btn btn-primary"><i class="bi bi-calendar-check"></i> Load Date</button>
      </div>
    </div>
  </form>
</div>

<!-- Guests Entitlements list -->
<div class="panel shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Room</th>
          <th>Guest Name</th>
          <th>Room Type</th>
          <th>Entitlement Type</th>
          <th>Pax Capacity</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($guests as $res)
          <tr>
            <td><strong>Room {{ $res->room->room_number }}</strong></td>
            <td>
              <strong>{{ $res->guest->name }}</strong>
              <br><small class="text-muted">Res No: {{ $res->reservation_number }}</small>
            </td>
            <td>{{ $res->room->roomType->name }}</td>
            <td>
              @if($res->room->roomType->breakfast_included)
                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Breakfast Included (Complimentary)</span>
              @else
                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle-fill"></i> Breakfast NOT Included</span>
              @endif
            </td>
            <td>
              <strong>{{ $res->room->roomType->capacity }} Pax</strong>
            </td>
            <td>
              <span class="badge bg-success">Checked In</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No active checked-in guests scheduled for breakfast entitlements on this date.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
