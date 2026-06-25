@extends('layouts.admin')

@section('title', 'Housekeeping Dashboard')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-broom" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING MANAGEMENT</p>
      <h1 class="h3 mb-1">Housekeeping Workspace</h1>
      <p class="text-muted mb-0">Monitor room cleanliness, log inspections, damage list, and room maintenance status.</p>
    </div>
  </div>
</div>

<!-- Metrics row -->
<section class="row g-3" aria-label="Housekeeping metrics">
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-danger">
      <div class="metric-top">
        <span class="metric-label">Dirty Rooms</span>
        <span class="metric-icon"><i class="bi bi-trash3" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">{{ $roomsCount['dirty'] }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-warning">
      <div class="metric-top">
        <span class="metric-label">Cleaning In-Progress</span>
        <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">{{ $roomsCount['cleaning'] }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-success">
      <div class="metric-top">
        <span class="metric-label">Available Rooms</span>
        <span class="metric-icon"><i class="bi bi-door-open" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">{{ $roomsCount['available'] }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-primary">
      <div class="metric-top">
        <span class="metric-label">Under Maintenance</span>
        <span class="metric-icon"><i class="bi bi-wrench" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value">{{ $roomsCount['maintenance'] }}</div>
    </article>
  </div>
</section>

<div class="row g-3 mt-3">
  <!-- Cleaning tasks -->
  <div class="col-12 col-xl-8">
    <div class="panel">
      <div class="panel-header border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-card-checklist text-warning"></i><span>Active Cleaning & Inspection Tasks</span></h2>
        <span class="badge bg-warning">{{ $hkTasks->count() }} Tasks</span>
      </div>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Room No</th>
              <th>Task Type</th>
              <th>Status</th>
              <th>Assigned To</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($hkTasks as $task)
              <tr>
                <td><strong>Room {{ $task->room->room_number }}</strong></td>
                <td>
                  <span class="badge bg-secondary">{{ str_replace('_', ' ', strtoupper($task->task_type)) }}</span>
                </td>
                <td>
                  <span class="badge bg-{{ $task->room->status_color }}">{{ strtoupper($task->status) }}</span>
                </td>
                <td>{{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}</td>
                <td>
                  @if($task->status === 'pending')
                    <form action="{{ route('hk.tasks.start', $task->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-warning btn-xs" type="submit"><i class="bi bi-play-fill"></i> Start Cleaning</button>
                    </form>
                  @elseif($task->status === 'cleaning')
                    <form action="{{ route('hk.tasks.complete', $task->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-success btn-xs" type="submit"><i class="bi bi-check-lg"></i> Finish Cleaning</button>
                    </form>
                  @elseif($task->status === 'ready_for_inspection')
                    <button class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#inspectModal-{{ $task->id }}"><i class="bi bi-shield-check"></i> Inspect Room</button>
                    
                    <!-- Inspection Modal -->
                    <div class="modal fade" id="inspectModal-{{ $task->id }}" tabindex="-1" aria-labelledby="inspectModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="{{ route('hk.inspections.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="housekeeping_task_id" value="{{ $task->id }}">
                            <input type="hidden" name="room_id" value="{{ $task->room_id }}">
                            <div class="modal-header">
                              <h5 class="modal-title" id="inspectModalLabel">Inspect Room {{ $task->room->room_number }}</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-start">
                              <p>Review cleaniness and items status for Room <strong>{{ $task->room->room_number }}</strong>.</p>
                              
                              <div class="mb-3">
                                <label class="form-label d-block">Inspection Result</label>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="result" id="resPassed-{{ $task->id }}" value="passed" checked>
                                  <label class="form-check-label text-success" for="resPassed-{{ $task->id }}">Passed (Clean & Ready)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="result" id="resFailed-{{ $task->id }}" value="failed">
                                  <label class="form-check-label text-danger" for="resFailed-{{ $task->id }}">Failed (Needs Maintenance / Re-clean)</label>
                                </div>
                              </div>
                              
                              <div class="mb-3">
                                <label class="form-label">Set Room Status To</label>
                                <select class="form-select" name="status_after_inspection" required>
                                  <option value="Available">Available (Ready to Sell)</option>
                                  <option value="Maintenance">Maintenance (Under Repairs)</option>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label">Notes / Findings</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Write cleaniness status or damage remarks..."></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary btn-sm">Submit Inspection</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No active cleaning tasks assigned. All rooms are clean.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- HK Shortcuts & Reports -->
  <div class="col-12 col-xl-4">
    <div class="panel p-3 mb-3">
      <h5 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle text-danger"></i> Report Room Issue</h5>
      
      <!-- Damage report form -->
      <form action="{{ route('hk.damages.store') }}" method="POST" class="mb-4">
        @csrf
        <p class="text-muted small">Report broken property/furniture in a guest room.</p>
        <div class="mb-2">
          <label class="form-label small">Room Number</label>
          <select class="form-select form-select-sm" name="room_id" required>
            <option value="" disabled selected>Select Room</option>
            @foreach(\App\Models\Room::orderBy('room_number')->get() as $rm)
              <option value="{{ $rm->id }}">Room {{ $rm->room_number }} ({{ $rm->status_text }})</option>
            @endforeach
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label small">Item Name</label>
          <input type="text" class="form-control form-control-sm" name="item_name" placeholder="e.g. AC Remote, Shower Glass" required>
        </div>
        <div class="mb-2">
          <label class="form-label small">Description</label>
          <input type="text" class="form-control form-control-sm" name="description" placeholder="e.g. Cracked, missing buttons" required>
        </div>
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label small">Est. Repair Cost</label>
            <input type="number" class="form-control form-control-sm" name="estimated_cost" placeholder="Rp" required>
          </div>
          <div class="col-6 pt-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="charge_to_folio" id="chargeFolio" value="1">
              <label class="form-check-label small" for="chargeFolio">Charge to Tamu</label>
            </div>
          </div>
        </div>
        <button class="btn btn-danger btn-sm w-100 mt-2" type="submit"><i class="bi bi-plus-circle"></i> File Damage Report</button>
      </form>
    </div>

    <!-- Maintenance requests list -->
    <div class="panel p-3">
      <h5 class="fw-bold mb-3"><i class="bi bi-wrench text-primary"></i> Maintenance & Repairs</h5>
      <div style="max-height: 200px; overflow-y: auto;">
        @forelse($maintenanceTasks as $mt)
          <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
            <div>
              <span class="badge bg-dark mb-1">Room {{ $mt->room->room_number }}</span>
              <p class="mb-0 small text-muted">{{ $mt->description }}</p>
            </div>
            <form action="{{ route('hk.maintenance.complete', $mt->id) }}" method="POST">
              @csrf
              <button class="btn btn-outline-success btn-xs" type="submit" title="Mark Repaired"><i class="bi bi-check-lg"></i> Done</button>
            </form>
          </div>
        @empty
          <p class="text-muted small text-center">No active maintenance works.</p>
        @endforelse
      </div>
      <a href="{{ route('hk.maintenance.index') }}" class="btn btn-light btn-sm w-100 mt-2">Log New Maintenance Work</a>
    </div>
  </div>
</div>
@endsection
