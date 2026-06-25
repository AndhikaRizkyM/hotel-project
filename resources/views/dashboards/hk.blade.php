@extends('layouts.admin')

@section('title', 'Housekeeping Dashboard')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <div class="page-icon" style="border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="bi bi-broom" aria-hidden="true"></i></div>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING MANAGEMENT</p>
      <h1 class="h3 mb-1 fw-bold">Housekeeping Workspace</h1>
      <p class="text-muted mb-0">Monitor room cleanliness, log inspections, damage list, and room maintenance status.</p>
    </div>
  </div>
</div>

<!-- Metrics row -->
<section class="row g-3" aria-label="Housekeeping metrics">
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-danger panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Dirty Rooms</span>
        <span class="metric-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;"><i class="bi bi-trash3" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $roomsCount['dirty'] }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-warning panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Cleaning In-Progress</span>
        <span class="metric-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $roomsCount['cleaning'] }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-success panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Available Rooms</span>
        <span class="metric-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;"><i class="bi bi-door-open" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $roomsCount['available'] }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-primary panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Under Maintenance</span>
        <span class="metric-icon" style="background: rgba(37, 99, 235, 0.15); color: var(--admin-primary);"><i class="bi bi-wrench" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $roomsCount['maintenance'] }}</div>
    </article>
  </div>
</section>

<div class="row g-3 mt-3">
  <!-- Cleaning tasks -->
  <div class="col-12 col-xl-8">
    <div class="panel border-0 shadow-sm h-100" style="border-radius: 14px; background: var(--admin-surface);">
      <div class="panel-header border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
        <h2 class="h6 mb-0 section-title fw-bold">
          <i class="bi bi-card-checklist" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"></i>
          <span>Active Cleaning & Inspection Tasks</span>
        </h2>
        <span class="badge badge-soft-warning" style="font-size: 0.72rem;">{{ $hkTasks->count() }} Tasks</span>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
          <thead>
            <tr>
              <th style="font-size: 0.72rem;">Room No</th>
              <th style="font-size: 0.72rem;">Task Type</th>
              <th style="font-size: 0.72rem;">Status</th>
              <th style="font-size: 0.72rem;">Assigned To</th>
              <th style="font-size: 0.72rem;" class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($hkTasks as $task)
              <tr>
                <td><strong>Room {{ $task->room->room_number }}</strong></td>
                <td>
                  <span class="badge bg-light text-dark border small" style="font-size: 0.7rem;">{{ str_replace('_', ' ', strtoupper($task->task_type)) }}</span>
                </td>
                <td>
                  <span class="badge badge-soft-{{ $task->room->status_color }} room-status-badge">{{ strtoupper($task->status) }}</span>
                </td>
                <td><small class="fw-semibold">{{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}</small></td>
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-1">
                    @if($task->status === 'pending')
                      <form action="{{ route('hk.tasks.start', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-warning btn-tactile btn-xs" type="submit"><i class="bi bi-play-fill"></i> Start</button>
                      </form>
                    @elseif($task->status === 'cleaning')
                      <form action="{{ route('hk.tasks.complete', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-tactile btn-xs" type="submit"><i class="bi bi-check-lg"></i> Finish</button>
                      </form>
                    @elseif($task->status === 'ready_for_inspection')
                      <button class="btn btn-primary btn-tactile btn-xs" data-bs-toggle="modal" data-bs-target="#inspectModal-{{ $task->id }}"><i class="bi bi-shield-check"></i> Inspect</button>
                      
                      <!-- Inspection Modal -->
                      <div class="modal fade" id="inspectModal-{{ $task->id }}" tabindex="-1" aria-labelledby="inspectModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
                            <form action="{{ route('hk.inspections.store') }}" method="POST">
                              @csrf
                              <input type="hidden" name="housekeeping_task_id" value="{{ $task->id }}">
                              <input type="hidden" name="room_id" value="{{ $task->room_id }}">
                              <div class="modal-header border-bottom">
                                <h5 class="modal-title fw-bold" id="inspectModalLabel">Inspect Room {{ $task->room->room_number }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body text-start p-4">
                                <p class="text-muted small">Review cleaniness and items status for Room <strong>{{ $task->room->room_number }}</strong>.</p>
                                
                                <div class="mb-3">
                                  <label class="form-label d-block small fw-bold">Inspection Result</label>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="result" id="resPassed-{{ $task->id }}" value="passed" checked>
                                    <label class="form-check-label text-success fw-semibold small" for="resPassed-{{ $task->id }}">Passed (Clean & Ready)</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="result" id="resFailed-{{ $task->id }}" value="failed">
                                    <label class="form-check-label text-danger fw-semibold small" for="resFailed-{{ $task->id }}">Failed (Needs Re-clean)</label>
                                  </div>
                                </div>
                                
                                <div class="mb-3">
                                  <label class="form-label small fw-bold">Set Room Status To</label>
                                  <select class="form-select" name="status_after_inspection" required style="border-radius: 8px;">
                                    <option value="Available">Available (Ready to Sell)</option>
                                    <option value="Maintenance">Maintenance (Under Repairs)</option>
                                  </select>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label small fw-bold">Notes / Findings</label>
                                  <textarea class="form-control" name="notes" rows="3" placeholder="Write cleaniness status or damage remarks..." style="border-radius: 8px;"></textarea>
                                </div>
                              </div>
                              <div class="modal-footer border-top">
                                <button type="button" class="btn btn-light btn-sm btn-tactile" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm btn-tactile">Submit Inspection</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-5" style="font-size: 0.85rem;">No active cleaning tasks assigned. All rooms are clean.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- HK Shortcuts & Reports -->
  <div class="col-12 col-xl-4">
    <div class="panel border-0 shadow-sm mb-3" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
      <h5 class="fw-bold mb-3 section-title h6"><i class="bi bi-exclamation-triangle" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"></i><span>Report Room Issue</span></h5>
      
      <!-- Damage report form -->
      <form action="{{ route('hk.damages.store') }}" method="POST">
        @csrf
        <p class="text-muted small mb-3">Report broken property/furniture in a guest room.</p>
        <div class="mb-2">
          <label class="form-label small fw-semibold">Room Number</label>
          <select class="form-select form-select-sm" name="room_id" required style="border-radius: 8px;">
            <option value="" disabled selected>Select Room</option>
            @foreach(\App\Models\Room::orderBy('room_number')->get() as $rm)
              <option value="{{ $rm->id }}">Room {{ $rm->room_number }} ({{ $rm->status_text }})</option>
            @endforeach
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label small fw-semibold">Item Name</label>
          <input type="text" class="form-control form-control-sm" name="item_name" placeholder="e.g. AC Remote, Shower Glass" required style="border-radius: 8px;">
        </div>
        <div class="mb-2">
          <label class="form-label small fw-semibold">Description</label>
          <input type="text" class="form-control form-control-sm" name="description" placeholder="e.g. Cracked, missing buttons" required style="border-radius: 8px;">
        </div>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label small fw-semibold">Est. Repair Cost</label>
            <input type="number" class="form-control form-control-sm" name="estimated_cost" placeholder="Rp" required style="border-radius: 8px;">
          </div>
          <div class="col-6 pt-4 text-end">
            <div class="form-check d-inline-block text-start">
              <input class="form-check-input" type="checkbox" name="charge_to_folio" id="chargeFolio" value="1">
              <label class="form-check-label small text-muted fw-semibold" for="chargeFolio">Charge Guest</label>
            </div>
          </div>
        </div>
        <button class="btn btn-danger btn-sm btn-tactile w-100 py-2 mt-1" type="submit" style="border-radius: 8px;"><i class="bi bi-plus-circle"></i> File Damage Report</button>
      </form>
    </div>

    <!-- Maintenance requests list -->
    <div class="panel border-0 shadow-sm" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
      <h5 class="fw-bold mb-3 section-title h6"><i class="bi bi-wrench" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i><span>Maintenance & Repairs</span></h5>
      <div style="max-height: 200px; overflow-y: auto;">
        @forelse($maintenanceTasks as $mt)
          <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
            <div>
              <span class="badge bg-dark text-white mb-1" style="font-size: 0.68rem; border-radius: 6px;">Room {{ $mt->room->room_number }}</span>
              <p class="mb-0 small text-body fw-semibold">{{ $mt->description }}</p>
            </div>
            <form action="{{ route('hk.maintenance.complete', $mt->id) }}" method="POST">
              @csrf
              <button class="btn btn-outline-success btn-tactile btn-xs py-1" type="submit" title="Mark Repaired"><i class="bi bi-check-lg"></i> Done</button>
            </form>
          </div>
        @empty
          <div class="text-center py-4">
            <p class="text-muted small mb-0">No active maintenance works.</p>
          </div>
        @endforelse
      </div>
      <a href="{{ route('hk.maintenance.index') }}" class="btn btn-light btn-sm btn-tactile w-100 py-2 mt-2" style="border-radius: 8px;">Log New Maintenance Work</a>
    </div>
  </div>
</div>
@endsection
