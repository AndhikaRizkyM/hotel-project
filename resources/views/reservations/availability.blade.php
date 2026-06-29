@extends('layouts.admin')

@section('title', 'Room Availability')

@push('styles')
    <style>
        /* ── Room Detail Modal Premium Styling ── */
        #roomDetailModal .modal-content {
            border: none;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
        }

        #roomDetailModal .modal-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0ea5e9 100%);
            padding: 2rem 2rem 1.5rem;
            border: none;
            position: relative;
        }

        #roomDetailModal .modal-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 30px;
            background: var(--bs-body-bg, #fff);
            border-radius: 1.25rem 1.25rem 0 0;
        }

        #roomDetailModal .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.7;
            transition: opacity 0.2s;
            position: relative;
            z-index: 2;
        }

        #roomDetailModal .modal-header .btn-close:hover {
            opacity: 1;
        }

        .room-modal-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 0.75rem;
        }

        .room-modal-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .room-modal-type {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .room-modal-price-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            color: #fff;
            text-align: center;
        }

        .room-modal-price-badge .price-value {
            font-size: 1.25rem;
            font-weight: 700;
            display: block;
        }

        .room-modal-price-badge .price-unit {
            font-size: 0.65rem;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        #roomDetailModal .modal-body {
            padding: 1rem 2rem 1.5rem;
        }

        .room-detail-section {
            margin-bottom: 1.25rem;
        }

        .room-detail-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--bs-secondary-color, #6c757d);
            margin-bottom: 0.5rem;
        }

        .room-detail-description {
            font-size: 0.875rem;
            color: var(--bs-body-color, #333);
            line-height: 1.6;
        }

        .room-detail-specs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .spec-card {
            background: var(--bs-tertiary-bg, #f8f9fa);
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: 0.75rem;
            padding: 0.75rem;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .spec-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .spec-card i {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
            display: block;
        }

        .spec-card .spec-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--bs-body-color, #333);
            display: block;
        }

        .spec-card .spec-label {
            font-size: 0.65rem;
            color: var(--bs-secondary-color, #6c757d);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .facility-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: var(--bs-tertiary-bg, #f0f4ff);
            border: 1px solid var(--bs-border-color, #d0d7e6);
            color: var(--bs-body-color, #334155);
            padding: 0.3rem 0.7rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .facility-chip:hover {
            background: #0ea5e9;
            color: #fff;
            border-color: #0ea5e9;
            transform: scale(1.05);
        }

        .facility-chip i {
            font-size: 0.7rem;
        }

        .room-modal-footer-tags .tag-yes {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .room-modal-footer-tags .tag-no {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .room-modal-footer-tags .tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        #roomDetailModal .modal-footer {
            border-top: 1px solid var(--bs-border-color, #dee2e6);
            padding: 1rem 2rem;
            background: var(--bs-tertiary-bg, #f8f9fa);
        }

        /* Room card info button */
        .btn-room-info {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            padding: 0;
            border: 1px solid var(--bs-border-color, #dee2e6);
            color: var(--bs-secondary-color, #6c757d);
            background: var(--bs-body-bg, #fff);
            transition: all 0.2s;
            cursor: pointer;
            flex-shrink: 0;
        }

        .btn-room-info:hover {
            background: #0ea5e9;
            border-color: #0ea5e9;
            color: #fff;
            transform: scale(1.1);
        }

        /* Pulse animation for the modal icon */
        @keyframes modalIconPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .room-modal-icon {
            animation: modalIconPulse 2s ease-in-out infinite;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading p-4">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-calendar-range" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
                <h1 class="h3 mb-1">Room Availability Grid</h1>
                <p class="text-muted mb-0">Check live room status, filter by floor/type, and easily make walk-in bookings.
                </p>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="panel-premium mb-4 shadow-sm p-4">
        <form method="GET" action="{{ route('fo.availability') }}" class="row g-3">
            <div class="col-12 col-md-3">
                <label for="room_type_id" class="form-label small fw-bold">Room Type</label>
                <select name="room_type_id" id="room_type_id" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach ($roomTypes as $type)
                        <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="floor" class="form-label small fw-bold">Floor</label>
                <select name="floor" id="floor" class="form-select form-select-sm">
                    <option value="">All Floors</option>
                    <option value="1" {{ request('floor') == '1' ? 'selected' : '' }}>Floor 1</option>
                    <option value="2" {{ request('floor') == '2' ? 'selected' : '' }}>Floor 2</option>
                    <option value="3" {{ request('floor') == '3' ? 'selected' : '' }}>Floor 3</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="status" class="form-label small fw-bold">Status</label>
                <select name="status" id="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="A" {{ request('status') == 'A' ? 'selected' : '' }}>Available</option>
                    <option value="O" {{ request('status') == 'O' ? 'selected' : '' }}>Occupied</option>
                    <option value="D" {{ request('status') == 'D' ? 'selected' : '' }}>Dirty</option>
                    <option value="C" {{ request('status') == 'C' ? 'selected' : '' }}>Cleaning</option>
                    <option value="M" {{ request('status') == 'M' ? 'selected' : '' }}>Maintenance</option>
                    <option value="R" {{ request('status') == 'R' ? 'selected' : '' }}>Reserved</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-tactile btn-sm me-2 w-100"><i class="bi bi-funnel"></i>
                    Filter</button>
                <a href="{{ route('fo.availability') }}" class="btn btn-outline-secondary btn-tactile btn-sm w-100"><i
                        class="bi bi-arrow-clockwise"></i> Reset</a>
            </div>
        </form>
    </div>

    <!-- Grid Map -->
    <div class="panel-premium shadow-sm p-4">
        @php
            $floors = $rooms->groupBy('floor')->sortKeysDesc();
        @endphp

        @forelse($floors as $floor => $floorRooms)
            <div class="mb-4">
                <h6 class="text-uppercase fw-bold text-muted border-bottom pb-2"
                    style="font-size: 0.75rem; letter-spacing: 1px;"><i class="bi bi-layers-half text-secondary me-1"></i>
                    Floor {{ $floor }}</h6>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-6 g-3">
                    @foreach ($floorRooms as $room)
                        <div class="col">
                            <div class="room-bento-card room-{{ $room->status }} btn-tactile">
                                <div class="d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">Room
                                                {{ $room->room_number }}</h6>
                                            <span
                                                class="badge badge-soft-{{ $room->status_color }} room-status-badge">{{ $room->status_text }}</span>
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 0.72rem;">{{ $room->roomType->name }}
                                        </p>
                                        <p class="mb-2 text-body font-monospace fw-semibold"
                                            style="font-size: 0.75rem; opacity: 0.9;">
                                            Rp{{ number_format($room->roomType->price_per_night, 0, ',', '.') }}/N</p>
                                    </div>

                                    <div
                                        class="d-flex align-items-center justify-content-between gap-1 mt-auto pt-2 border-top">
                                        <button type="button" class="btn-room-info border-0 shadow-sm"
                                            title="View Room Details" data-room-number="{{ $room->room_number }}"
                                            data-room-floor="{{ $room->floor }}"
                                            data-room-status="{{ $room->status_text }}"
                                            data-room-status-color="{{ $room->status_color }}"
                                            data-room-type="{{ $room->roomType->name }}"
                                            data-room-description="{{ $room->roomType->description }}"
                                            data-room-capacity="{{ $room->roomType->capacity }}"
                                            data-room-size="{{ $room->roomType->size }}"
                                            data-room-price="{{ $room->roomType->price_per_night }}"
                                            data-room-facilities="{{ $room->roomType->facilities }}"
                                            data-room-breakfast="{{ $room->roomType->breakfast_included ? '1' : '0' }}"
                                            data-room-extrabed="{{ $room->roomType->extra_bed_available ? '1' : '0' }}"
                                            data-room-id="{{ $room->id }}" onclick="showRoomDetail(this)">
                                            <i class="bi bi-info-lg"></i>
                                        </button>

                                        <div class="d-inline-flex gap-1">
                                            @if ($room->status === 'A')
                                                <a href="{{ route('fo.reservations.create', ['room_id' => $room->id]) }}"
                                                    class="btn btn-success btn-tactile btn-xs py-1 px-2"
                                                    style="font-size: 0.7rem;"><i class="bi bi-bookmark-plus"></i> Book</a>
                                            @elseif($room->status === 'R')
                                                @php
                                                    $res = $room->reservations()->where('status', 'RSV')->first();
                                                @endphp
                                                @if ($res)
                                                    <a href="{{ route('fo.reservations.show', $res->id) }}"
                                                        class="btn btn-info text-white btn-tactile btn-xs py-1 px-2"
                                                        style="font-size: 0.7rem;"><i class="bi bi-eye"></i> View</a>
                                                @endif
                                            @elseif($room->status === 'O')
                                                @php
                                                    $res = $room->reservations()->where('status', 'CI')->first();
                                                @endphp
                                                @if ($res)
                                                    <a href="{{ route('fo.reservations.show', $res->id) }}"
                                                        class="btn btn-primary btn-tactile btn-xs py-1 px-2"
                                                        style="font-size: 0.7rem;"><i class="bi bi-receipt"></i> Folio</a>
                                                @endif
                                            @else
                                                <span class="text-muted small text-center py-1"
                                                    style="font-size: 0.65rem; white-space: nowrap;">Service Mode</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-door-closed h1 d-block mb-3"></i>
                <p>No rooms match the selected filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Room Detail Modal -->
    <div class="modal fade" id="roomDetailModal" tabindex="-1" aria-labelledby="roomDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header align-items-start">
                    <div class="d-flex justify-content-between align-items-start w-100"
                        style="position: relative; z-index: 2;">
                        <div>
                            <div class="room-modal-icon">
                                <i class="bi bi-door-open-fill"></i>
                            </div>
                            <div class="room-modal-number" id="modal-room-number">Room 101</div>
                            <div class="room-modal-type" id="modal-room-type">Standard Room</div>
                            <span class="badge mt-2" id="modal-room-status" style="font-size: 0.7rem;">Available</span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <div class="room-modal-price-badge">
                                <span class="price-value" id="modal-room-price">Rp350.000</span>
                                <span class="price-unit">per night</span>
                            </div>
                            <button type="button" class="btn-close mt-1" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <!-- Description -->
                    <div class="room-detail-section">
                        <div class="room-detail-label">Description</div>
                        <p class="room-detail-description mb-0" id="modal-room-description">
                            A cozy room equipped with essential amenities...
                        </p>
                    </div>

                    <!-- Specs Grid -->
                    <div class="room-detail-section">
                        <div class="room-detail-label">Room Specifications</div>
                        <div class="room-detail-specs">
                            <div class="spec-card">
                                <i class="bi bi-people-fill text-primary"></i>
                                <span class="spec-value" id="modal-room-capacity">2</span>
                                <span class="spec-label">Max Guests</span>
                            </div>
                            <div class="spec-card">
                                <i class="bi bi-arrows-fullscreen text-info"></i>
                                <span class="spec-value" id="modal-room-size">20 m²</span>
                                <span class="spec-label">Room Size</span>
                            </div>
                            <div class="spec-card">
                                <i class="bi bi-layers text-warning"></i>
                                <span class="spec-value" id="modal-room-floor">Floor 1</span>
                                <span class="spec-label">Location</span>
                            </div>
                        </div>
                    </div>

                    <!-- Facilities -->
                    <div class="room-detail-section">
                        <div class="room-detail-label">Facilities & Amenities</div>
                        <div class="d-flex flex-wrap gap-2" id="modal-room-facilities">
                            <!-- Dynamically injected -->
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="room-detail-section mb-0">
                        <div class="room-detail-label">Included Services</div>
                        <div class="room-modal-footer-tags d-flex flex-wrap gap-2" id="modal-room-tags">
                            <!-- Dynamically injected -->
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light btn-tactile btn-sm" data-bs-dismiss="modal"><i
                            class="bi bi-x-lg"></i> Close</button>
                    <a href="#" class="btn btn-primary btn-tactile btn-sm" id="modal-book-btn"
                        style="display: none;">
                        <i class="bi bi-bookmark-plus"></i> Book This Room
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Facility icon mapping
        const facilityIcons = {
            'bed': 'bi-house-heart',
            'king bed': 'bi-house-heart',
            'queen bed': 'bi-house-heart',
            'sofa': 'bi-lamp',
            'tv': 'bi-tv',
            'smart tv': 'bi-tv',
            'ac': 'bi-snow',
            'wifi': 'bi-wifi',
            'work desk': 'bi-pc-display',
            'shower': 'bi-droplet-half',
            'bathroom shower': 'bi-droplet-half',
            'mineral water': 'bi-cup-straw',
            'mini pantry': 'bi-cup-hot',
            'mini fridge': 'bi-box-seam',
            'bathtub': 'bi-water',
            'living room': 'bi-house',
            'breakfast': 'bi-egg-fried',
            'family area': 'bi-people',
            'two connected rooms': 'bi-door-open',
            'two bathrooms': 'bi-droplet',
        };

        function getFacilityIcon(facility) {
            const lower = facility.toLowerCase().trim();
            for (const [key, icon] of Object.entries(facilityIcons)) {
                if (lower.includes(key)) return icon;
            }
            return 'bi-check-circle';
        }

        function showRoomDetail(button) {
            const data = button.dataset;

            // Header
            document.getElementById('modal-room-number').textContent = 'Room ' + data.roomNumber;
            document.getElementById('modal-room-type').textContent = data.roomType;

            // Status badge
            const statusBadge = document.getElementById('modal-room-status');
            statusBadge.textContent = data.roomStatus;
            statusBadge.className = 'badge mt-2 bg-' + data.roomStatusColor;

            // Price
            const price = parseFloat(data.roomPrice);
            document.getElementById('modal-room-price').textContent = 'Rp' + price.toLocaleString('id-ID');

            // Description
            document.getElementById('modal-room-description').textContent = data.roomDescription;

            // Specs
            document.getElementById('modal-room-capacity').textContent = data.roomCapacity;
            document.getElementById('modal-room-size').textContent = data.roomSize + ' m²';
            document.getElementById('modal-room-floor').textContent = 'Floor ' + data.roomFloor;

            // Facilities
            const facilitiesContainer = document.getElementById('modal-room-facilities');
            facilitiesContainer.innerHTML = '';
            if (data.roomFacilities) {
                const facilities = data.roomFacilities.split(',').map(f => f.trim()).filter(f => f);
                facilities.forEach(facility => {
                    const chip = document.createElement('span');
                    chip.className = 'facility-chip';
                    chip.innerHTML = `<i class="bi ${getFacilityIcon(facility)}"></i> ${facility}`;
                    facilitiesContainer.appendChild(chip);
                });
            }

            // Tags (Breakfast + Extra Bed)
            const tagsContainer = document.getElementById('modal-room-tags');
            tagsContainer.innerHTML = '';

            const breakfastTag = document.createElement('span');
            if (data.roomBreakfast === '1') {
                breakfastTag.className = 'tag tag-yes';
                breakfastTag.innerHTML = '<i class="bi bi-check-circle-fill"></i> Breakfast Included';
            } else {
                breakfastTag.className = 'tag tag-no';
                breakfastTag.innerHTML = '<i class="bi bi-x-circle-fill"></i> No Breakfast';
            }
            tagsContainer.appendChild(breakfastTag);

            const extrabedTag = document.createElement('span');
            if (data.roomExtrabed === '1') {
                extrabedTag.className = 'tag tag-yes';
                extrabedTag.innerHTML = '<i class="bi bi-check-circle-fill"></i> Extra Bed Available';
            } else {
                extrabedTag.className = 'tag tag-no';
                extrabedTag.innerHTML = '<i class="bi bi-x-circle-fill"></i> No Extra Bed';
            }
            tagsContainer.appendChild(extrabedTag);

            // Book button
            const bookBtn = document.getElementById('modal-book-btn');
            if (data.roomStatus === 'Available') {
                bookBtn.style.display = 'inline-flex';
                bookBtn.href = "{{ url('fo/reservations/create') }}?room_id=" + data.roomId;
            } else {
                bookBtn.style.display = 'none';
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('roomDetailModal'));
            modal.show();
        }
    </script>
@endpush
