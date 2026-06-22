@extends('layouts.admin')

@section('title', 'Walk-In Booking')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-journal-plus" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
      <h1 class="h3 mb-1">Create Walk-In Booking</h1>
      <p class="text-muted mb-0">Check-in walk-in guests or schedule future reservations.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('fo.availability') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Room Map</a>
  </div>
</div>

<form method="POST" action="{{ route('fo.reservations.store') }}">
  @csrf
  <div class="row g-4">
    <!-- Guest Identity Details -->
    <div class="col-12 col-lg-7">
      <div class="panel h-100">
        <div class="panel-header border-bottom pb-2 mb-3">
          <h2 class="h5 mb-0 section-title"><i class="bi bi-person-fill text-primary"></i><span>Guest Identity Details</span></h2>
        </div>

        <div class="row g-3">
          <div class="col-12 col-md-6">
            <label for="name" class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control form-control-sm @error('name') is-invalid @enderror" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12 col-md-6">
            <label for="id_number" class="form-label small fw-bold">NIK / Passport No. <span class="text-danger">*</span></label>
            <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" class="form-control form-control-sm @error('id_number') is-invalid @enderror" required placeholder="For identification card verification">
            @error('id_number')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12 col-md-4">
            <label for="birth_date" class="form-label small fw-bold">Birth Date <span class="text-danger">*</span></label>
            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="form-control form-control-sm @error('birth_date') is-invalid @enderror" required>
            @error('birth_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12 col-md-4">
            <label for="gender" class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
            <select name="gender" id="gender" class="form-select form-select-sm @error('gender') is-invalid @enderror" required>
              <option value="">Select Gender</option>
              <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12 col-md-4">
            <label for="country" class="form-label small fw-bold">Country / Nationality <span class="text-danger">*</span></label>
            <input type="text" name="country" id="country" value="{{ old('country', 'Indonesia') }}" class="form-control form-control-sm @error('country') is-invalid @enderror" required>
            @error('country')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12 col-md-6">
            <label for="phone" class="form-label small fw-bold">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control form-control-sm @error('phone') is-invalid @enderror" required placeholder="e.g. +628123456789">
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12 col-md-6">
            <label for="email" class="form-label small fw-bold">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-sm @error('email') is-invalid @enderror" placeholder="guest@example.com">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12">
            <label for="address" class="form-label small fw-bold">Permanent Address</label>
            <textarea name="address" id="address" class="form-control form-control-sm @error('address') is-invalid @enderror" rows="2" placeholder="Full address details...">{{ old('address') }}</textarea>
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12">
            <label for="vehicle_no" class="form-label small fw-bold">Vehicle License Plate No.</label>
            <input type="text" name="vehicle_no" id="vehicle_no" value="{{ old('vehicle_no') }}" class="form-control form-control-sm @error('vehicle_no') is-invalid @enderror" placeholder="e.g. B 1234 CD (Optional)">
            @error('vehicle_no')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </div>

    <!-- Booking Details -->
    <div class="col-12 col-lg-5">
      <div class="panel h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="panel-header border-bottom pb-2 mb-3">
            <h2 class="h5 mb-0 section-title"><i class="bi bi-door-closed-fill text-primary"></i><span>Room & Dates</span></h2>
          </div>

          <div class="row g-3">
            <div class="col-12">
              <label for="room_id" class="form-label small fw-bold">Select Room <span class="text-danger">*</span></label>
              <select name="room_id" id="room_id" class="form-select form-select-sm @error('room_id') is-invalid @enderror" required>
                <option value="">Select Available Room</option>
                @foreach($rooms as $room)
                  <option value="{{ $room->id }}" data-price="{{ $room->roomType->price_per_night }}" {{ (old('room_id') == $room->id || (isset($preselectedRoom) && $preselectedRoom->id == $room->id)) ? 'selected' : '' }}>
                    Room {{ $room->room_number }} - {{ $room->roomType->name }} (Rp{{ number_format($room->roomType->price_per_night, 0, ',', '.') }}/N)
                  </option>
                @endforeach
              </select>
              @error('room_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="check_in_date" class="form-label small fw-bold">Check-in Date <span class="text-danger">*</span></label>
              <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date', today()->toDateString()) }}" class="form-control form-control-sm @error('check_in_date') is-invalid @enderror" required>
              @error('check_in_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="check_out_date" class="form-label small fw-bold">Check-out Date <span class="text-danger">*</span></label>
              <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date', today()->addDay()->toDateString()) }}" class="form-control form-control-sm @error('check_out_date') is-invalid @enderror" required>
              @error('check_out_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Realtime Calculations -->
          <div class="mt-4 p-3 rounded border text-body shadow-xs" style="background-color: var(--bs-tertiary-bg, rgba(0,0,0,0.02));">
            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-calculator"></i> Estimated Folio Statement</h6>
            <div class="d-flex justify-content-between small mb-1 text-secondary">
              <span>Stay Duration</span>
              <span id="calc-nights" class="fw-bold text-body">1 Night(s)</span>
            </div>
            <div class="d-flex justify-content-between small mb-1 text-secondary">
              <span>Room Charge Subtotal</span>
              <span id="calc-subtotal" class="text-body">Rp0</span>
            </div>
            <div class="d-flex justify-content-between small mb-1 text-secondary">
              <span>Government Tax (10%)</span>
              <span id="calc-tax" class="text-body">Rp0</span>
            </div>
            <div class="d-flex justify-content-between small mb-2 border-bottom pb-2 text-secondary">
              <span>Service Fee (5%)</span>
              <span id="calc-service" class="text-body">Rp0</span>
            </div>
            <div class="d-flex justify-content-between fw-bold h6 mb-0 text-body">
              <span>Total Bill</span>
              <span id="calc-total" class="text-success">Rp0</span>
            </div>
          </div>
        </div>

        <div class="pt-4 border-top mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-check-circle"></i> Save Reservation</button>
          <a href="{{ route('fo.reservations.index') }}" class="btn btn-outline-secondary btn-sm w-100">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const inDateInput = document.getElementById('check_in_date');
    const outDateInput = document.getElementById('check_out_date');

    const nightsEl = document.getElementById('calc-nights');
    const subtotalEl = document.getElementById('calc-subtotal');
    const taxEl = document.getElementById('calc-tax');
    const serviceEl = document.getElementById('calc-service');
    const totalEl = document.getElementById('calc-total');

    function calculateCharges() {
      const selectedOption = roomSelect.options[roomSelect.selectedIndex];
      if (!selectedOption || !selectedOption.value) {
        subtotalEl.textContent = 'Rp0';
        taxEl.textContent = 'Rp0';
        serviceEl.textContent = 'Rp0';
        totalEl.textContent = 'Rp0';
        return;
      }

      const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
      const inDate = new Date(inDateInput.value);
      const outDate = new Date(outDateInput.value);

      if (isNaN(inDate) || isNaN(outDate) || outDate <= inDate) {
        nightsEl.textContent = '0 Nights';
        subtotalEl.textContent = 'Rp0';
        taxEl.textContent = 'Rp0';
        serviceEl.textContent = 'Rp0';
        totalEl.textContent = 'Rp0';
        return;
      }

      const diffTime = Math.abs(outDate - inDate);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;

      const subtotal = price * diffDays;
      const tax = subtotal * 0.10;
      const service = subtotal * 0.05;
      const total = subtotal + tax + service;

      nightsEl.textContent = `${diffDays} Night(s)`;
      subtotalEl.textContent = `Rp${subtotal.toLocaleString('id-ID')}`;
      taxEl.textContent = `Rp${tax.toLocaleString('id-ID')}`;
      serviceEl.textContent = `Rp${service.toLocaleString('id-ID')}`;
      totalEl.textContent = `Rp${total.toLocaleString('id-ID')}`;
    }

    roomSelect.addEventListener('change', calculateCharges);
    inDateInput.addEventListener('change', calculateCharges);
    outDateInput.addEventListener('change', calculateCharges);

    calculateCharges();
  });
</script>
@endpush
@endsection
