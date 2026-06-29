@extends('layouts.admin')

@section('title', 'Edit Room')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-pencil" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">HOTEL ROOMS</p>
                <h1 class="h3 mb-1">Edit Room</h1>
                <p class="text-muted mb-0">Modify configuration for unit <strong>Room {{ $room->room_number }}</strong>.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('master.rooms.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2"><i
                    class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="panel p-4">
                <form action="{{ route('master.rooms.update', $room->id) }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="roomNumber">Room Number</label>
                        <input class="form-control" name="room_number" id="roomNumber" type="text"
                            value="{{ old('room_number', $room->room_number) }}" required>
                        <div class="invalid-feedback">Room number is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="roomFloor">Floor</label>
                        <input class="form-control" name="floor" id="roomFloor" type="number"
                            value="{{ old('floor', $room->floor) }}" required>
                        <div class="invalid-feedback">Floor number is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="roomTypeId">Room Class / Type</label>
                        <select class="form-select" name="room_type_id" id="roomTypeId" required>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('room_type_id', $room->room_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} (Rp{{ number_format($type->price_per_night, 0, ',', '.') }}/night)
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a room type.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="roomStatus">Room Status</label>
                        <select class="form-select" name="status" id="roomStatus" required>
                            <option value="A" {{ old('status', $room->status) === 'A' ? 'selected' : '' }}>Available
                            </option>
                            <option value="O" {{ old('status', $room->status) === 'O' ? 'selected' : '' }}>Occupied
                            </option>
                            <option value="D" {{ old('status', $room->status) === 'D' ? 'selected' : '' }}>Dirty
                            </option>
                            <option value="C" {{ old('status', $room->status) === 'C' ? 'selected' : '' }}>Cleaning
                            </option>
                            <option value="M" {{ old('status', $room->status) === 'M' ? 'selected' : '' }}>Maintenance
                            </option>
                            <option value="R" {{ old('status', $room->status) === 'R' ? 'selected' : '' }}>Reserved
                            </option>
                            <option value="B" {{ old('status', $room->status) === 'B' ? 'selected' : '' }}>Blocked
                            </option>
                        </select>
                        <div class="invalid-feedback">Please select status.</div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
