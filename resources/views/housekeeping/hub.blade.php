@extends('layouts.admin')

@section('title', 'Housekeeping Hub')

@section('content')
@php
  $activeTab = request('tab', 'tasks');
@endphp

<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-house-gear" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING DEPT</p>
      <h1 class="h3 mb-1">Housekeeping Hub</h1>
      <p class="text-muted mb-0">Unified workspace for room cleaning checklist, QC inspections, and logging room issues.</p>
    </div>
  </div>
</div>

<!-- Mobile-Friendly Quick Stats Cards -->
<div class="row g-2 mb-4">
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center" style="min-height: auto;">
      <span class="text-primary small d-block mb-1 fw-bold">Active Tasks</span>
      <span class="h4 mb-0 text-primary fw-bold">{{ $tasks->whereIn('status', ['pending', 'cleaning'])->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center" style="min-height: auto;">
      <span class="text-info small d-block mb-1 fw-bold">Inspect Pending</span>
      <span class="h4 mb-0 text-info fw-bold">{{ $pendingTasks->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center" style="min-height: auto;">
      <span class="text-danger small d-block mb-1 fw-bold">Maintenance</span>
      <span class="h4 mb-0 text-danger fw-bold">{{ $maintenanceRequests->where('status', 'pending')->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="mini-card p-3 text-center" style="min-height: auto;">
      <span class="text-warning small d-block mb-1 fw-bold">Lost Items</span>
      <span class="h4 mb-0 text-warning fw-bold">{{ $lostFoundReports->where('status', 'lost')->count() }}</span>
    </div>
  </div>
</div>

<!-- Unified Navigation Tabs (Scrollable on Mobile) -->
<div class="panel mb-4 shadow-sm">
  <div class="border-bottom border-secondary-subtle pb-0 mb-3">
    <ul class="nav nav-pills nav-fill flex-nowrap overflow-auto pb-2" id="hkTabs" role="tablist" style="-webkit-overflow-scrolling: touch; white-space: nowrap;">
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-semibold {{ $activeTab === 'tasks' ? 'active' : '' }}" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tab-tasks" type="button" role="tab" aria-controls="tab-tasks" aria-selected="{{ $activeTab === 'tasks' ? 'true' : 'false' }}">
          <i class="bi bi-card-checklist me-1"></i> Cleaning
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-semibold {{ $activeTab === 'inspections' ? 'active' : '' }}" id="inspections-tab" data-bs-toggle="tab" data-bs-target="#tab-inspections" type="button" role="tab" aria-controls="tab-inspections" aria-selected="{{ $activeTab === 'inspections' ? 'true' : 'false' }}">
          <i class="bi bi-clipboard-check me-1"></i> QC Inspections
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-semibold {{ $activeTab === 'report' ? 'active' : '' }}" id="report-tab" data-bs-toggle="tab" data-bs-target="#tab-report" type="button" role="tab" aria-controls="tab-report" aria-selected="{{ $activeTab === 'report' ? 'true' : 'false' }}">
          <i class="bi bi-exclamation-triangle me-1"></i> Log Issue
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link py-2 px-3 fw-semibold {{ $activeTab === 'logs' ? 'active' : '' }}" id="logs-tab" data-bs-toggle="tab" data-bs-target="#tab-logs" type="button" role="tab" aria-controls="tab-logs" aria-selected="{{ $activeTab === 'logs' ? 'true' : 'false' }}">
          <i class="bi bi-clock-history me-1"></i> Reports Log
        </button>
      </li>
    </ul>
  </div>

  <div class="panel-body">
    <div class="tab-content" id="hkTabsContent">
      
      <!-- 1. CLEANING TASKS TAB -->
      <div class="tab-pane fade {{ $activeTab === 'tasks' ? 'show active' : '' }}" id="tab-tasks" role="tabpanel" aria-labelledby="tasks-tab">
        <h4 class="h6 mb-3 fw-bold"><i class="bi bi-broom text-primary"></i> Active Room Cleaning Tasks</h4>
        
        <div class="table-responsive">
          <table class="table align-middle table-hover">
            <thead>
              <tr class="table-light">
                <th>Room</th>
                <th>Task Type</th>
                <th>Status</th>
                <th>Attendant</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tasks as $task)
                <tr>
                  <td>
                    <strong>Room {{ $task->room->room_number }}</strong>
                    <div class="small text-muted">{{ $task->room->roomType->name }}</div>
                  </td>
                  <td>
                    @php
                      $types = [
                        'cleaning_checkout' => 'Checkout Clean',
                        'cleaning_daily' => 'Daily Touch-up',
                        'inspection' => 'Room Inspection'
                      ];
                    @endphp
                    <span class="small">{{ $types[$task->task_type] ?? $task->task_type }}</span>
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
                    <span class="badge badge-soft-{{ $colors[$task->status] ?? 'secondary' }}">{{ $labels[$task->status] ?? $task->status }}</span>
                  </td>
                  <td>
                    @if($task->assignedTo)
                      <span class="small">{{ $task->assignedTo->name }}</span>
                    @else
                      <span class="text-muted italic small"><i class="bi bi-person-x"></i> Unassigned</span>
                    @endif
                  </td>
                  <td>
                    @if($task->status === 'pending')
                      <button type="button" class="btn btn-success btn-xs py-1" data-bs-toggle="modal" data-bs-target="#startTaskModal-{{ $task->id }}"><i class="bi bi-play-circle"></i> Start</button>
                    @elseif($task->status === 'cleaning')
                      <form action="{{ route('hk.tasks.complete', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-xs py-1"><i class="bi bi-check-circle"></i> Complete</button>
                      </form>
                    @elseif($task->status === 'ready_for_inspection')
                      <button class="btn btn-info btn-xs text-white py-1" onclick="focusInspection({{ $task->id }})"><i class="bi bi-clipboard-check"></i> Quality Check</button>
                    @else
                      <span class="text-success small"><i class="bi bi-check2-all"></i> Verified</span>
                    @endif
                  </td>
                </tr>

                <!-- Modal to assign attendant -->
                @if($task->status === 'pending')
                  <div class="modal fade" id="startTaskModal-{{ $task->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title fw-bold">Start Cleaning Room {{ $task->room->room_number }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('hk.tasks.start', $task->id) }}" method="POST">
                          @csrf
                          <div class="modal-body text-start">
                            <label class="form-label small fw-bold">Select Housekeeping Attendant</label>
                            <select name="assigned_to_user_id" class="form-select form-select-sm" required>
                              <option value="">Choose staff...</option>
                              @foreach($hkStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success btn-sm">Start Task</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endif
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No active cleaning tasks found.</td>
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
            <div class="p-3 panel-soft rounded">
              <h5 class="h6 fw-bold mb-3"><i class="bi bi-clipboard-check text-success"></i> Log Quality Inspection</h5>
              @if($pendingTasks->isEmpty())
                <div class="alert alert-info small py-2 mb-0">
                  <i class="bi bi-info-circle me-1"></i> No rooms are currently waiting for quality inspection.
                </div>
              @else
                <form action="{{ route('hk.inspections.store') }}" method="POST" id="inspectionForm">
                  @csrf
                  <div class="mb-3">
                    <label for="inspect_task_id" class="form-label small fw-bold">Room Pending Inspection</label>
                    <select name="housekeeping_task_id" id="inspect_task_id" class="form-select form-select-sm" required>
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
                    <textarea name="notes" id="inspect_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. All clean and tidy..."></textarea>
                  </div>

                  <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-save"></i> Submit Inspection</button>
                </form>
              @endif
            </div>
          </div>

          <div class="col-12 col-lg-7">
            <h5 class="h6 fw-bold mb-3"><i class="bi bi-clock-history"></i> Recent Inspection Logs</h5>
            <div class="table-responsive">
              <table class="table align-middle table-sm small">
                <thead>
                  <tr class="table-light">
                    <th>Room</th>
                    <th>Result</th>
                    <th>Notes</th>
                    <th>Time</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($inspections->take(10) as $ins)
                    <tr>
                      <td class="fw-bold">Room {{ $ins->room->room_number }}</td>
                      <td>
                        <span class="badge badge-soft-{{ $ins->result === 'passed' ? 'success' : 'danger' }}">{{ ucfirst($ins->result) }}</span>
                      </td>
                      <td>{{ $ins->notes ?? '-' }}</td>
                      <td>{{ $ins->created_at->format('d/M H:i') }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-muted py-3">No inspections logged yet.</td>
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
            <div class="p-3 panel-soft rounded">
              <h5 class="h6 fw-bold mb-3"><i class="bi bi-exclamation-triangle text-danger"></i> Unified Issue & Incident Report Form</h5>
              <p class="text-muted small">Select a room and type of issue to file a damage report, lost & found record, or a maintenance repair ticket.</p>
              
              <form action="" method="POST" id="unifiedReportForm">
                @csrf
                <div class="mb-3">
                  <label for="report_room_id" class="form-label small fw-bold">Select Target Room</label>
                  <select name="room_id" id="report_room_id" class="form-select form-select-sm" required>
                    <option value="" disabled selected>Select Room...</option>
                    @foreach($rooms as $rm)
                      <option value="{{ $rm->id }}">Room {{ $rm->room_number }} (Status: {{ $rm->status_text }})</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="report_type" class="form-label small fw-bold">Select Report / Issue Type</label>
                  <select id="report_type" class="form-select form-select-sm" required>
                    <option value="" disabled selected>Select issue type...</option>
                    <option value="damage">Damage / Broken Room Property</option>
                    <option value="lost_found">Lost & Found Item</option>
                    <option value="maintenance">Maintenance Request (Repairs)</option>
                  </select>
                </div>

                <!-- 3a. Damage Report Section -->
                <div id="section-damage" class="report-section d-none border p-3 rounded mb-3 bg-transparent">
                  <h6 class="fw-bold text-danger border-bottom pb-1 mb-3"><i class="bi bi-tools"></i> Damage Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Broken Item Name</label>
                    <input type="text" class="form-control form-control-sm" name="item_name" placeholder="e.g. AC Remote, Television Screen" required>
                  </div>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Description of Damage</label>
                    <textarea class="form-control form-control-sm" name="description" rows="2" placeholder="e.g. Cracked, missing, water damage" required></textarea>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Est. Repair Cost (Rp)</label>
                      <input type="number" class="form-control form-control-sm" name="estimated_cost" value="0" min="0" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Active Guest Booking</label>
                      <select name="reservation_id" class="form-select form-select-sm">
                        <option value="">Internal Hotel Cost (Don't charge guest)</option>
                        @foreach($activeReservations as $res)
                          <option value="{{ $res->id }}">Room {{ $res->room->room_number }} - {{ $res->guest->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 mt-2">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_charged_to_folio" value="1" id="is_charged_to_folio">
                        <label class="form-check-label small text-muted" for="is_charged_to_folio">Charge the cost to active guest folio</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- 3b. Lost & Found Section -->
                <div id="section-lost_found" class="report-section d-none border p-3 rounded mb-3 bg-transparent">
                  <h6 class="fw-bold text-warning border-bottom pb-1 mb-3"><i class="bi bi-box-seam"></i> Lost & Found Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Item Description</label>
                    <textarea class="form-control form-control-sm" name="item_description" rows="2" placeholder="e.g. Leather wallet containing ID, iPhone 13 charger" required></textarea>
                  </div>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Location Found inside Room</label>
                    <input type="text" class="form-control form-control-sm" name="location_found" placeholder="e.g. Under the bedside table, bathroom drawer" required>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Guest Name (If known)</label>
                      <input type="text" class="form-control form-control-sm" name="guest_name" placeholder="Tamu Menginap">
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Contact Number</label>
                      <input type="text" class="form-control form-control-sm" name="contact_number" placeholder="e.g. HP / Email">
                    </div>
                  </div>
                </div>

                <!-- 3c. Maintenance / Repair Section -->
                <div id="section-maintenance" class="report-section d-none border p-3 rounded mb-3 bg-transparent">
                  <h6 class="fw-bold text-primary border-bottom pb-1 mb-3"><i class="bi bi-wrench"></i> Maintenance Request Details</h6>
                  <div class="mb-2">
                    <label class="form-label small fw-bold">Issue / Work Description</label>
                    <textarea class="form-control form-control-sm" name="maintenance_description" rows="2" placeholder="e.g. Toilet leaking water, AC not getting cold, bulb replacement" required></textarea>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label small fw-bold">Priority Level</label>
                      <select name="priority" class="form-select form-select-sm" required>
                        <option value="low">Low (Minor / Routine)</option>
                        <option value="medium" selected>Medium (Standard)</option>
                        <option value="high">High (Urgent / Block Room)</option>
                      </select>
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-bold">Est. Repair Cost (Rp)</label>
                      <input type="number" class="form-control form-control-sm" name="maintenance_estimated_cost" value="0" min="0" required>
                    </div>
                  </div>
                </div>

                <button type="submit" class="btn btn-danger w-100" id="submitReportBtn" disabled><i class="bi bi-send-fill"></i> Submit Incident Report</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- 4. ACTIVE LOGS TAB -->
      <div class="tab-pane fade {{ $activeTab === 'logs' ? 'show active' : '' }}" id="tab-logs" role="tabpanel" aria-labelledby="logs-tab">
        <div class="mb-4">
          <ul class="nav nav-tabs" id="subLogsTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active small py-1 px-3 fw-semibold" id="logs-damages-tab" data-bs-toggle="tab" data-bs-target="#sub-damages" type="button" role="tab">Room Damages</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link small py-1 px-3 fw-semibold" id="logs-lost-tab" data-bs-toggle="tab" data-bs-target="#sub-lost" type="button" role="tab">Lost & Found</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link small py-1 px-3 fw-semibold" id="logs-maint-tab" data-bs-toggle="tab" data-bs-target="#sub-maint" type="button" role="tab">Maintenance & Repairs</button>
            </li>
          </ul>

          <div class="tab-content border-start border-end border-bottom p-3 bg-transparent" id="subLogsTabsContent">
            <!-- 4a. Damages Logs -->
            <div class="tab-pane fade show active" id="sub-damages" role="tabpanel">
              <h5 class="h6 fw-bold mb-2">Logged Room Damages</h5>
              <div class="table-responsive">
                <table class="table align-middle table-sm small">
                  <thead>
                    <tr class="table-light">
                      <th>Room</th>
                      <th>Item Name</th>
                      <th>Description</th>
                      <th>Est. Cost</th>
                      <th>Guest Charge?</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($damages as $dmg)
                      <tr>
                        <td><strong>Room {{ $dmg->room->room_number }}</strong></td>
                        <td>{{ $dmg->item_name }}</td>
                        <td>{{ $dmg->description ?? '-' }}</td>
                        <td>Rp{{ number_format($dmg->estimated_cost, 0, ',', '.') }}</td>
                        <td>
                          @if($dmg->is_charged_to_folio)
                            <span class="badge badge-soft-danger">Folio Charged</span>
                          @else
                            <span class="badge badge-soft-secondary">Hotel Cost</span>
                          @endif
                        </td>
                        <td>{{ $dmg->created_at->format('d/M/y H:i') }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center text-muted py-3">No room damages reported.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- 4b. Lost & Found Logs -->
            <div class="tab-pane fade" id="sub-lost" role="tabpanel">
              <h5 class="h6 fw-bold mb-2">Lost & Found Records</h5>
              <div class="table-responsive">
                <table class="table align-middle table-sm small">
                  <thead>
                    <tr class="table-light">
                      <th>Room</th>
                      <th>Description</th>
                      <th>Location Found</th>
                      <th>Guest Info</th>
                      <th>Status</th>
                      <th>Date Found</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($lostFoundReports as $lf)
                      <tr>
                        <td><strong>Room {{ $lf->room->room_number }}</strong></td>
                        <td>{{ $lf->item_description }}</td>
                        <td>{{ $lf->location_found ?? '-' }}</td>
                        <td>
                          @if($lf->guest_name)
                            {{ $lf->guest_name }} <br><span class="text-muted text-xs">{{ $lf->contact_number ?? '-' }}</span>
                          @else
                            <span class="text-muted small">Anonymous</span>
                          @endif
                        </td>
                        <td>
                          <span class="badge badge-soft-{{ $lf->status === 'lost' ? 'warning' : 'success' }}">{{ ucfirst($lf->status) }}</span>
                        </td>
                        <td>{{ $lf->created_at->format('d/M/y') }}</td>
                        <td>
                          @if($lf->status === 'lost')
                            <form action="{{ route('hk.lost-found.claim', $lf->id) }}" method="POST">
                              @csrf
                              <button class="btn btn-outline-success btn-xs" type="submit"><i class="bi bi-check-circle"></i> Claimed</button>
                            </form>
                          @else
                            <span class="text-success small"><i class="bi bi-check-all"></i> Returned</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-3">No lost & found logs recorded.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- 4c. Maintenance Request Logs -->
            <div class="tab-pane fade" id="sub-maint" role="tabpanel">
              <h5 class="h6 fw-bold mb-2">Maintenance & Repairs List</h5>
              <div class="table-responsive">
                <table class="table align-middle table-sm small">
                  <thead>
                    <tr class="table-light">
                      <th>Room</th>
                      <th>Description</th>
                      <th>Priority</th>
                      <th>Est. Cost</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Action</th>
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
                          <span class="badge badge-soft-{{ $prioColors[$maint->priority] ?? 'secondary' }}">{{ ucfirst($maint->priority) }}</span>
                        </td>
                        <td>Rp{{ number_format($maint->estimated_cost, 0, ',', '.') }}</td>
                        <td>
                          <span class="badge badge-soft-{{ $maint->status === 'completed' ? 'success' : 'danger' }}">{{ ucfirst($maint->status) }}</span>
                        </td>
                        <td>{{ $maint->created_at->format('d/M/y') }}</td>
                        <td>
                          @if($maint->status === 'pending')
                            <form action="{{ route('hk.maintenance.complete', $maint->id) }}" method="POST">
                              @csrf
                              <button class="btn btn-outline-success btn-xs py-1" type="submit"><i class="bi bi-check-lg"></i> Complete</button>
                            </form>
                          @else
                            <span class="text-success small"><i class="bi bi-check-all"></i> Repaired</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-3">No maintenance requests logged.</td>
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
        // Don't require is_charged_to_folio or reservation_id as they can be optional
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
          // Adjust names if they match model expectations
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
    // Set the select box value
    const select = document.getElementById('inspect_task_id');
    if (select) {
      select.value = taskId;
    }
    
    // Focus the tab
    const inspectTab = document.getElementById('inspections-tab');
    if (inspectTab) {
      bootstrap.Tab.getInstance(inspectTab)?.show() || new bootstrap.Tab(inspectTab).show();
    }
  }
</script>
@endsection
