@extends('layouts.admin')

@section('title', 'Room Inspections')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING DEPT</p>
      <h1 class="h3 mb-1">Room Quality Control Inspections</h1>
      <p class="text-muted mb-0">Inspect cleaned rooms before releasing them back to Front Office occupancy availability.</p>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Rooms Pending Inspection Form -->
  <div class="col-12 col-lg-5">
    <div class="panel shadow-sm">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-plus-square text-primary"></i><span>Log Quality Inspection</span></h2>
      </div>

      @if($pendingTasks->isEmpty())
        <div class="alert alert-info small py-3 mb-0">
          <i class="bi bi-info-circle me-1"></i> There are currently no rooms marked as "Ready for Inspection" by housekeeping attendants.
        </div>
      @else
        <form action="{{ route('hk.inspections.store') }}" method="POST">
          @csrf
          <div class="row g-3">
            <div class="col-12">
              <label for="housekeeping_task_id" class="form-label small fw-bold">Select Cleaned Room</label>
              <select name="housekeeping_task_id" id="housekeeping_task_id" class="form-select form-select-sm" required>
                <option value="">Choose room pending inspect...</option>
                @foreach($pendingTasks as $task)
                  <option value="{{ $task->id }}">Room {{ $task->room->room_number }} - {{ $task->room->roomType->name }} (Attendant: {{ $task->assignedTo->name ?? 'Unassigned' }})</option>
                @endforeach
              </select>
            </div>

            <div class="col-12">
              <label class="form-label small fw-bold">Inspection Result</label>
              <div class="d-flex gap-4">
                <div class="form-check">
                  <input class="form-check-input text-success" type="radio" name="result" id="res-passed" value="passed" checked required>
                  <label class="form-check-label fw-bold text-success" for="res-passed"><i class="bi bi-check-circle"></i> Passed (Release Room)</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input text-danger" type="radio" name="result" id="res-failed" value="failed" required>
                  <label class="form-check-label fw-bold text-danger" for="res-failed"><i class="bi bi-x-circle"></i> Failed (Re-clean Room)</label>
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="notes" class="form-label small fw-bold">Inspector Notes / Details</label>
              <textarea name="notes" id="notes" rows="3" class="form-control form-control-sm" placeholder="e.g. Toiletries replaced, pillows fluffed, floor clean, or mention issues if failed..."></textarea>
            </div>

            <div class="col-12 mt-2">
              <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-save"></i> Submit Inspection</button>
            </div>
          </div>
        </form>
      @endif
    </div>
  </div>

  <!-- Quality Control Logs history -->
  <div class="col-12 col-lg-7">
    <div class="panel shadow-sm h-100">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-clock-history text-primary"></i><span>Recent Inspection History Logs</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-sm small">
          <thead>
            <tr>
              <th>Date</th>
              <th>Room No</th>
              <th>Clean Attendant</th>
              <th>Result</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            @forelse($inspections as $ins)
              <tr>
                <td>{{ $ins->created_at->format('d/m/Y H:i') }}</td>
                <td class="fw-bold">Room {{ $ins->room->room_number }}</td>
                <td>{{ $ins->housekeepingTask->assignedTo->name ?? 'Attendant N/A' }}</td>
                <td>
                  <span class="badge bg-{{ $ins->result === 'passed' ? 'success' : 'danger' }}">{{ ucfirst($ins->result) }}</span>
                </td>
                <td>{{ $ins->notes ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No inspection logs recorded.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
