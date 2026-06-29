@extends('layouts.admin')

@section('title', 'Lost & Found Registry')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING DEPT</p>
      <h1 class="h3 mb-1">Lost & Found Registry</h1>
      <p class="text-muted mb-0">Record items found on premises and track guest claims and recovery history.</p>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Record Found Item Form -->
  <div class="col-12 col-lg-4">
    <div class="panel shadow-sm">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-plus-square text-primary"></i><span>Log Found Item</span></h2>
      </div>

      <form action="{{ route('hk.lost-found.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-12">
            <label for="room_id" class="form-label small fw-bold">Location / Room Found</label>
            <select name="room_id" id="room_id" class="form-select form-select-sm" required>
              <option value="">Choose room...</option>
              @foreach($rooms as $room)
                <option value="{{ $room->id }}">Room {{ $room->room_number }} - {{ $room->roomType->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label for="item_description" class="form-label small fw-bold">Item Description</label>
            <textarea name="item_description" id="item_description" rows="2" class="form-control form-control-sm" required placeholder="Describe the item (e.g. Jam Tangan Rolex Emas, Charger HP Hitam)"></textarea>
          </div>

          <div class="col-12">
            <label for="location_found" class="form-label small fw-bold">Specific Spot Found</label>
            <input type="text" name="location_found" id="location_found" class="form-control form-control-sm" placeholder="e.g. Di bawah ranjang, laci meja rias">
          </div>

          <div class="col-12 border-top pt-2">
            <h6 class="small fw-bold text-muted mb-2">Guest Reference (If known)</h6>
            <div class="mb-2">
              <label for="guest_name" class="form-label small">Guest Name</label>
              <input type="text" name="guest_name" id="guest_name" class="form-control form-control-sm" placeholder="e.g. John Doe">
            </div>
            <div>
              <label for="contact_number" class="form-label small">Contact Number</label>
              <input type="text" name="contact_number" id="contact_number" class="form-control form-control-sm" placeholder="e.g. +6289999999">
            </div>
          </div>

          <div class="col-12 mt-2">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-save"></i> Register Found Item</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Lost & Found Database Ledger -->
  <div class="col-12 col-lg-8">
    <div class="panel shadow-sm h-100">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-journal-text text-primary"></i><span>Lost & Found Ledger</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-sm small">
          <thead>
            <tr>
              <th>Date Found</th>
              <th>Room</th>
              <th>Item & Spot</th>
              <th>Guest Contact</th>
              <th>Reporter</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reports as $rep)
              <tr>
                <td>{{ $rep->created_at->format('d/m Y H:i') }}</td>
                <td class="fw-bold">Room {{ $rep->room->room_number }}</td>
                <td>
                  <strong>{{ $rep->item_description }}</strong>
                  <br><span class="text-muted small">Spot: {{ $rep->location_found ?? '-' }}</span>
                </td>
                <td>
                  @if($rep->guest_name)
                    <strong>{{ $rep->guest_name }}</strong>
                    <br><span class="text-muted small">{{ $rep->contact_number ?? '-' }}</span>
                  @else
                    <span class="text-muted italic small">Anonymous</span>
                  @endif
                </td>
                <td>{{ $rep->reportedBy->name }}</td>
                <td>
                  <span class="badge bg-{{ $rep->status === 'claimed' ? 'success' : 'danger' }}">{{ ucfirst($rep->status) }}</span>
                  @if($rep->claim_date)
                    <br><small class="text-muted" style="font-size: 0.7rem;">Claimed: {{ \Carbon\Carbon::parse($rep->claim_date)->format('d/m/Y') }}</small>
                  @endif
                </td>
                <td>
                  @if($rep->status === 'lost')
                    <form action="{{ route('hk.lost-found.claim', $rep->id) }}" method="POST" onsubmit="return confirm('Confirm item returned to guest?')">
                      @csrf
                      <button type="submit" class="btn btn-success btn-xs"><i class="bi bi-person-check"></i> Handover/Claim</button>
                    </form>
                  @else
                    <span class="text-success small"><i class="bi bi-check-lg"></i> Resolved</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">No lost items registered.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
