@extends('layouts.admin')

@section('title', 'Housekeeping Hub')

@section('content')
@php
  $activeTab = request('tab', 'tasks');
@endphp

<div class="page-heading">
  <div class="page-heading-copy">
    <div class="page-icon" style="border-radius: 12px; background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"><i class="bi bi-house-gear" aria-hidden="true"></i></div>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING DEPT</p>
      <h1 class="h3 mb-1 fw-bold">Housekeeping Hub</h1>
      <p class="text-muted mb-0">Unified workspace for room cleaning checklist, QC inspections, and logging room issues.</p>
    </div>
  </div>
</div>

<!-- Mobile-Friendly Quick Stats Cards -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center btn-tactile border-0 shadow-sm" style="min-height: auto; border-radius: 12px; background: var(--admin-surface);">
      <span class="text-primary small d-block mb-1 fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Active Tasks</span>
      <span class="h4 mb-0 text-primary fw-bold" style="font-weight: 800;">{{ $tasks->whereIn('status', ['pending', 'cleaning'])->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center btn-tactile border-0 shadow-sm" style="min-height: auto; border-radius: 12px; background: var(--admin-surface);">
      <span class="text-info small d-block mb-1 fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Inspect Pending</span>
      <span class="h4 mb-0 text-info fw-bold" style="font-weight: 800;">{{ $pendingTasks->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center btn-tactile border-0 shadow-sm" style="min-height: auto; border-radius: 12px; background: var(--admin-surface);">
      <span class="text-danger small d-block mb-1 fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Maintenance</span>
      <span class="h4 mb-0 text-danger fw-bold" style="font-weight: 800;">{{ $maintenanceRequests->where('status', 'pending')->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center btn-tactile border-0 shadow-sm" style="min-height: auto; border-radius: 12px; background: var(--admin-surface);">
      <span class="text-warning small d-block mb-1 fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Lost Items</span>
      <span class="h4 mb-0 text-warning fw-bold" style="font-weight: 800;">{{ $lostFoundReports->where('status', 'lost')->count() }}</span>
    </div>
  </div>
</div>

<!-- Unified Navigation Tabs (Scrollable on Mobile) -->
<div class="panel mb-4 shadow-sm border-0" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
  <div class="border-bottom border-light-subtle pb-0 mb-3">
    <ul class="nav nav-pills nav-fill flex-nowrap overflow-auto pb-2" id="hkTabs" role="tablist" style="-webkit-overflow-scrolling: touch; white-space: nowrap; gap: 0.5rem;">
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-bold btn-tactile rounded-pill {{ $activeTab === 'tasks' ? 'active' : '' }}" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tab-tasks" type="button" role="tab" aria-controls="tab-tasks" aria-selected="{{ $activeTab === 'tasks' ? 'true' : 'false' }}" style="font-size: 0.82rem;">
          <i class="bi bi-card-checklist me-1"></i> Cleaning
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-bold btn-tactile rounded-pill {{ $activeTab === 'inspections' ? 'active' : '' }}" id="inspections-tab" data-bs-toggle="tab" data-bs-target="#tab-inspections" type="button" role="tab" aria-controls="tab-inspections" aria-selected="{{ $activeTab === 'inspections' ? 'true' : 'false' }}" style="font-size: 0.82rem;">
          <i class="bi bi-clipboard-check me-1"></i> QC Inspections
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-bold btn-tactile rounded-pill {{ $activeTab === 'report' ? 'active' : '' }}" id="report-tab" data-bs-toggle="tab" data-bs-target="#tab-report" type="button" role="tab" aria-controls="tab-report" aria-selected="{{ $activeTab === 'report' ? 'true' : 'false' }}" style="font-size: 0.82rem;">
          <i class="bi bi-exclamation-triangle me-1"></i> Log Issue
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-bold btn-tactile rounded-pill {{ $activeTab === 'logs' ? 'active' : '' }}" id="logs-tab" data-bs-toggle="tab" data-bs-target="#tab-logs" type="button" role="tab" aria-controls="tab-logs" aria-selected="{{ $activeTab === 'logs' ? 'true' : 'false' }}" style="font-size: 0.82rem;">
          <i class="bi bi-clock-history me-1"></i> Reports Log
        </button>
      </li>
    </ul>
  </div>

  <div class="panel-body p-0">
    <div class="tab-content" id="hkTabsContent">
      
      <!-- 1. CLEANING TASKS TAB -->
      <div class="tab-pane fade {{ $activeTab === 'tasks' ? 'show active' : '' }}" id="tab-tasks" role="tabpanel" aria-labelledby="tasks-tab">
        <h4 class="h6 mb-3 fw-bold text-secondary"><i class="bi bi-broom text-primary me-1"></i> Active Room Cleaning Tasks</h4>
        
        <div class="table-responsive">
          <table class="table align-middle table-hover">
            <thead>
              <tr>
                <th style="font-size: 0.72rem; letter-spacing: 0.5px;">Room</th>
                <th style="font-size: 0.72rem; letter-spacing: 0.5px;">Task Type</th>
                <th style="font-size: 0.72rem; letter-spacing: 0.5px;">Status</th>
                <th style="font-size: 0.72rem; letter-spacing: 0.5px;">Attendant</th>
                <th style="font-size: 0.72rem; letter-spacing: 0.5px;" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tasks as $task)
                <tr>
                  <td>
                    <strong>Room {{ $task->room->room_number }}</strong>
                    <div class="small text-muted" style="font-size: 0.72rem;">{{ $task->room->roomType->name }}</div>
                  </td>
                  <td>
                    @php
                      $types = [
                        'cleaning_checkout' => 'Checkout Clean',
                        'cleaning_daily' => 'Daily Touch-up',
                        'inspection' => 'Room Inspection'
                      ];
                    @endphp
                    <span class="badge bg-light text-dark border small" style="font-size: 0.7rem;">{{ $types[$task->task_type] ?? $task->task_type }}</span>
                  </td>
                  <td>
                    @php
                      $colors = [
                        'pending' => 'danger',
                        'cleaning' => 'warning',
                        'ready_for_inspection' => 'info',
                        'completed' => 'success'
                      ];
                      $labels = [
                        'pending' => 'Pending',
                        'cleaning' => 'Cleaning',
                        'ready_for_inspection' => 'Inspect Pending',
                        'completed' => 'Completed'
                      ];
                    @endphp
                    <span class="badge badge-soft-{{ $colors[$task->status] ?? 'secondary' }} room-status-badge">{{ $labels[$task->status] ?? $task->status }}</span>
                  </td>
                  <td>
                    @if($task->assignedTo)
                      <span class="small fw-semibold">{{ $task->assignedTo->name }}</span>
                    @else
                      <span class="text-muted italic small"><i class="bi bi-person-x"></i> Unassigned</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                      @if($task->status === 'pending')
                        <button type="button" class="btn btn-success btn-tactile btn-xs py-1" data-bs-toggle="modal" data-bs-target="#startTaskModal-{{ $task->id }}"><i class="bi bi-play-circle"></i> Start</button>
                      @elseif($task->status === 'cleaning')
                        <form action="{{ route('hk.tasks.complete', $task->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-primary btn-tactile btn-xs py-1"><i class="bi bi-check-circle"></i> Complete</button>
                        </form>
                      @elseif($task->status === 'ready_for_inspection')
                        <button class="btn btn-info btn-tactile btn-xs text-white py-1" onclick="focusInspection({{ $task->id }})"><i class="bi bi-clipboard-check"></i> Quality Check</button>
                      @else
                        <span class="text-success small fw-semibold"><i class="bi bi-check2-all"></i> Verified</span>
                      @endif
                    </div>
                  </td>
                </tr>

                <!-- Modal to assign attendant -->
                @if($task->status === 'pending')
                  <div class="modal fade" id="startTaskModal-{{ $task->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
                        <div class="modal-header border-bottom">
                          <h5 class="modal-title fw-bold">Start Cleaning Room {{ $task->room->room_number }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('hk.tasks.start', $task->id) }}" method="POST">
                          @csrf
                          <div class="modal-body text-start p-4">
                            <label class="form-label small fw-bold">Select Housekeeping Attendant</label>
                            <select name="assigned_to_user_id" class="form-select form-select-sm" required style="border-radius: 8px;">
                              <option value="">Choose staff...</option>
                              @foreach($hkStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="modal-footer border-top">
                            <button type="button" class="btn btn-light btn-sm btn-tactile" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success btn-sm btn-tactile">Start Task</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endif
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-5" style="font-size: 0.85rem;">No active cleaning tasks found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- 2. QUALITY INSPECTION TAB -->
      <div class="tab-pane fade {{ $activeTab === 'inspections' ? 'show active' : '' }}" id="tab-inspections" role="tabpanel" aria-labelledby="inspections-tab">
        <div class="row g-4">
          <div class="col-12 col-lg-5">
            <div class="p-3 rounded border" style="background: var(--admin-surface-soft); border-radius: 12px !important;">
              <h5 class="h6 fw-bold mb-3"><i class="bi bi-clipboard-check text-success me-1"></i> Log Quality Inspection</h5>
              @if($pendingTasks->isEmpty())
                <div class="alert alert-info border-0 small py-2 mb-0" style="border-left: 4px solid var(--admin-primary) !important;">
                  <i class="bi bi-info-circle me-1"></i> No rooms are currently waiting for quality inspection.
                </div>
              @else
                <form action="{{ route('hk.inspections.store') }}" method="POST" id="inspectionForm">
                  @csrf
                  <div class="mb-3">
                    <label for="inspect_task_id" class="form-label small fw-bold">Room Pending Inspection</label>
                    <select name="housekeeping_task_id" id="inspect_task_id" class="form-select form-select-sm" required style="border-radius: 8px;">
                      <option value="">Select Room...</option>
                      @foreach($pendingTasks as $pt)
                        <option value="{{ $pt->id }}">Room {{ $pt->room->room_number }} (Attendant: {{ $pt->assignedTo->name ?? 'Unassigned' }})</option>
                      @endforeach
                    </select>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label small fw-bold d-block">Inspection Result</label>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input text-success" type="radio" name="result" id="inspect-passed" value="passed" checked required>
                      <label class="form-check-label fw-bold text-success small" for="inspect-passed"><i class="bi bi-check-circle"></i> Passed (Release)</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input text-danger" type="radio" name="result" id="inspect-failed" value="failed" required>
                      <label class="form-check-label fw-bold text-danger small" for="inspect-failed"><i class="bi bi-x-circle"></i> Failed (Re-clean)</label>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="inspect_notes" class="form-label small fw-bold">Inspector Notes / Findings</label>
                    <textarea name="notes" id="inspect_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. All clean and tidy..." style="border-radius: 8px;"></textarea>
                  </div>

                  <!-- Integrated Room Issues (Collapsible) -->
                  <div class="border-top pt-3 mt-3">
                    <h6 class="fw-bold mb-2 small text-secondary"><i class="bi bi-link-45deg"></i> Report Room Issues (Optional)</h6>
                    
                    <div class="d-flex flex-column gap-2 mb-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="report_damage" id="qc_report_damage" value="1">
                        <label class="form-check-label small text-body fw-semibold" for="qc_report_damage">
                          <i class="bi bi-tools text-danger"></i> Property Damage
                        </label>
                      </div>
                      
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="report_lost" id="qc_report_lost" value="1">
                        <label class="form-check-label small text-body fw-semibold" for="qc_report_lost">
                          <i class="bi bi-box-seam text-warning"></i> Lost & Found Item
                        </label>
                      </div>
                      
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="report_maintenance" id="qc_report_maintenance" value="1">
                        <label class="form-check-label small text-body fw-semibold" for="qc_report_maintenance">
                          <i class="bi bi-wrench text-primary"></i> Maintenance / Repairs
                        </label>
                      </div>
                    </div>

                    <!-- 1. Damage Fields -->
                    <div id="qc_damage_fields" class="d-none border p-3 rounded mb-3 bg-light-subtle" style="border-radius: 10px !important;">
                      <h6 class="fw-bold text-danger mb-2 small">Damage Details</h6>
                      <div class="mb-2">
                        <label class="form-label small fw-bold">Broken Item Name</label>
                        <input type="text" class="form-control form-control-sm" name="damage_item_name" id="damage_item_name_input" placeholder="e.g. AC Remote, Broken Glass">
                      </div>
                      <div class="mb-2">
                        <label class="form-label small fw-bold">Description of Damage</label>
                        <textarea class="form-control form-control-sm" name="damage_description" id="damage_description_input" rows="2" placeholder="e.g. Cracked screen, missing remote"></textarea>
                      </div>
                      <div class="row g-2">
                        <div class="col-6">
                          <label class="form-label small fw-bold">Estimated Cost (IDR)</label>
                          <input type="number" class="form-control form-control-sm" name="damage_estimated_cost" id="damage_estimated_cost_input" value="0" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                          <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="damage_is_charged_to_folio" value="1" id="damage_is_charged_to_folio_input">
                            <label class="form-check-label small text-muted fw-semibold" for="damage_is_charged_to_folio_input">Charge Folio</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- 2. Lost & Found Fields -->
                    <div id="qc_lost_fields" class="d-none border p-3 rounded mb-3 bg-light-subtle" style="border-radius: 10px !important;">
                      <h6 class="fw-bold text-warning mb-2 small">Lost & Found Details</h6>
                      <div class="mb-2">
                        <label class="form-label small fw-bold">Item Description</label>
                        <textarea class="form-control form-control-sm" name="lost_item_description" id="lost_item_description_input" rows="2" placeholder="e.g. Black leather wallet, iPhone Charger"></textarea>
                      </div>
                      <div class="mb-2">
                        <label class="form-label small fw-bold">Location Found</label>
                        <input type="text" class="form-control form-control-sm" name="lost_location_found" id="lost_location_found_input" placeholder="e.g. Desk drawer, under bed">
                      </div>
                      <div class="row g-2">
                        <div class="col-6">
                          <label class="form-label small fw-bold">Guest Name</label>
                          <input type="text" class="form-control form-control-sm" name="lost_guest_name" id="lost_guest_name_input" placeholder="Guest Name">
                        </div>
                        <div class="col-6">
                          <label class="form-label small fw-bold">Guest Contact</label>
                          <input type="text" class="form-control form-control-sm" name="lost_contact_number" id="lost_contact_number_input" placeholder="Phone / Email">
                        </div>
                      </div>
                    </div>

                    <!-- 3. Maintenance Fields -->
                    <div id="qc_maintenance_fields" class="d-none border p-3 rounded mb-3 bg-light-subtle" style="border-radius: 10px !important;">
                      <h6 class="fw-bold text-primary mb-2 small">Maintenance Details</h6>
                      <div class="mb-2">
                        <label class="form-label small fw-bold">Description of Issue</label>
                        <textarea class="form-control form-control-sm" name="maintenance_description" id="maintenance_description_input" rows="2" placeholder="e.g. Leaking toilet, AC not cooling"></textarea>
                      </div>
                      <div class="row g-2">
                        <div class="col-6">
                          <label class="form-label small fw-bold">Priority</label>
                          <select name="maintenance_priority" id="maintenance_priority_input" class="form-select form-select-sm">
                            <option value="low">Low (Minor)</option>
                            <option value="medium" selected>Medium (Standard)</option>
                            <option value="high">High (Urgent)</option>
                          </select>
                        </div>
                        <div class="col-6">
                          <label class="form-label small fw-bold">Estimated Cost (IDR)</label>
                          <input type="number" class="form-control form-control-sm" name="maintenance_estimated_cost" id="maintenance_estimated_cost_input" value="0" min="0">
                        </div>
                      </div>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary btn-tactile w-100 mt-3 py-2" style="border-radius: 8px;"><i class="bi bi-save"></i> Submit Inspection</button>
                </form>
              @endif
            </div>
          </div>

          <div class="col-12 col-lg-7">
            <h5 class="h6 fw-bold mb-3 text-secondary"><i class="bi bi-clock-history me-1"></i> Recent Inspection Logs</h5>
            <div class="table-responsive">
              <table class="table align-middle table-sm table-hover">
                <thead>
                  <tr>
                    <th style="font-size: 0.72rem; letter-spacing: 0.5px;">Room</th>
                    <th style="font-size: 0.72rem; letter-spacing: 0.5px;">Result</th>
                    <th style="font-size: 0.72rem; letter-spacing: 0.5px;">Notes</th>
                    <th style="font-size: 0.72rem; letter-spacing: 0.5px;" class="text-end">Time</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($inspections->take(10) as $ins)
                    <tr>
                      <td class="fw-bold">Room {{ $ins->room->room_number }}</td>
                      <td>
                        <span class="badge badge-soft-{{ $ins->result === 'passed' ? 'success' : 'danger' }} room-status-badge">{{ ucfirst($ins->result) }}</span>
                      </td>
                      <td><small class="text-body">{{ $ins->notes ?? '-' }}</small></td>
                      <td class="text-end text-muted"><small class="fw-semibold">{{ $ins->created_at->format('d/M H:i') }}</small></td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-muted py-4" style="font-size: 0.85rem;">No inspections logged yet.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. QUICK ISSUES REPORT TAB -->
      <div class="tab-pane fade {{ $activeTab === 'report' ? 'show active' : '' }}" id="tab-report" role="tabpanel" aria-labelledby="report-tab">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8">
            <div class="p-4 rounded border" style="background: var(--admin-surface-soft); border-radius: 12px !important;">
              <h5 class="h6 fw-bold mb-2"><i class="bi bi-exclamation-triangle text-danger me-1"></i> Unified Issue & Incident Report Form</h5>
              <p class="text-muted small mb-4">Select a room and type of issue to file a damage report, lost & found record, or a maintenance repair ticket.</p>
              
              <form action="" method="POST" id="unifiedReportForm">
                @csrf
                <div class="mb-3">
                  <label for="report_room_id" class="form-label small fw-bold">Select Target Room</label>
                  <select name="room_id" id="report_room_id" class="form-select form-select-sm" required style="border-radius: 8px;">
                    <option value="" disabled selected>Select Room...</option>
                    @foreach($rooms as $rm)
                      <option value="{{ $rm->id }}">Room {{ $rm->room_number }} (Status: {{ $rm->status_text }})</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="report_type" class="form-label small fw-bold">Select Report / Issue Type</label>
                  <select id="report_type" class="form-select form-select-sm" required style="border-radius: 8px;">
                    <option value="" disabled selected>Select issue type...</option>
                    <option value="damage">Damage / Broken Room Property</option>
                    <option value="lost_found">Lost & Found Item</option>
                    <option value="maintenance">Maintenance Request (Repairs)</option>
                  </select>
                </div>

                <!-- 3a. Damage Report Section -->
                <div id="section-damage" class="report-section d-none border p-3 rounded mb-3 bg-transparent" style="border-radius: 10px !important;">
                  <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="bi bi-tools me-1"></i> Damage Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Broken Item Name</label>
                    <input type="text" class="form-control form-control-sm" name="item_name" placeholder="e.g. AC Remote, Television Screen" required style="border-radius: 8px;">
                  </div>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Description of Damage</label>
                    <textarea class="form-control form-control-sm" name="description" rows="2" placeholder="e.g. Cracked, missing, water damage" required style="border-radius: 8px;"></textarea>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Est. Repair Cost (Rp)</label>
                      <input type="number" class="form-control form-control-sm" name="estimated_cost" value="0" min="0" required style="border-radius: 8px;">
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Active Guest Booking</label>
                      <select name="reservation_id" class="form-select form-select-sm" style="border-radius: 8px;">
                        <option value="">Internal Hotel Cost (Don't charge guest)</option>
                        @foreach($activeReservations as $res)
                          <option value="{{ $res->id }}">Room {{ $res->room->room_number }} - {{ $res->guest->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 mt-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_charged_to_folio" value="1" id="is_charged_to_folio">
                        <label class="form-check-label small text-muted fw-semibold" for="is_charged_to_folio">Charge the cost to active guest folio</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- 3b. Lost & Found Section -->
                <div id="section-lost_found" class="report-section d-none border p-3 rounded mb-3 bg-transparent" style="border-radius: 10px !important;">
                  <h6 class="fw-bold text-warning border-bottom pb-2 mb-3"><i class="bi bi-box-seam me-1"></i> Lost & Found Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Item Description</label>
                    <textarea class="form-control form-control-sm" name="item_description" rows="2" placeholder="e.g. Leather wallet containing ID, iPhone 13 charger" required style="border-radius: 8px;"></textarea>
                  </div>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Location Found inside Room</label>
                    <input type="text" class="form-control form-control-sm" name="location_found" placeholder="e.g. Under the bedside table, bathroom drawer" required style="border-radius: 8px;">
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Guest Name (If known)</label>
                      <input type="text" class="form-control form-control-sm" name="guest_name" placeholder="Staying Guest Name" style="border-radius: 8px;">
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Contact Number</label>
                      <input type="text" class="form-control form-control-sm" name="contact_number" placeholder="e.g. Phone / Email" style="border-radius: 8px;">
                    </div>
                  </div>
                </div>

                <!-- 3c. Maintenance / Repair Section -->
                <div id="section-maintenance" class="report-section d-none border p-3 rounded mb-3 bg-transparent" style="border-radius: 10px !important;">
                  <h6 class="fw-bold text-primary border-bottom pb-2 mb-3"><i class="bi bi-wrench me-1"></i> Maintenance Request Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Issue / Work Description</label>
                    <textarea class="form-control form-control-sm" name="maintenance_description" rows="2" placeholder="e.g. Toilet leaking water, AC not getting cold, bulb replacement" required style="border-radius: 8px;"></textarea>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Priority Level</label>
                      <select name="priority" class="form-select form-select-sm" required style="border-radius: 8px;">
                        <option value="low">Low (Minor / Routine)</option>
                        <option value="medium" selected>Medium (Standard)</option>
                        <option value="high">High (Urgent / Block Room)</option>
                      </select>
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Est. Repair Cost (Rp)</label>
                      <input type="number" class="form-control form-control-sm" name="maintenance_estimated_cost" value="0" min="0" required style="border-radius: 8px;">
                    </div>
                  </div>
                </div>

                <button type="submit" class="btn btn-danger btn-tactile w-100 mt-2 py-2" id="submitReportBtn" disabled style="border-radius: 8px;"><i class="bi bi-send-fill"></i> Submit Incident Report</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- 4. ACTIVE LOGS TAB -->
      <div class="tab-pane fade {{ $activeTab === 'logs' ? 'show active' : '' }}" id="tab-logs" role="tabpanel" aria-labelledby="logs-tab">
        <div class="mb-4">
          <ul class="nav nav-tabs mb-3" id="subLogsTabs" role="tablist" style="gap: 0.25rem;">
            <li class="nav-item" role="presentation">
              <button class="nav-link active small py-1 px-3 fw-bold btn-tactile" id="logs-damages-tab" data-bs-toggle="tab" data-bs-target="#sub-damages" type="button" role="tab" style="border-radius: 8px 8px 0 0;">Damages</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link small py-1 px-3 fw-bold btn-tactile" id="logs-lost-tab" data-bs-toggle="tab" data-bs-target="#sub-lost" type="button" role="tab" style="border-radius: 8px 8px 0 0;">Lost & Found</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link small py-1 px-3 fw-bold btn-tactile" id="logs-maint-tab" data-bs-toggle="tab" data-bs-target="#sub-maint" type="button" role="tab" style="border-radius: 8px 8px 0 0;">Maintenance</button>
            </li>
          </ul>

          <div class="tab-content p-2 bg-transparent" id="subLogsTabsContent">
            <!-- 4a. Damages Logs -->
            <div class="tab-pane fade show active" id="sub-damages" role="tabpanel">
              <h5 class="h6 fw-bold mb-3 text-secondary"><i class="bi bi-tools me-1 text-danger"></i> Logged Room Damages</h5>
              <div class="table-responsive">
                <table class="table align-middle table-sm table-hover">
                  <thead>
                    <tr>
                      <th style="font-size: 0.72rem;">Room</th>
                      <th style="font-size: 0.72rem;">Item Name</th>
                      <th style="font-size: 0.72rem;">Description</th>
                      <th style="font-size: 0.72rem;">Est. Cost</th>
                      <th style="font-size: 0.72rem;">Guest Charge?</th>
                      <th style="font-size: 0.72rem;" class="text-end">Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($damages as $dmg)
                      <tr>
                        <td><strong>Room {{ $dmg->room->room_number }}</strong></td>
                        <td><span class="badge bg-light text-dark border">{{ $dmg->item_name }}</span></td>
                        <td><small>{{ $dmg->description ?? '-' }}</small></td>
                        <td class="fw-bold">Rp{{ number_format($dmg->estimated_cost, 0, ',', '.') }}</td>
                        <td>
                          @if($dmg->is_charged_to_folio)
                            <span class="badge badge-soft-danger">Charged Folio</span>
                          @else
                            <span class="badge badge-soft-secondary">Hotel Expense</span>
                          @endif
                        </td>
                        <td class="text-end text-muted"><small class="fw-semibold">{{ $dmg->created_at->format('d/M/y H:i') }}</small></td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center text-muted py-4" style="font-size: 0.85rem;">No room damages reported.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- 4b. Lost & Found Logs -->
            <div class="tab-pane fade" id="sub-lost" role="tabpanel">
              <h5 class="h6 fw-bold mb-3 text-secondary"><i class="bi bi-box-seam me-1 text-warning"></i> Lost & Found Records</h5>
              <div class="table-responsive">
                <table class="table align-middle table-sm table-hover">
                  <thead>
                    <tr>
                      <th style="font-size: 0.72rem;">Room</th>
                      <th style="font-size: 0.72rem;">Description</th>
                      <th style="font-size: 0.72rem;">Location Found</th>
                      <th style="font-size: 0.72rem;">Guest Info</th>
                      <th style="font-size: 0.72rem;">Status</th>
                      <th style="font-size: 0.72rem;">Date Found</th>
                      <th style="font-size: 0.72rem;" class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($lostFoundReports as $lf)
                      <tr>
                        <td><strong>Room {{ $lf->room->room_number }}</strong></td>
                        <td>{{ $lf->item_description }}</td>
                        <td><small>{{ $lf->location_found ?? '-' }}</small></td>
                        <td>
                          @if($lf->guest_name)
                            <small class="fw-bold">{{ $lf->guest_name }}</small> <br><span class="text-muted" style="font-size: 0.7rem;">{{ $lf->contact_number ?? '-' }}</span>
                          @else
                            <span class="text-muted small">Anonymous</span>
                          @endif
                        </td>
                        <td>
                          <span class="badge badge-soft-{{ $lf->status === 'lost' ? 'warning' : 'success' }} room-status-badge">{{ ucfirst($lf->status) }}</span>
                        </td>
                        <td><small class="fw-semibold text-muted">{{ $lf->created_at->format('d/M/y') }}</small></td>
                        <td class="text-end">
                          @if($lf->status === 'lost')
                            <form action="{{ route('hk.lost-found.claim', $lf->id) }}" method="POST">
                              @csrf
                              <button class="btn btn-outline-success btn-tactile btn-xs py-1" type="submit"><i class="bi bi-check-circle"></i> Claimed</button>
                            </form>
                          @else
                            <span class="text-success small fw-semibold"><i class="bi bi-check-all"></i> Claimed</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-4" style="font-size: 0.85rem;">No lost & found logs recorded.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- 4c. Maintenance Request Logs -->
            <div class="tab-pane fade" id="sub-maint" role="tabpanel">
              <h5 class="h6 fw-bold mb-3 text-secondary"><i class="bi bi-wrench me-1 text-primary"></i> Maintenance & Repairs List</h5>
              <div class="table-responsive">
                <table class="table align-middle table-sm table-hover">
                  <thead>
                    <tr>
                      <th style="font-size: 0.72rem;">Room</th>
                      <th style="font-size: 0.72rem;">Description</th>
                      <th style="font-size: 0.72rem;">Priority</th>
                      <th style="font-size: 0.72rem;">Est. Cost</th>
                      <th style="font-size: 0.72rem;">Status</th>
                      <th style="font-size: 0.72rem;">Date</th>
                      <th style="font-size: 0.72rem;" class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($maintenanceRequests as $maint)
                      <tr>
                        <td><strong>Room {{ $maint->room->room_number }}</strong></td>
                        <td>{{ $maint->description }}</td>
                        <td>
                          @php
                            $prioColors = ['low' => 'secondary', 'medium' => 'warning', 'high' => 'danger'];
                          @endphp
                          <span class="badge badge-soft-{{ $prioColors[$maint->priority] ?? 'secondary' }} room-status-badge">{{ ucfirst($maint->priority) }}</span>
                        </td>
                        <td class="fw-bold">Rp{{ number_format($maint->estimated_cost, 0, ',', '.') }}</td>
                        <td>
                          <span class="badge badge-soft-{{ $maint->status === 'completed' ? 'success' : 'danger' }} room-status-badge">{{ ucfirst($maint->status) }}</span>
                        </td>
                        <td><small class="text-muted fw-semibold">{{ $maint->created_at->format('d/M/y') }}</small></td>
                        <td class="text-end">
                          @if($maint->status === 'pending')
                            <form action="{{ route('hk.maintenance.complete', $maint->id) }}" method="POST">
                              @csrf
                              <button class="btn btn-outline-success btn-tactile btn-xs py-1" type="submit"><i class="bi bi-check-lg"></i> Complete</button>
                            </form>
                          @else
                            <span class="text-success small fw-semibold"><i class="bi bi-check-all"></i> Repaired</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-4" style="font-size: 0.85rem;">No maintenance requests logged.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  // Dynamically configure reporting inputs depending on issue type selection
  document.getElementById('report_type').addEventListener('change', function() {
    const type = this.value;
    const submitBtn = document.getElementById('submitReportBtn');
    const form = document.getElementById('unifiedReportForm');
    
    // Hide all sections and disable all their inputs to prevent submission issues
    document.querySelectorAll('.report-section').forEach(sec => {
      sec.classList.add('d-none');
      sec.querySelectorAll('input, select, textarea').forEach(el => {
        el.disabled = true;
        el.required = false; // Turn off requirement constraints on hidden fields
      });
    });
    
    if (type === 'damage') {
      form.action = "{{ route('hk.damages.store') }}";
      const sec = document.getElementById('section-damage');
      sec.classList.remove('d-none');
      sec.querySelectorAll('input, select, textarea').forEach(el => {
        el.disabled = false;
        if(el.name === 'item_name' || el.name === 'description' || el.name === 'estimated_cost') {
          el.required = true;
        }
      });
      submitBtn.disabled = false;
    } else if (type === 'lost_found') {
      form.action = "{{ route('hk.lost-found.store') }}";
      const sec = document.getElementById('section-lost_found');
      sec.classList.remove('d-none');
      sec.querySelectorAll('input, select, textarea').forEach(el => {
        el.disabled = false;
        if(el.name === 'item_description' || el.name === 'location_found') {
          el.required = true;
        }
      });
      submitBtn.disabled = false;
    } else if (type === 'maintenance') {
      form.action = "{{ route('hk.maintenance.store') }}";
      const sec = document.getElementById('section-maintenance');
      sec.classList.remove('d-none');
      sec.querySelectorAll('input, select, textarea').forEach(el => {
        el.disabled = false;
        if(el.name === 'maintenance_description' || el.name === 'priority' || el.name === 'maintenance_estimated_cost') {
          if (el.name === 'maintenance_description') el.name = 'description';
          if (el.name === 'maintenance_estimated_cost') el.name = 'estimated_cost';
          el.required = true;
        }
      });
      submitBtn.disabled = false;
    } else {
      form.action = "";
      submitBtn.disabled = true;
    }
  });

  // Switch tab focus programmatically when QC inspection is requested
  function focusInspection(taskId) {
    const select = document.getElementById('inspect_task_id');
    if (select) {
      select.value = taskId;
    }
    
    const inspectTab = document.getElementById('inspections-tab');
    if (inspectTab) {
      bootstrap.Tab.getInstance(inspectTab)?.show() || new bootstrap.Tab(inspectTab).show();
    }
  }

  // Toggles for Integrated QC Inspection Issues Form
  document.addEventListener('DOMContentLoaded', function() {
    const qcReportDamage = document.getElementById('qc_report_damage');
    const qcDamageFields = document.getElementById('qc_damage_fields');
    if (qcReportDamage && qcDamageFields) {
      qcReportDamage.addEventListener('change', function() {
        if (this.checked) {
          qcDamageFields.classList.remove('d-none');
          document.getElementById('damage_item_name_input').required = true;
          document.getElementById('damage_description_input').required = true;
          document.getElementById('damage_estimated_cost_input').required = true;
        } else {
          qcDamageFields.classList.add('d-none');
          document.getElementById('damage_item_name_input').required = false;
          document.getElementById('damage_description_input').required = false;
          document.getElementById('damage_estimated_cost_input').required = false;
        }
      });
    }

    const qcReportLost = document.getElementById('qc_report_lost');
    const qcLostFields = document.getElementById('qc_lost_fields');
    if (qcReportLost && qcLostFields) {
      qcReportLost.addEventListener('change', function() {
        if (this.checked) {
          qcLostFields.classList.remove('d-none');
          document.getElementById('lost_item_description_input').required = true;
          document.getElementById('lost_location_found_input').required = true;
        } else {
          qcLostFields.classList.add('d-none');
          document.getElementById('lost_item_description_input').required = false;
          document.getElementById('lost_location_found_input').required = false;
        }
      });
    }

    const qcReportMaint = document.getElementById('qc_report_maintenance');
    const qcMaintFields = document.getElementById('qc_maintenance_fields');
    if (qcReportMaint && qcMaintFields) {
      qcReportMaint.addEventListener('change', function() {
        if (this.checked) {
          qcMaintFields.classList.remove('d-none');
          document.getElementById('maintenance_description_input').required = true;
          document.getElementById('maintenance_priority_input').required = true;
          document.getElementById('maintenance_estimated_cost_input').required = true;
        } else {
          qcMaintFields.classList.add('d-none');
          document.getElementById('maintenance_description_input').required = false;
          document.getElementById('maintenance_priority_input').required = false;
          document.getElementById('maintenance_estimated_cost_input').required = false;
        }
      });
    }
  });
</script>
@endsection
