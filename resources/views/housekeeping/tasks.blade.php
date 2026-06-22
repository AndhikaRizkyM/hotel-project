@extends('layouts.admin')

@section('title', 'Housekeeping Cleaning Tasks')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-card-checklist" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING DEPT</p>
      <h1 class="h3 mb-1">Room Cleaning Checklist</h1>
      <p class="text-muted mb-0">Track and assign room cleanings, daily touch-ups, and checkout services.</p>
    </div>
  </div>
</div>

<!-- Filters & Stats Row -->
<div class="row g-3 mb-4">
  <div class="col-12 col-md-8">
    <div class="panel shadow-sm">
      <form method="GET" action="{{ route('hk.tasks') }}" class="row g-2">
        <div class="col-12 col-sm-4">
          <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Cleaning</option>
            <option value="cleaning" {{ request('status') === 'cleaning' ? 'selected' : '' }}>In Progress</option>
            <option value="ready_for_inspection" {{ request('status') === 'ready_for_inspection' ? 'selected' : '' }}>Ready for Inspection</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
          </select>
        </div>
        <div class="col-12 col-sm-4">
          <select name="floor" class="form-select form-select-sm">
            <option value="">All Floors</option>
            <option value="1" {{ request('floor') === '1' ? 'selected' : '' }}>Floor 1</option>
            <option value="2" {{ request('floor') === '2' ? 'selected' : '' }}>Floor 2</option>
            <option value="3" {{ request('floor') === '3' ? 'selected' : '' }}>Floor 3</option>
          </select>
        </div>
        <div class="col-12 col-sm-4 d-flex">
          <button type="submit" class="btn btn-primary btn-sm me-2 w-100"><i class="bi bi-funnel"></i> Filter</button>
          <a href="{{ route('hk.tasks') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="panel shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Room</th>
          <th>Task Type</th>
          <th>Status</th>
          <th>Assigned Attendant</th>
          <th>Timestamps</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tasks as $task)
          <tr>
            <td>
              <strong>Room {{ $task->room->room_number }}</strong>
              <br><small class="text-muted">{{ $task->room->roomType->name }}</small>
            </td>
            <td>
              @php
                $types = [
                  'cleaning_checkout' => 'Checkout Deep Clean',
                  'cleaning_daily' => 'Daily Service Touch-up',
                  'inspection' => 'Room Inspection'
                ];
              @endphp
              {{ $types[$task->task_type] ?? $task->task_type }}
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
                  'cleaning' => 'In Progress',
                  'ready_for_inspection' => 'Inspect Pending',
                  'completed' => 'Completed'
                ];
              @endphp
              <span class="badge bg-{{ $colors[$task->status] ?? 'secondary' }}">{{ $labels[$task->status] ?? $task->status }}</span>
            </td>
            <td>
              @if($task->assignedTo)
                <strong>{{ $task->assignedTo->name }}</strong>
              @else
                <span class="text-muted italic small"><i class="bi bi-person-x"></i> Unassigned</span>
              @endif
            </td>
            <td>
              @if($task->start_time)
                <span class="d-block small text-muted"><strong>Started:</strong> {{ $task->start_time->format('d/m H:i') }}</span>
              @endif
              @if($task->end_time)
                <span class="d-block small text-muted"><strong>Completed:</strong> {{ $task->end_time->format('d/m H:i') }}</span>
              @endif
            </td>
            <td>
              @if($task->status === 'pending')
                <button type="button" class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#startTaskModal-{{ $task->id }}"><i class="bi bi-play-circle"></i> Start Cleaning</button>
              @elseif($task->status === 'cleaning')
                <form action="{{ route('hk.tasks.complete', $task->id) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-xs"><i class="bi bi-check-circle"></i> Finish Cleaning</button>
                </form>
              @elseif($task->status === 'ready_for_inspection')
                <a href="{{ route('hk.inspections.index') }}" class="btn btn-info btn-xs text-white"><i class="bi bi-eye"></i> Log Quality Inspection</a>
              @else
                <span class="text-success small"><i class="bi bi-check2-all"></i> Verified Clean</span>
              @endif
            </td>
          </tr>

          <!-- Modal to start and assign task -->
          @if($task->status === 'pending')
            <div class="modal fade" id="startTaskModal-{{ $task->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-play-fill text-success"></i> Start Cleaning Room {{ $task->room->room_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('hk.tasks.start', $task->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="attendant-{{ $task->id }}" class="form-label small fw-bold">Select Housekeeping Attendant</label>
                        <select name="assigned_to_user_id" id="attendant-{{ $task->id }}" class="form-select form-select-sm" required>
                          <option value="">Select Staff Attendant...</option>
                          @foreach($hkStaff as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success btn-sm">Assign & Start</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @endif
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No active housekeeping cleaning tasks found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
