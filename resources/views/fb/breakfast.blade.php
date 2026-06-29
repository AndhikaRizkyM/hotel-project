@extends('layouts.admin')

@section('title', 'Breakfast Entitlements')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <div class="page-icon" style="border-radius: 12px; background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i
                    class="bi bi-egg-fried" aria-hidden="true"></i></div>
            <div>
                <p class="eyebrow mb-1">FOOD & BEVERAGE DEPT</p>
                <h1 class="h3 mb-1 fw-bold">Daily Breakfast Entitlements</h1>
                <p class="text-muted mb-0">Track preparation, delivery status, and historical timeline for in-house guest
                    breakfasts.</p>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="panel mb-4 shadow-sm border-0"
        style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
        <form method="GET" action="{{ route('fb.breakfast') }}" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-md-4">
                <label for="date" class="form-label small fw-bold">Select Date</label>
                <div class="input-group input-group-sm">
                    <input type="date" name="date" id="date" value="{{ $date }}"
                        class="form-control me-3" style="border-radius: 8px 0 0 8px;">
                    <button type="submit" class="btn btn-primary btn-tactile px-4 py-2"><i
                            class="bi bi-calendar-check"></i> Load
                        Date</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Guests Entitlements list -->
    <div class="panel shadow-sm border-0" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 15%; font-size: 0.72rem; letter-spacing: 0.5px;">Room</th>
                        <th style="width: 30%; font-size: 0.72rem; letter-spacing: 0.5px;">Guest Details</th>
                        <th style="width: 20%; font-size: 0.72rem; letter-spacing: 0.5px;">Entitlement</th>
                        <th style="width: 20%; font-size: 0.72rem; letter-spacing: 0.5px;">Status & Timeline</th>
                        <th style="width: 15%; font-size: 0.72rem; letter-spacing: 0.5px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guests as $res)
                        @php
                            $br = $res->breakfast_record;
                            $brColors = [
                                'Pending' => 'secondary',
                                'Preparing' => 'warning',
                                'Delivered' => 'success',
                                'Skipped' => 'danger',
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span
                                        class="avatar bg-primary-subtle text-primary fw-bold me-2 rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 38px; height: 38px; font-size: 0.85rem; border: 2px solid var(--admin-primary);">
                                        {{ $res->room->room_number }}
                                    </span>
                                    <div>
                                        <strong>Room {{ $res->room->room_number }}</strong>
                                        <div class="text-xs text-muted" style="font-size: 0.72rem;">
                                            {{ $res->room->roomType->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-body">{{ $res->guest->name }}</div>
                                <div class="text-xs text-muted" style="font-size: 0.72rem;">Res No: <span
                                        class="font-monospace fw-semibold">{{ $res->reservation_number }}</span></div>
                                <div class="text-xs text-muted mt-1" style="font-size: 0.72rem;"><i
                                        class="bi bi-people-fill text-secondary"></i> Capacity: <strong>{{ $br->pax }}
                                        Pax</strong></div>
                            </td>
                            <td>
                                @if ($res->room->roomType->breakfast_included)
                                    <span class="badge badge-soft-success"><i class="bi bi-check-circle-fill"></i>
                                        Complimentary</span>
                                @else
                                    <span class="badge badge-soft-warning"><i class="bi bi-exclamation-triangle-fill"></i>
                                        Add-on Paid</span>
                                @endif
                            </td>
                            <td>
                                <div class="mb-2">
                                    <span
                                        class="badge badge-soft-{{ $brColors[$br->status] ?? 'light' }} room-status-badge">{{ $br->status }}</span>
                                </div>

                                <!-- Mini Timeline -->
                                @if ($br->timeline && is_array($br->timeline))
                                    <div class="ps-1 small" style="font-size: 0.72rem;">
                                        @php
                                            $steps = array_slice(array_reverse($br->timeline), 0, 3);
                                        @endphp
                                        @foreach ($steps as $index => $step)
                                            <div
                                                class="breakfast-timeline-item {{ $index === 0 ? 'active-step' : 'done-step' }}">
                                                <span class="fw-bold text-body"
                                                    style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($step['time'])->format('H:i') }}</span>
                                                <span class="text-muted">: {{ $step['status'] }}</span>
                                                <span class="text-muted font-monospace"
                                                    style="font-size: 0.68rem;">({{ $step['user'] ?? 'Staff' }})</span>
                                            </div>
                                        @endforeach

                                        @if (count($br->timeline) > 3)
                                            <div class="text-xs text-primary cursor-pointer mt-1 fw-bold btn-tactile"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#fullTimeline-{{ $br->id }}">
                                                Show full timeline <i class="bi bi-chevron-down"></i>
                                            </div>
                                            <div class="collapse mt-2" id="fullTimeline-{{ $br->id }}">
                                                @foreach (array_slice(array_reverse($br->timeline), 3) as $step)
                                                    <div class="breakfast-timeline-item done-step">
                                                        <span class="fw-bold text-body"
                                                            style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($step['time'])->format('H:i') }}</span>
                                                        <span class="text-muted">: {{ $step['status'] }}</span>
                                                        <span class="text-muted font-monospace"
                                                            style="font-size: 0.68rem;">({{ $step['user'] ?? 'Staff' }})</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if ($br->notes)
                                    <div class="text-xs text-muted mt-2 italic" style="font-size: 0.72rem;"><i
                                            class="bi bi-chat-left-text text-secondary me-1"></i> "{{ $br->notes }}"
                                    </div>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    @if ($br->status === 'Pending')
                                        <button type="button" class="btn btn-warning btn-tactile btn-xs py-1"
                                            onclick="openBreakfastModal('{{ $br->id }}', 'Preparing')">
                                            <i class="bi bi-play-fill"></i> Prepare
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-tactile btn-xs py-1"
                                            onclick="openBreakfastModal('{{ $br->id }}', 'Skipped')">
                                            <i class="bi bi-x-lg"></i> Skip
                                        </button>
                                    @elseif($br->status === 'Preparing')
                                        <button type="button" class="btn btn-success btn-tactile btn-xs py-1"
                                            onclick="openBreakfastModal('{{ $br->id }}', 'Delivered')">
                                            <i class="bi bi-check-lg"></i> Deliver
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-tactile btn-xs py-1"
                                            onclick="openBreakfastModal('{{ $br->id }}', 'Skipped')">
                                            <i class="bi bi-x-lg"></i> Skip
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-tactile btn-xs py-1"
                                            onclick="openBreakfastModal('{{ $br->id }}', 'Pending')">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5" style="font-size: 0.85rem;">No active
                                checked-in guests scheduled for breakfast entitlements on this date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Breakfast Status Update -->
    <div class="modal fade" id="breakfastStatusModal" tabindex="-1" aria-labelledby="breakfastStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="breakfastStatusModalLabel">Update Breakfast Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="breakfastStatusForm" method="POST" action="">
                    @csrf
                    <div class="modal-body p-4">
                        <input type="hidden" name="status" id="breakfast_status_input" value="">

                        <p class="small text-muted mb-3" id="breakfast_status_confirm_msg">
                            Are you sure you want to update the breakfast status?
                        </p>

                        <div class="mb-0">
                            <label for="breakfast_notes" class="form-label small fw-bold">Notes / Special Instructions
                                (Optional)</label>
                            <textarea name="notes" id="breakfast_notes" rows="2" class="form-control form-control-sm"
                                placeholder="e.g. No onions, extra chili, or skip reason..." style="border-radius: 8px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light btn-sm btn-tactile"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm btn-tactile"
                            id="breakfast_submit_btn">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openBreakfastModal(recordId, status) {
            const form = document.getElementById('breakfastStatusForm');
            const statusInput = document.getElementById('breakfast_status_input');
            const confirmMsg = document.getElementById('breakfast_status_confirm_msg');
            const submitBtn = document.getElementById('breakfast_submit_btn');
            const modalLabel = document.getElementById('breakfastStatusModalLabel');
            const notesArea = document.getElementById('breakfast_notes');

            notesArea.value = '';
            form.action = `/fb/breakfast/${recordId}/status`;
            statusInput.value = status;
            modalLabel.textContent = `Update Status to: ${status}`;

            let btnClass = 'btn-primary';
            if (status === 'Preparing') btnClass = 'btn-warning';
            else if (status === 'Delivered') btnClass = 'btn-success';
            else if (status === 'Skipped') btnClass = 'btn-danger';
            else if (status === 'Pending') btnClass = 'btn-secondary';

            submitBtn.className = `btn ${btnClass} btn-sm btn-tactile`;
            submitBtn.textContent = `Confirm: ${status}`;

            confirmMsg.innerHTML =
                `Are you sure you want to update the breakfast status for this room to <strong>${status}</strong>? Please enter any special instructions or skip reason below:`;

            const modalEl = document.getElementById('breakfastStatusModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalInstance.show();
        }
    </script>
@endpush
