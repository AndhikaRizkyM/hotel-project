@extends('layouts.admin')

@section('title', 'Walk-In Booking')

@push('styles')
<style>
  /* ── Guest Mode Toggle ── */
  .guest-mode-toggle {
    display: flex;
    background: var(--bs-tertiary-bg, #f1f5f9);
    border-radius: 0.75rem;
    padding: 4px;
    gap: 4px;
    margin-bottom: 1.25rem;
  }

  .guest-mode-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    border: none;
    border-radius: 0.6rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: transparent;
    color: var(--bs-secondary-color, #6c757d);
  }

  .guest-mode-btn.active {
    background: var(--admin-surface, #fff);
    color: var(--admin-primary, #0ea5e9);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15), 0 1px 3px rgba(0, 0, 0, 0.08);
  }

  .guest-mode-btn:not(.active):hover {
    background: rgba(255,255,255,0.5);
    color: var(--bs-body-color, #333);
  }

  .guest-mode-btn i {
    font-size: 1rem;
  }

  /* ── Guest Search Box ── */
  .guest-search-wrapper {
    position: relative;
    margin-bottom: 1rem;
  }

  .guest-search-input {
    padding-left: 2.5rem !important;
    border-radius: 0.75rem !important;
    border: 2px solid var(--bs-border-color, #dee2e6) !important;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .guest-search-input:focus {
    border-color: #0ea5e9 !important;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1) !important;
  }

  .guest-search-icon {
    position: absolute;
    left: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--bs-secondary-color, #94a3b8);
    font-size: 0.9rem;
    pointer-events: none;
    z-index: 5;
  }

  .guest-search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--bs-body-bg, #fff);
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.75rem;
    max-height: 260px;
    overflow-y: auto;
    z-index: 100;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    display: none;
  }

  .guest-search-results.show {
    display: block;
  }

  .guest-result-item {
    padding: 0.7rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid var(--bs-border-color-translucent, rgba(0,0,0,0.05));
    transition: background 0.15s;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .guest-result-item:last-child {
    border-bottom: none;
  }

  .guest-result-item:hover,
  .guest-result-item.highlighted {
    background: rgba(14, 165, 233, 0.06);
  }

  .guest-result-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.75rem;
    flex-shrink: 0;
  }

  .guest-result-info {
    flex: 1;
    min-width: 0;
  }

  .guest-result-name {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--bs-body-color, #1e293b);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .guest-result-meta {
    font-size: 0.7rem;
    color: var(--bs-secondary-color, #94a3b8);
  }

  .guest-search-empty {
    padding: 1.5rem 1rem;
    text-align: center;
    color: var(--bs-secondary-color, #94a3b8);
    font-size: 0.8rem;
  }

  .guest-search-empty i {
    font-size: 1.5rem;
    display: block;
    margin-bottom: 0.5rem;
    opacity: 0.5;
  }

  /* ── Selected Guest Card ── */
  .selected-guest-card {
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.05), rgba(99, 102, 241, 0.05));
    border: 2px solid rgba(14, 165, 233, 0.2);
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    display: none;
    animation: slideIn 0.3s ease;
  }

  .selected-guest-card.show {
    display: block;
  }

  @keyframes slideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .selected-guest-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
  }

  .selected-guest-header .guest-avatar-lg {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.9rem;
    margin-right: 0.75rem;
  }

  .selected-guest-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem 1rem;
  }

  .selected-guest-detail-item {
    font-size: 0.75rem;
  }

  .selected-guest-detail-item .detail-label {
    color: var(--bs-secondary-color, #94a3b8);
    font-weight: 500;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .selected-guest-detail-item .detail-value {
    color: var(--bs-body-color, #1e293b);
    font-weight: 600;
  }

  .btn-change-guest {
    font-size: 0.7rem;
    padding: 0.25rem 0.65rem;
    border-radius: 2rem;
  }

  /* ── Guest Directory Table ── */
  .guest-table-wrapper {
    max-height: 320px;
    overflow-y: auto;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.75rem;
    margin-top: 0.75rem;
  }

  .guest-table-wrapper::-webkit-scrollbar {
    width: 5px;
  }

  .guest-table-wrapper::-webkit-scrollbar-track {
    background: transparent;
  }

  .guest-table-wrapper::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.15);
    border-radius: 10px;
  }

  .guest-directory-table {
    margin-bottom: 0;
    font-size: 0.78rem;
  }

  .guest-directory-table thead th {
    position: sticky;
    top: 0;
    background: var(--bs-tertiary-bg, #f1f5f9);
    border-bottom: 2px solid var(--bs-border-color, #dee2e6);
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--bs-secondary-color, #6c757d);
    font-weight: 700;
    padding: 0.6rem 0.75rem;
    z-index: 2;
  }

  .guest-directory-table tbody tr {
    transition: background 0.15s;
    cursor: pointer;
  }

  .guest-directory-table tbody tr:hover {
    background: rgba(14, 165, 233, 0.06);
  }

  .guest-directory-table tbody td {
    padding: 0.55rem 0.75rem;
    vertical-align: middle;
  }

  .guest-table-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.6rem;
    flex-shrink: 0;
  }

  .btn-select-guest {
    font-size: 0.68rem;
    padding: 0.2rem 0.6rem;
    border-radius: 2rem;
    white-space: nowrap;
  }

  .guest-table-count {
    font-size: 0.7rem;
    color: var(--bs-secondary-color, #94a3b8);
    margin-top: 0.5rem;
  }

  .guest-table-empty {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--bs-secondary-color, #94a3b8);
  }

  .guest-table-empty i {
    font-size: 1.5rem;
    display: block;
    margin-bottom: 0.5rem;
    opacity: 0.4;
  }

  /* Panel transitions */
  .guest-panel-new,
  .guest-panel-existing {
    transition: opacity 0.25s ease, max-height 0.3s ease;
  }

  .guest-panel-hidden {
    display: none !important;
  }
</style>
@endpush

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
    <a href="{{ route('fo.availability') }}" class="btn btn-light btn-tactile btn-sm"><i class="bi bi-arrow-left"></i> Back to Room Map</a>
  </div>
</div>

<form method="POST" action="{{ route('fo.reservations.store') }}" id="reservationForm">
  @csrf
  <input type="hidden" name="guest_mode" id="guest_mode" value="new">
  <input type="hidden" name="guest_id" id="guest_id" value="">

  <div class="row g-4">
    <!-- Guest Identity Details -->
    <div class="col-12 col-lg-7">
      <div class="panel-premium shadow-sm h-100">
        <div class="panel-header border-bottom pb-2 mb-3">
          <h2 class="h5 mb-0 section-title"><i class="bi bi-person-fill text-primary"></i><span>Guest Identity Details</span></h2>
        </div>

        <!-- Guest Mode Toggle -->
        <div class="guest-mode-toggle">
          <button type="button" class="guest-mode-btn active" data-mode="new" id="btn-mode-new">
            <i class="bi bi-person-plus-fill"></i>
            New Guest
          </button>
          <button type="button" class="guest-mode-btn" data-mode="existing" id="btn-mode-existing">
            <i class="bi bi-people-fill"></i>
            Registered Guest
          </button>
        </div>

        <!-- ═══ Panel: Existing Guest Search + Table ═══ -->
        <div id="panel-existing-guest" class="guest-panel-existing guest-panel-hidden">
          <!-- Selected Guest Card (shown after selection) -->
          <div class="selected-guest-card" id="selectedGuestCard">
            <div class="selected-guest-header">
              <div class="d-flex align-items-center">
                <div class="guest-avatar-lg" id="selectedGuestAvatar">-</div>
                <div>
                  <div class="fw-bold" id="selectedGuestName" style="font-size: 0.95rem;">-</div>
                  <div class="text-muted" style="font-size: 0.75rem;" id="selectedGuestIdNumber">-</div>
                </div>
              </div>
              <button type="button" class="btn btn-outline-danger btn-change-guest" id="btnChangeGuest">
                <i class="bi bi-arrow-repeat"></i> Change
              </button>
            </div>
            <div class="selected-guest-details">
              <div class="selected-guest-detail-item">
                <div class="detail-label">Gender</div>
                <div class="detail-value" id="selectedGuestGender">-</div>
              </div>
              <div class="selected-guest-detail-item">
                <div class="detail-label">Birth Date</div>
                <div class="detail-value" id="selectedGuestBirthDate">-</div>
              </div>
              <div class="selected-guest-detail-item">
                <div class="detail-label">Phone</div>
                <div class="detail-value" id="selectedGuestPhone">-</div>
              </div>
              <div class="selected-guest-detail-item">
                <div class="detail-label">Email</div>
                <div class="detail-value" id="selectedGuestEmail">-</div>
              </div>
              <div class="selected-guest-detail-item">
                <div class="detail-label">Country</div>
                <div class="detail-value" id="selectedGuestCountry">-</div>
              </div>
              <div class="selected-guest-detail-item">
                <div class="detail-label">Address</div>
                <div class="detail-value" id="selectedGuestAddress">-</div>
              </div>
            </div>
          </div>

          <!-- Search + Guest Directory (shown when no guest is selected) -->
          <div id="guestDirectoryPanel">
            <div class="guest-search-wrapper">
              <i class="bi bi-search guest-search-icon"></i>
              <input type="text" class="form-control form-control-sm guest-search-input" id="guestSearchInput"
                placeholder="Search guest name, NIK, or phone number..." autocomplete="off">
            </div>

            <div class="guest-table-wrapper">
              <table class="table guest-directory-table" id="guestDirectoryTable">
                <thead>
                  <tr>
                    <th></th>
                    <th>Guest Name</th>
                    <th>NIK / Passport</th>
                    <th>Phone</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody id="guestTableBody">
                  @forelse($guests as $g)
                  <tr class="guest-row"
                    data-guest-id="{{ $g->id }}"
                    data-guest-name="{{ $g->name }}"
                    data-guest-id-number="{{ $g->id_number }}"
                    data-guest-phone="{{ $g->phone }}"
                    data-guest-email="{{ $g->email }}"
                    data-guest-gender="{{ $g->gender }}"
                    data-guest-birth-date="{{ $g->birth_date ? $g->birth_date->format('Y-m-d') : '' }}"
                    data-guest-country="{{ $g->country }}"
                    data-guest-address="{{ $g->address }}"
                    data-guest-vehicle-no="{{ $g->vehicle_no }}">
                    <td><span class="guest-table-avatar">{{ strtoupper(substr($g->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $g->name)[1] ?? '', 0, 1)) }}</span></td>
                    <td class="fw-semibold">{{ $g->name }}</td>
                    <td class="text-muted">{{ $g->id_number }}</td>
                    <td class="text-muted">{{ $g->phone ?: '-' }}</td>
                    <td class="text-end">
                      <button type="button" class="btn btn-outline-primary btn-select-guest" onclick="selectGuestFromRow(this.closest('tr'))">
                        <i class="bi bi-check-lg"></i> Select
                      </button>
                    </td>
                  </tr>
                  @empty
                  <tr id="guestTableEmpty">
                    <td colspan="5">
                      <div class="guest-table-empty">
                        <i class="bi bi-person-x"></i>
                        No registered guests found.
                      </div>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            <div class="guest-table-count">
              <i class="bi bi-people"></i>
              <span id="guestCount">{{ $guests->count() }}</span> registered guest(s)
              <span id="guestFilterInfo" style="display:none;"> — showing <span id="guestFilterCount">0</span> result(s)</span>
            </div>
          </div>
        </div>

        <!-- ═══ Panel: New Guest Form ═══ -->
        <div id="panel-new-guest" class="guest-panel-new">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label for="name" class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control form-control-sm @error('name') is-invalid @enderror">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="id_number" class="form-label small fw-bold">NIK / Passport No. <span class="text-danger">*</span></label>
              <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" class="form-control form-control-sm @error('id_number') is-invalid @enderror" placeholder="For identification card verification">
              @error('id_number')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-4">
              <label for="birth_date" class="form-label small fw-bold">Birth Date <span class="text-danger">*</span></label>
              <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="form-control form-control-sm @error('birth_date') is-invalid @enderror">
              @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-4">
              <label for="gender" class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
              <select name="gender" id="gender" class="form-select form-select-sm @error('gender') is-invalid @enderror">
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
              <input type="text" name="country" id="country" value="{{ old('country', 'Indonesia') }}" class="form-control form-control-sm @error('country') is-invalid @enderror">
              @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label for="phone" class="form-label small fw-bold">Phone Number <span class="text-danger">*</span></label>
              <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control form-control-sm @error('phone') is-invalid @enderror" placeholder="e.g. +628123456789">
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
    </div>

    <!-- Booking Details -->
    <div class="col-12 col-lg-5">
      <div class="panel-premium shadow-sm h-100 d-flex flex-column justify-content-between">
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
          <div class="mt-4 p-3 rounded border text-body shadow-xs" style="background-color: rgba(0, 0, 0, 0.02); border-color: var(--admin-border) !important;">
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
            <div class="d-flex justify-content-between small mb-2 border-bottom pb-2 text-secondary" style="border-bottom-color: var(--admin-border) !important;">
              <span>Service Fee (5%)</span>
              <span id="calc-service" class="text-body">Rp0</span>
            </div>
            <div class="d-flex justify-content-between fw-bold h6 mb-0 text-body">
              <span>Total Bill</span>
              <span id="calc-total" class="text-success">Rp0</span>
            </div>
          </div>
        </div>

        <div class="pt-4 border-top mt-4 d-flex gap-2" style="border-top-color: var(--admin-border) !important;">
          <button type="submit" class="btn btn-primary btn-tactile btn-sm w-100"><i class="bi bi-check-circle"></i> Save Reservation</button>
          <a href="{{ route('fo.reservations.index') }}" class="btn btn-outline-secondary btn-tactile btn-sm w-100">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>

  // Get today's date
  const today = new Date();

  // Format to YYYY-MM-DD (required by HTML5 date inputs)
  const formattedDate = today.toISOString().split('T')[0];

  // Set the minimum selectable date to today
  document.getElementById('check_in_date').setAttribute('min', formattedDate);
  document.getElementById('check_out_date').setAttribute('min', formattedDate);
  // Global function: select guest from table row
  
  function selectGuestFromRow(row) {
    const data = row.dataset;
    const guest = {
      id: data.guestId,
      name: data.guestName,
      id_number: data.guestIdNumber,
      phone: data.guestPhone,
      email: data.guestEmail,
      gender: data.guestGender,
      birth_date: data.guestBirthDate,
      country: data.guestCountry,
      address: data.guestAddress,
      vehicle_no: data.guestVehicleNo,
    };
    window._selectGuest(guest);
  }

  document.addEventListener('DOMContentLoaded', function() {
    // ═══════════════════════════════════════
    // 1. Guest Mode Toggle Logic
    // ═══════════════════════════════════════
    const guestModeInput = document.getElementById('guest_mode');
    const guestIdInput = document.getElementById('guest_id');
    const btnModeNew = document.getElementById('btn-mode-new');
    const btnModeExisting = document.getElementById('btn-mode-existing');
    const panelNewGuest = document.getElementById('panel-new-guest');
    const panelExistingGuest = document.getElementById('panel-existing-guest');

    function setGuestMode(mode) {
      guestModeInput.value = mode;

      if (mode === 'new') {
        btnModeNew.classList.add('active');
        btnModeExisting.classList.remove('active');
        panelNewGuest.classList.remove('guest-panel-hidden');
        panelExistingGuest.classList.add('guest-panel-hidden');
        guestIdInput.value = '';
        // Re-enable required on new guest fields
        document.querySelectorAll('#panel-new-guest input, #panel-new-guest select, #panel-new-guest textarea').forEach(el => {
          if (el.dataset.wasRequired === 'true') el.setAttribute('required', '');
        });
      } else {
        btnModeExisting.classList.add('active');
        btnModeNew.classList.remove('active');
        panelExistingGuest.classList.remove('guest-panel-hidden');
        panelNewGuest.classList.add('guest-panel-hidden');
        // Remove required from hidden new guest fields (prevent validation block)
        document.querySelectorAll('#panel-new-guest input, #panel-new-guest select, #panel-new-guest textarea').forEach(el => {
          if (el.hasAttribute('required')) {
            el.dataset.wasRequired = 'true';
            el.removeAttribute('required');
          }
        });
      }
    }

    // Mark initial required states
    document.querySelectorAll('#panel-new-guest input[required], #panel-new-guest select[required]').forEach(el => {
      el.dataset.wasRequired = 'true';
    });

    btnModeNew.addEventListener('click', () => setGuestMode('new'));
    btnModeExisting.addEventListener('click', () => setGuestMode('existing'));

    // Set initial mode from old input if available on validation errors
    const oldGuestMode = "{{ old('guest_mode', 'new') }}";
    setGuestMode(oldGuestMode);

    // If old guest ID was selected, let's restore it
    const oldGuestId = "{{ old('guest_id') }}";
    if (oldGuestMode === 'existing' && oldGuestId) {
      // Wait for DOM to render row data
      setTimeout(() => {
        const row = document.querySelector(`#guestTableBody tr[data-guest-id="${oldGuestId}"]`);
        if (row) {
          selectGuestFromRow(row);
        }
      }, 50);
    }

    // ═══════════════════════════════════════
    // 2. Guest Table Search/Filter
    // ═══════════════════════════════════════
    const searchInput = document.getElementById('guestSearchInput');
    const selectedGuestCard = document.getElementById('selectedGuestCard');
    const guestDirectoryPanel = document.getElementById('guestDirectoryPanel');
    const guestRows = document.querySelectorAll('#guestTableBody .guest-row');
    const guestFilterInfo = document.getElementById('guestFilterInfo');
    const guestFilterCount = document.getElementById('guestFilterCount');

    // Real-time table filtering
    searchInput.addEventListener('input', function() {
      const query = this.value.trim().toLowerCase();

      if (query.length === 0) {
        // Show all rows
        guestRows.forEach(row => { row.style.display = ''; });
        guestFilterInfo.style.display = 'none';
        return;
      }

      let visibleCount = 0;
      guestRows.forEach(row => {
        const name = (row.dataset.guestName || '').toLowerCase();
        const idNumber = (row.dataset.guestIdNumber || '').toLowerCase();
        const phone = (row.dataset.guestPhone || '').toLowerCase();

        const matches = name.includes(query) || idNumber.includes(query) || phone.includes(query);
        row.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
      });

      guestFilterInfo.style.display = 'inline';
      guestFilterCount.textContent = visibleCount;
    });

    // ═══════════════════════════════════════
    // 3. Guest Selection Logic
    // ═══════════════════════════════════════
    window._selectGuest = function(guest) {
      // Set hidden input
      guestIdInput.value = guest.id;

      // Populate selected card
      document.getElementById('selectedGuestAvatar').textContent = getInitials(guest.name);
      document.getElementById('selectedGuestName').textContent = guest.name;
      document.getElementById('selectedGuestIdNumber').textContent = 'NIK: ' + guest.id_number;
      document.getElementById('selectedGuestGender').textContent = guest.gender || '-';
      document.getElementById('selectedGuestBirthDate').textContent = guest.birth_date ? formatDate(guest.birth_date) : '-';
      document.getElementById('selectedGuestPhone').textContent = guest.phone || '-';
      document.getElementById('selectedGuestEmail').textContent = guest.email || '-';
      document.getElementById('selectedGuestCountry').textContent = guest.country || '-';
      document.getElementById('selectedGuestAddress').textContent = guest.address || '-';

      // Show card, hide directory
      selectedGuestCard.classList.add('show');
      guestDirectoryPanel.style.display = 'none';
    };

    // Change guest button
    document.getElementById('btnChangeGuest').addEventListener('click', function() {
      guestIdInput.value = '';
      selectedGuestCard.classList.remove('show');
      guestDirectoryPanel.style.display = 'block';
      searchInput.value = '';
      // Reset filter
      guestRows.forEach(row => { row.style.display = ''; });
      guestFilterInfo.style.display = 'none';
      searchInput.focus();
    });

    // ═══════════════════════════════════════
    // 4. Form Submission Validation
    // ═══════════════════════════════════════
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
      if (guestModeInput.value === 'existing' && !guestIdInput.value) {
        e.preventDefault();
        alert('Please select a guest from the directory first.');
        searchInput.focus();
        return false;
      }
    });

    // ═══════════════════════════════════════
    // 5. Room Charge Calculator
    // ═══════════════════════════════════════
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

    // ═══════════════════════════════════════
    // 6. Utility Helpers
    // ═══════════════════════════════════════
    function getInitials(name) {
      return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
    }

    function formatDate(dateStr) {
      if (!dateStr) return '-';
      const d = new Date(dateStr);
      return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }
  });
</script>
@endpush
@endsection
