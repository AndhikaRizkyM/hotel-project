@extends('layouts.admin')

@section('title', 'Maintenance Logs')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-wrench" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING DEPT</p>
      <h1 class="h3 mb-1">Room Maintenance Requests</h1>
      <p class="text-muted mb-0">Manage technical repairs, log maintenance costs, and lock room availability.</p>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Register Maintenance Request Form -->
  <div class="col-12 col-lg-4">
    <div class="panel shadow-sm">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-plus-square text-primary"></i><span>Log Repair Request</span></h2>
      </div>

      <form action="{{ route('hk.maintenance.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-12">
            <label for="room_id" class="form-label small fw-bold">Room Needing Repair</label>
            <select name="room_id" id="room_id" class="form-select form-select-sm" required>
              <option value="">Choose room...</option>
              @foreach($rooms as $room)
                <option value="{{ $room->id }}">Room {{ $room->room_number }} - {{ $room->roomType->name }} (Status: {{ $room->status_text }})</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label for="priority" class="form-label small fw-bold">Priority Level</label>
            <select name="priority" id="priority" class="form-select form-select-sm" required>
              <option value="low">Low (Cosmetic/Minor)</option>
              <option value="medium" selected>Medium (Standard Repair)</option>
              <option value="high">High (Urgent/Lock Room)</option>
            </select>
          </div>

          <div class="col-12">
            <label for="estimated_cost" class="form-label small fw-bold">Estimated Cost (IDR)</label>
            <input type="number" name="estimated_cost" id="estimated_cost" min="0" value="0" class="form-control form-control-sm" required>
          </div>

          <div class="col-12">
            <label for="description" class="form-label small fw-bold">Description of Defect / Issue</label>
            <textarea name="description" id="description" rows="3" class="form-control form-control-sm" required placeholder="Describe what needs repair (e.g. AC tidak dingin, Air wastafel mampet, Lampu redup)"></textarea>
          </div>

          <div class="col-12 mt-2">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-gear-fill"></i> Log Request & Lock Room</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Maintenance Logs Database Table -->
  <div class="col-12 col-lg-8">
    <div class="panel shadow-sm h-100">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-list-columns-reverse text-primary"></i><span>Repair Status logs</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-sm small">
          <thead>
            <tr>
              <th>Date Logged</th>
              <th>Room No</th>
              <th>Defect Details</th>
              <th>Priority</th>
              <th>Est. Cost</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $req)
              <tr>
                <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                <td class="fw-bold">Room {{ $req->room->room_number }}</td>
                <td>
                  <p class="mb-0 fw-semibold text-wrap">{{ $req->description }}</p>
                  <small class="text-muted italic d-block">Reported by: {{ $req->reportedBy->name }}</small>
                </td>
                <td>
                  @php
                    $priColors = ['low' => 'info', 'medium' => 'warning', 'high' => 'danger'];
                  @endphp
                  <span class="badge bg-{{ $priColors[$req->priority] ?? 'secondary' }}">{{ ucfirst($req->priority) }}</span>
                </td>
                <td>Rp{{ number_format($req->estimated_cost, 0, ',', '.') }}</td>
                <td>
                  <span class="badge bg-{{ $req->status === 'completed' ? 'success' : 'danger' }}">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span>
                  @if($req->completion_date)
                    <br><small class="text-muted" style="font-size: 0.7rem;">Fixed: {{ \Carbon\Carbon::parse($req->completion_date)->format('d/m H:i') }}</small>
                  @endif
                </td>
                <td>
                  @if($req->status !== 'completed')
                    <form action="{{ route('hk.maintenance.complete', $req->id) }}" method="POST" onsubmit="return confirm('Confirm repair is completed? Room will return to Dirty status for cleaning.')">
                      @csrf
                      <button type="submit" class="btn btn-success btn-xs"><i class="bi bi-wrench"></i> Complete Fix</button>
                    </form>
                  @else
                    <span class="text-success small"><i class="bi bi-shield-check"></i> Fixed</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">No maintenance repairs logged.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
