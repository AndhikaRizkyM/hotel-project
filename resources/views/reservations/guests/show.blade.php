@extends('layouts.admin')

@section('title', 'Guest Profile - ' . $guest->name)

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-person-fill" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
      <h1 class="h3 mb-1">Profile: {{ $guest->name }}</h1>
      <p class="text-muted mb-0">Update identity information and review historical stays.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('fo.guests.index') }}" class="btn btn-light btn-tactile btn-sm"><i class="bi bi-arrow-left"></i> Back to Directory</a>
  </div>
</div>

<div class="row g-4">
  <!-- Profile Edit Form -->
  <div class="col-12 col-lg-5">
    <div class="panel-premium shadow-sm">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-pencil-square text-primary"></i><span>Modify Identity Details</span></h2>
      </div>

      <form method="POST" action="{{ route('fo.guests.update', $guest->id) }}">
        @csrf
        @method('PUT')
        
        <div class="row g-3">
          <div class="col-12">
            <label for="name" class="form-label small fw-bold">Full Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $guest->name) }}" class="form-control form-control-sm" required>
          </div>

          <div class="col-12">
            <label class="form-label small fw-bold text-muted">NIK / Passport (Read Only)</label>
            <input type="text" class="form-control form-control-sm bg-light" value="{{ $guest->id_number }}" readonly>
          </div>

          <div class="col-12 col-md-6">
            <label for="birth_date" class="form-label small fw-bold">Birth Date</label>
            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $guest->birth_date) }}" class="form-control form-control-sm" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="gender" class="form-label small fw-bold">Gender</label>
            <select name="gender" id="gender" class="form-select form-select-sm" required>
              <option value="Male" {{ old('gender', $guest->gender) === 'Male' ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ old('gender', $guest->gender) === 'Female' ? 'selected' : '' }}>Female</option>
            </select>
          </div>

          <div class="col-12 col-md-6">
            <label for="phone" class="form-label small fw-bold">Phone Number</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $guest->phone) }}" class="form-control form-control-sm" required>
          </div>

          <div class="col-12 col-md-6">
            <label for="email" class="form-label small fw-bold">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $guest->email) }}" class="form-control form-control-sm">
          </div>

          <div class="col-12">
            <label for="country" class="form-label small fw-bold">Country / Nationality</label>
            <input type="text" name="country" id="country" value="{{ old('country', $guest->country) }}" class="form-control form-control-sm" required>
          </div>

          <div class="col-12">
            <label for="address" class="form-label small fw-bold">Permanent Address</label>
            <textarea name="address" id="address" rows="2" class="form-control form-control-sm">{{ old('address', $guest->address) }}</textarea>
          </div>

          <div class="col-12">
            <label for="vehicle_no" class="form-label small fw-bold">Vehicle Plate No.</label>
            <input type="text" name="vehicle_no" id="vehicle_no" value="{{ old('vehicle_no', $guest->vehicle_no) }}" class="form-control form-control-sm">
          </div>

          <div class="col-12 mt-3 pt-2 border-top" style="border-top-color: var(--admin-border) !important;">
            <button type="submit" class="btn btn-primary btn-tactile btn-sm w-100"><i class="bi bi-save"></i> Save Profile Modifications</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Booking History logs -->
  <div class="col-12 col-lg-7">
    <div class="panel-premium shadow-sm h-100">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-clock-history text-primary"></i><span>Historical Room Reservations</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle table-sm small mb-0">
          <thead>
            <tr>
              <th>Res No.</th>
              <th>Room</th>
              <th>Period</th>
              <th>Total Bill</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($guest->reservations as $res)
              <tr>
                <td class="fw-bold">#{{ $res->reservation_number }}</td>
                <td><span class="badge badge-soft-secondary">Room {{ $res->room->room_number }}</span></td>
                <td>
                  <span class="d-block text-muted">{{ $res->check_in_date }}</span>
                  <span class="d-block text-muted">to {{ $res->check_out_date }}</span>
                </td>
                <td class="fw-semibold">Rp{{ number_format($res->total_charge, 0, ',', '.') }}</td>
                <td>
                  @php
                    $colors = ['RSV' => 'badge-soft-info', 'CI' => 'badge-soft-success', 'CO' => 'badge-soft-secondary', 'CAN' => 'badge-soft-danger', 'NS' => 'badge-soft-warning'];
                  @endphp
                  <span class="badge {{ $colors[$res->status] ?? 'badge-soft-secondary' }}">{{ $res->status }}</span>
                </td>
                <td>
                  <a href="{{ route('fo.reservations.show', $res->id) }}" class="btn btn-light btn-tactile btn-xs"><i class="bi bi-receipt"></i> Bill Ledger</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No reservations logged under this guest.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
