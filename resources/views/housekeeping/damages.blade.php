@extends('layouts.admin')

@section('title', 'Damage Reports')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-tools" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING DEPT</p>
      <h1 class="h3 mb-1">Room Damage & Loss Reports</h1>
      <p class="text-muted mb-0">Record broken amenities and post damage charges directly to active guest folios.</p>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Report Damage Form -->
  <div class="col-12 col-lg-5">
    <div class="panel shadow-sm">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-exclamation-triangle text-danger"></i><span>Log New Damage/Loss</span></h2>
      </div>

      <form action="{{ route('hk.damages.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-12">
            <label for="room_id" class="form-label small fw-bold">Select Room</label>
            <select name="room_id" id="room_id" class="form-select form-select-sm" required>
              <option value="">Choose room...</option>
              @foreach($rooms as $room)
                <option value="{{ $room->id }}">Room {{ $room->room_number }} - {{ $room->roomType->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label for="item_name" class="form-label small fw-bold">Damaged/Lost Item Name</label>
            <input type="text" name="item_name" id="item_name" class="form-control form-control-sm" required placeholder="e.g. Remote TV, Cermin Pecah, Handuk Hilang">
          </div>

          <div class="col-12">
            <label for="estimated_cost" class="form-label small fw-bold">Estimated Cost (IDR)</label>
            <input type="number" name="estimated_cost" id="estimated_cost" min="0" class="form-control form-control-sm" required placeholder="e.g. 150000">
          </div>

          <div class="col-12 border-top pt-2">
            <label for="reservation_id" class="form-label small fw-bold">Link to Active Stay (Optional)</label>
            <select name="reservation_id" id="reservation_id" class="form-select form-select-sm">
              <option value="">Choose active guest to charge folio...</option>
              @foreach($activeReservations as $res)
                <option value="{{ $res->id }}">Room {{ $res->room->room_number }} - {{ $res->guest->name }} ({{ $res->reservation_number }})</option>
              @endforeach
            </select>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.75rem;"><i class="bi bi-info-circle"></i> Charge this cost to the guest's active folio billing statement?</p>
            <div class="form-check mt-1">
              <input class="form-check-input" type="checkbox" name="is_charged_to_folio" id="is_charged_to_folio" value="1">
              <label class="form-check-label small fw-bold text-danger" for="is_charged_to_folio">Yes, Charge to Guest Folio</label>
            </div>
          </div>

          <div class="col-12">
            <label for="description" class="form-label small fw-bold">Damage Description / Details</label>
            <textarea name="description" id="description" rows="2" class="form-control form-control-sm" placeholder="Explain the severity or issue..."></textarea>
          </div>

          <div class="col-12 mt-2">
            <button type="submit" class="btn btn-danger btn-sm w-100"><i class="bi bi-save"></i> Submit Damage Report</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Damages Log History -->
  <div class="col-12 col-lg-7">
    <div class="panel shadow-sm h-100">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-clock-history text-primary"></i><span>Recorded Damages Database</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-sm small">
          <thead>
            <tr>
              <th>Date</th>
              <th>Room</th>
              <th>Item & Cost</th>
              <th>Charged Folio?</th>
              <th>Reporter</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($damages as $dmg)
              <tr>
                <td>{{ $dmg->created_at->format('d/m H:i') }}</td>
                <td class="fw-bold">Room {{ $dmg->room->room_number }}</td>
                <td>
                  <strong>{{ $dmg->item_name }}</strong>
                  <br><span class="text-muted">Est: Rp{{ number_format($dmg->estimated_cost, 0, ',', '.') }}</span>
                </td>
                <td>
                  @if($dmg->is_charged_to_folio)
                    <span class="badge bg-danger"><i class="bi bi-file-earmark-check"></i> Folio Charged</span>
                    <br><small class="text-muted italic">{{ $dmg->guest->name ?? '' }}</small>
                  @else
                    <span class="badge bg-light text-dark">No Folio charge</span>
                  @endif
                </td>
                <td>{{ $dmg->reportedBy->name }}</td>
                <td>
                  <span class="badge bg-{{ $dmg->status === 'repaired' ? 'success' : 'warning' }}">{{ ucfirst($dmg->status) }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No damage reports logged.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
