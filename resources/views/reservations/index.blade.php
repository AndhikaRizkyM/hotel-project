@extends('layouts.admin')

@section('title', 'Bookings')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-journal-bookmark" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
                <h1 class="h3 mb-1">Booking & Reservation Directory</h1>
                <p class="text-muted mb-0">Track reservation histories, active stays, and handle check-in / check-out
                    workflows.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('fo.reservations.create') }}" class="btn btn-primary btn-tactile btn-sm px-4 py-2"><i
                    class="bi bi-journal-plus"></i> Walk-In Booking</a>
        </div>
    </div>

    <!-- Search & Filters Panel -->
    <div class="panel-premium mb-4 shadow-sm p-4">
        <form method="GET" action="{{ route('fo.reservations.index') }}" class="row g-3">
            <div class="col-12 col-md-5">
                <label for="search" class="form-label small fw-bold">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    class="form-control form-control-sm" placeholder="Search by Res No or Guest Name...">
            </div>
            <div class="col-12 col-md-4">
                <label for="status" class="form-label small fw-bold">Status</label>
                <select name="status" id="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="RSV" {{ request('status') == 'RSV' ? 'selected' : '' }}>Reserved</option>
                    <option value="CI" {{ request('status') == 'CI' ? 'selected' : '' }}>Checked In</option>
                    <option value="CO" {{ request('status') == 'CO' ? 'selected' : '' }}>Checked Out</option>
                    <option value="CAN" {{ request('status') == 'CAN' ? 'selected' : '' }}>Cancelled</option>
                    <option value="NS" {{ request('status') == 'NS' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-tactile btn-sm me-2 w-100"><i class="bi bi-search"></i>
                    Search</button>
                <a href="{{ route('fo.reservations.index') }}" class="btn btn-outline-secondary btn-tactile btn-sm w-100"><i
                        class="bi bi-arrow-clockwise"></i> Reset</a>
            </div>
        </form>
    </div>

    <!-- Reservation List Panel -->
    <div class="panel-premium shadow-sm px-4 py-5">
        <table class="table table-responsive table-hover align-middle mb-0 p-4">
            <thead>
                <tr>
                    <th>Res Number</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Check-in / Check-out</th>
                    <th>Total Bill</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                    <tr>
                        <td class="fw-bold text-primary">#{{ $res->reservation_number }}</td>
                        <td>
                            <strong>{{ $res->guest->name }}</strong>
                            <br><span class="text-muted small"><i class="bi bi-telephone"></i>
                                {{ $res->guest->phone }}</span>
                        </td>
                        <td>
                            <strong>Room {{ $res->room->room_number }}</strong>
                            <br><span class="text-muted small">{{ $res->room->roomType->name }}</span>
                        </td>
                        <td>
                            <span class="badge badge-soft-secondary">{{ $res->check_in_date }}</span>
                            <i class="bi bi-arrow-right mx-1 text-muted"></i>
                            <span class="badge badge-soft-secondary">{{ $res->check_out_date }}</span>
                        </td>
                        <td class="fw-semibold">
                            Rp{{ number_format($res->total_charge, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'RSV' => 'badge-soft-info',
                                    'CI' => 'badge-soft-success',
                                    'CO' => 'badge-soft-secondary',
                                    'CAN' => 'badge-soft-danger',
                                    'NS' => 'badge-soft-warning',
                                ];
                                $statusNames = [
                                    'RSV' => 'Reserved',
                                    'CI' => 'Checked In',
                                    'CO' => 'Checked Out',
                                    'CAN' => 'Cancelled',
                                    'NS' => 'No Show',
                                ];
                                $class = $statusColors[$res->status] ?? 'badge-soft-secondary';
                                $name = $statusNames[$res->status] ?? $res->status;
                            @endphp
                            <span class="badge {{ $class }}">{{ $name }}</span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-tactile btn-xs dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                    style="font-size: 0.85rem; border-radius: 12px; border: 1px solid var(--admin-border);">
                                    <li><a class="dropdown-item" href="{{ route('fo.reservations.show', $res->id) }}"><i
                                                class="bi bi-eye text-primary"></i> View Details & Folio</a></li>

                                    @if ($res->status === 'RSV')
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('fo.reservations.check-in', $res->id) }}" method="POST"
                                                onsubmit="return confirm('Confirm check-in for {{ $res->guest->name }}?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success"><i
                                                        class="bi bi-check-circle"></i> Process Check-In</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('fo.reservations.cancel', $res->id) }}" method="POST"
                                                onsubmit="return confirm('Cancel this reservation?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger"><i
                                                        class="bi bi-x-circle"></i> Cancel Reservation</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('fo.reservations.no-show', $res->id) }}" method="POST"
                                                onsubmit="return confirm('Mark this reservation as No Show?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-warning"><i
                                                        class="bi bi-exclamation-circle"></i> Mark as No Show</button>
                                            </form>
                                        </li>
                                    @endif

                                    @if ($res->status === 'CI')
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger"
                                                href="{{ route('fo.reservations.show', $res->id) }}#checkout-section"><i
                                                    class="bi bi-box-arrow-right"></i> Process Check-Out</a></li>
                                    @endif

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('fo.print-registration', $res->id) }}"
                                            target="_blank"><i class="bi bi-printer"></i> Print Reg Card</a></li>
                                    @if ($res->status === 'CI' || $res->status === 'CO')
                                        <li><a class="dropdown-item" href="{{ route('fo.print-invoice', $res->id) }}"
                                                target="_blank"><i class="bi bi-printer"></i> Print Folio Invoice</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No reservations found matching the
                            filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- <div class="table-responsive">
        </div> --}}
    </div>
@endsection
