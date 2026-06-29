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

@foreach($hkTasks as $task)
  @if($task->status === 'ready_for_inspection')
    <!-- Inspection Modal -->
    <div class="modal fade" id="inspectModal-{{ $task->id }}" tabindex="-1" aria-labelledby="inspectModalLabel-{{ $task->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
          <form action="{{ route('hk.inspections.store') }}" method="POST">
            @csrf
            <input type="hidden" name="housekeeping_task_id" value="{{ $task->id }}">
            <input type="hidden" name="room_id" value="{{ $task->room_id }}">
            <div class="modal-header border-bottom">
              <h5 class="modal-title fw-bold" id="inspectModalLabel-{{ $task->id }}">Inspect Room {{ $task->room->room_number }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start p-4">
              <p class="text-muted small">Review cleanliness and items status for Room <strong>{{ $task->room->room_number }}</strong>.</p>
              
              <div class="mb-3">
                <label class="form-label d-block small fw-bold">Inspection Result</label>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="result" id="resPassed-{{ $task->id }}" value="passed" checked required>
                  <label class="form-check-label text-success fw-semibold small" for="resPassed-{{ $task->id }}">Passed (Clean & Ready)</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="result" id="resFailed-{{ $task->id }}" value="failed" required>
                  <label class="form-check-label text-danger fw-semibold small" for="resFailed-{{ $task->id }}">Failed (Needs Re-clean)</label>
                </div>
              </div>

              <div class="mb-3">
                <label for="inspect_notes-{{ $task->id }}" class="form-label small fw-bold">Notes / Findings</label>
                <textarea class="form-control" name="notes" id="inspect_notes-{{ $task->id }}" rows="2" placeholder="Write cleanliness status or damage remarks..." style="border-radius: 8px;"></textarea>
              </div>

              <!-- Integrated Room Issues (Collapsible) -->
              <div class="border-top pt-3 mt-3">
                <h6 class="fw-bold mb-2 small text-secondary"><i class="bi bi-link-45deg"></i> Report Room Issues (Optional)</h6>

                <div class="d-flex flex-column gap-2 mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="report_damage" id="qc_report_damage-{{ $task->id }}" value="1" onchange="toggleDamageFields({{ $task->id }}, this.checked)">
                    <label class="form-check-label small text-body fw-semibold" for="qc_report_damage-{{ $task->id }}">
                      <i class="bi bi-tools text-danger"></i> Property Damage
                    </label>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="report_lost" id="qc_report_lost-{{ $task->id }}" value="1" onchange="toggleLostFields({{ $task->id }}, this.checked)">
                    <label class="form-check-label small text-body fw-semibold" for="qc_report_lost-{{ $task->id }}">
                      <i class="bi bi-box-seam text-warning"></i> Lost & Found Item
                    </label>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="report_maintenance" id="qc_report_maintenance-{{ $task->id }}" value="1" onchange="toggleMaintenanceFields({{ $task->id }}, this.checked)">
                    <label class="form-check-label small text-body fw-semibold" for="qc_report_maintenance-{{ $task->id }}">
                      <i class="bi bi-wrench text-primary"></i> Maintenance / Repairs
                    </label>
                  </div>
                </div>

                <!-- 1. Damage Fields -->
                <div id="qc_damage_fields-{{ $task->id }}" class="d-none border p-3 rounded mb-3 bg-light-subtle" style="border-radius: 10px !important;">
                  <h6 class="fw-bold text-danger mb-2 small">Damage Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Broken Item Name</label>
                    <input type="text" class="form-control form-control-sm" name="damage_item_name" id="damage_item_name_input-{{ $task->id }}" placeholder="e.g. AC Remote, Broken Glass">
                  </div>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Description of Damage</label>
                    <textarea class="form-control form-control-sm" name="damage_description" id="damage_description_input-{{ $task->id }}" rows="2" placeholder="e.g. Cracked screen, missing remote"></textarea>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Estimated Cost (IDR)</label>
                      <input type="number" class="form-control form-control-sm" name="damage_estimated_cost" id="damage_estimated_cost_input-{{ $task->id }}" value="0" min="0">
                    </div>
                    <div class="col-6 d-flex align-items-end">
                      <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="damage_is_charged_to_folio" value="1" id="damage_is_charged_to_folio_input-{{ $task->id }}">
                        <label class="form-check-label small text-muted fw-semibold" for="damage_is_charged_to_folio_input-{{ $task->id }}">Charge Folio</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- 2. Lost & Found Fields -->
                <div id="qc_lost_fields-{{ $task->id }}" class="d-none border p-3 rounded mb-3 bg-light-subtle" style="border-radius: 10px !important;">
                  <h6 class="fw-bold text-warning mb-2 small">Lost & Found Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Item Description</label>
                    <textarea class="form-control form-control-sm" name="lost_item_description" id="lost_item_description_input-{{ $task->id }}" rows="2" placeholder="e.g. Black leather wallet, iPhone Charger"></textarea>
                  </div>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Location Found</label>
                    <input type="text" class="form-control form-control-sm" name="lost_location_found" id="lost_location_found_input-{{ $task->id }}" placeholder="e.g. Desk drawer, under bed">
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Guest Name</label>
                      <input type="text" class="form-control form-control-sm" name="lost_guest_name" id="lost_guest_name_input-{{ $task->id }}" placeholder="Guest Name">
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Guest Contact</label>
                      <input type="text" class="form-control form-control-sm" name="lost_contact_number" id="lost_contact_number_input-{{ $task->id }}" placeholder="Phone / Email">
                    </div>
                  </div>
                </div>

                <!-- 3. Maintenance Fields -->
                <div id="qc_maintenance_fields-{{ $task->id }}" class="d-none border p-3 rounded mb-3 bg-light-subtle" style="border-radius: 10px !important;">
                  <h6 class="fw-bold text-primary mb-2 small">Maintenance Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Description of Issue</label>
                    <textarea class="form-control form-control-sm" name="maintenance_description" id="maintenance_description_input-{{ $task->id }}" rows="2" placeholder="e.g. Leaking toilet, AC not cooling"></textarea>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Priority</label>
                      <select name="maintenance_priority" id="maintenance_priority_input-{{ $task->id }}" class="form-select form-select-sm">
                        <option value="low">Low (Minor)</option>
                        <option value="medium" selected>Medium (Standard)</option>
                        <option value="high">High (Urgent)</option>
                      </select>
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Estimated Cost (IDR)</label>
                      <input type="number" class="form-control form-control-sm" name="maintenance_estimated_cost" id="maintenance_estimated_cost_input-{{ $task->id }}" value="0" min="0">
                    </div>
                  </div>
                </div>
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
@endforeach

@endsection

@push('scripts')
<script>
    function toggleDamageFields(taskId, isChecked) {
        const fields = document.getElementById(`qc_damage_fields-${taskId}`);
        const itemInput = document.getElementById(`damage_item_name_input-${taskId}`);
        const descInput = document.getElementById(`damage_description_input-${taskId}`);
        const costInput = document.getElementById(`damage_estimated_cost_input-${taskId}`);
        
        if (isChecked) {
            fields.classList.remove('d-none');
            itemInput.required = true;
            descInput.required = true;
            costInput.required = true;
        } else {
            fields.classList.add('d-none');
            itemInput.required = false;
            descInput.required = false;
            costInput.required = false;
        }
    }

    function toggleLostFields(taskId, isChecked) {
        const fields = document.getElementById(`qc_lost_fields-${taskId}`);
        const descInput = document.getElementById(`lost_item_description_input-${taskId}`);
        const locInput = document.getElementById(`lost_location_found_input-${taskId}`);
        
        if (isChecked) {
            fields.classList.remove('d-none');
            descInput.required = true;
            locInput.required = true;
        } else {
            fields.classList.add('d-none');
            descInput.required = false;
            locInput.required = false;
        }
    }

    function toggleMaintenanceFields(taskId, isChecked) {
        const fields = document.getElementById(`qc_maintenance_fields-${taskId}`);
        const descInput = document.getElementById(`maintenance_description_input-${taskId}`);
        const priorityInput = document.getElementById(`maintenance_priority_input-${taskId}`);
        const costInput = document.getElementById(`maintenance_estimated_cost_input-${taskId}`);
        
        if (isChecked) {
            fields.classList.remove('d-none');
            descInput.required = true;
            priorityInput.required = true;
            costInput.required = true;
        } else {
            fields.classList.add('d-none');
            descInput.required = false;
            priorityInput.required = false;
            costInput.required = false;
        }
    }
</script>
@endpush
